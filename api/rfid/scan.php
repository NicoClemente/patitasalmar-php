<?php
/**
 * API para escaneo RFID - Versión simplificada
 * Ruta: /patitasalmar-php/api/rfid/scan.php
 */

// Headers necesarios
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

// Función para responder con JSON
function jsonResponse($success, $message, $data = null) {
    $response = ['success' => $success, 'message' => $message];
    if ($data) {
        $response = array_merge($response, $data);
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

// Obtener datos del POST
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Si no hay JSON, intentar POST normal
if (!$data) {
    $data = $_POST;
}

$rfidTag = isset($data['rfidTag']) ? trim(strtoupper($data['rfidTag'])) : '';

// Validar input
if (empty($rfidTag)) {
    jsonResponse(false, 'Tag RFID requerido');
}

if (!preg_match('/^[A-Z0-9]{3,20}$/', $rfidTag)) {
    jsonResponse(false, 'Tag RFID debe tener entre 3 y 20 caracteres alfanuméricos');
}

// Log del intento
error_log("RFID API: Buscando tag '$rfidTag'");

try {
    // Configuración de base de datos directa
    $host = 'localhost';
    $port = '3307';  // Tu puerto XAMPP
    $database = 'patitasalmar_db';
    $username = 'root';
    $password = '';
    
    // Conectar
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscar mascota
    $sql = "SELECT p.*, u.name as owner_name, u.email as owner_email, u.phone as owner_phone
            FROM pets p
            LEFT JOIN users u ON p.owner_id = u.id
            WHERE p.rfid_tag = :tag";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tag', $rfidTag);
    $stmt->execute();
    
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$pet) {
        error_log("RFID API: Tag '$rfidTag' no encontrado");
        jsonResponse(false, 'Mascota no encontrada');
    }
    
    // Registrar escaneo
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        $scanSql = "INSERT INTO scans (pet_id, scanned_at, ip_address, user_agent) VALUES (?, NOW(), ?, ?)";
        $scanStmt = $pdo->prepare($scanSql);
        $scanStmt->execute([$pet['id'], $ip, $userAgent]);
    } catch (Exception $e) {
        error_log("Error registrando escaneo: " . $e->getMessage());
    }
    
    // Emoji por especie
    $emojis = [
        'Perro' => '🐕',
        'Gato' => '🐱',
        'Ave' => '🐦',
        'Conejo' => '🐰',
        'Pez' => '🐟',
        'Reptil' => '🦎',
        'Hamster' => '🐹'
    ];
    $emoji = $emojis[$pet['species']] ?? '🐾';
    
    // Respuesta exitosa
    error_log("RFID API: Mascota encontrada: {$pet['name']} ({$rfidTag})");
    
    jsonResponse(true, 'Mascota encontrada', [
        'pet' => [
            'id' => $pet['id'],
            'name' => $pet['name'],
            'species' => $pet['species'],
            'species_emoji' => $emoji,
            'breed' => $pet['breed'],
            'age' => $pet['age'] ? (int)$pet['age'] : null,
            'rfid_tag' => $pet['rfid_tag'],
            'description' => $pet['description'],
            'image_url' => $pet['image_url'],
            'registered_date' => $pet['created_at'],
            'owner' => $pet['owner_name'] ? [
                'name' => $pet['owner_name'],
                'email' => $pet['owner_email'],
                'phone' => $pet['owner_phone']
            ] : null
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("RFID API: Error BD: " . $e->getMessage());
    jsonResponse(false, 'Error de conexión a la base de datos: ' . $e->getMessage());
} catch (Exception $e) {
    error_log("RFID API: Error general: " . $e->getMessage());
    jsonResponse(false, 'Error del servidor: ' . $e->getMessage());
}
?>