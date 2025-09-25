<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$rfidTag = sanitizeInput($input['rfidTag'] ?? '');

if (empty($rfidTag)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tag RFID requerido']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT p.*, u.name as owner_name, u.email as owner_email, u.phone as owner_phone
              FROM pets p
              LEFT JOIN users u ON p.owner_id = u.id
              WHERE p.rfid_tag = :rfid_tag";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':rfid_tag', $rfidTag);
    $stmt->execute();
    
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$pet) {
        echo json_encode([
            'success' => false,
            'message' => 'Mascota no encontrada'
        ]);
        exit();
    }
    
    // Registrar el escaneo
    $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    $scanQuery = "INSERT INTO scans (pet_id, scanned_at, ip_address) VALUES (:pet_id, NOW(), :ip_address)";
    $scanStmt = $db->prepare($scanQuery);
    $scanStmt->bindParam(':pet_id', $pet['id']);
    $scanStmt->bindParam(':ip_address', $clientIP);
    $scanStmt->execute();
    
    echo json_encode([
        'success' => true,
        'pet' => [
            'id' => $pet['id'],
            'name' => $pet['name'],
            'species' => $pet['species'],
            'breed' => $pet['breed'],
            'description' => $pet['description'],
            'imageUrl' => $pet['image_url'],
            'owner' => [
                'name' => $pet['owner_name'],
                'email' => $pet['owner_email'],
                'phone' => $pet['owner_phone']
            ]
        ]
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
}
?>
                    