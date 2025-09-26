<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../../config/database.php';
require_once '../../includes/functions.php';

try {
    $user = requireAuth();
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('Error de conexión a la base de datos');
    }
    
    $stats = [];
    
    if ($user['role'] === 'admin') {
        // Estadísticas para administrador
        
        // Total de mascotas
        $stmt = $db->prepare("SELECT COUNT(*) as total_pets FROM pets");
        $stmt->execute();
        $stats['totalPets'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total_pets'];
        
        // Total de usuarios
        $stmt = $db->prepare("SELECT COUNT(*) as total_users FROM users WHERE role = 'user'");
        $stmt->execute();
        $stats['totalUsers'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
        
        // Escaneos recientes
        $stmt = $db->prepare("SELECT COUNT(*) as recent_scans FROM scans WHERE scanned_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $stmt->execute();
        $stats['recentScans'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['recent_scans'];
        
        // Registros de hoy
        $stmt = $db->prepare("SELECT COUNT(*) as today_pets FROM pets WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        $stats['todayPets'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['today_pets'];
        
    } else {
        // Estadísticas para usuario normal
        
        // Mascotas del usuario
        $stmt = $db->prepare("SELECT COUNT(*) as total_pets FROM pets WHERE owner_id = :owner_id");
        $stmt->bindParam(':owner_id', $user['id']);
        $stmt->execute();
        $stats['totalPets'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total_pets'];
        
        // Escaneos de sus mascotas
        $stmt = $db->prepare("
            SELECT COUNT(*) as recent_scans 
            FROM scans s 
            JOIN pets p ON s.pet_id = p.id 
            WHERE p.owner_id = :owner_id 
            AND s.scanned_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ");
        $stmt->bindParam(':owner_id', $user['id']);
        $stmt->execute();
        $stats['recentScans'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['recent_scans'];
        
        $stats['totalUsers'] = 0; // No relevante para usuarios normales
    }
    
    echo json_encode([
        'success' => true,
        'stats' => $stats
    ]);
    
} catch (Exception $e) {
    error_log("Error in dashboard stats API: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error del servidor'
    ]);
}
?>