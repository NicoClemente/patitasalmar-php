<?php
header('Content-Type: application/json');
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$user = requireAuth();

$input = json_decode(file_get_contents('php://input'), true);
$name = sanitizeInput($input['name'] ?? '');
$species = sanitizeInput($input['species'] ?? '');
$breed = sanitizeInput($input['breed'] ?? '');
$age = isset($input['age']) ? (int)$input['age'] : null;
$rfidTag = sanitizeInput($input['rfidTag'] ?? '');
$description = sanitizeInput($input['description'] ?? '');
$imageUrl = sanitizeInput($input['imageUrl'] ?? '');

if (empty($name) || empty($species) || empty($rfidTag)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nombre, especie y tag RFID son requeridos']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verificar que el tag RFID no esté en uso
    $query = "SELECT id FROM pets WHERE rfid_tag = :rfid_tag";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':rfid_tag', $rfidTag);
    $stmt->execute();
    
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Este tag RFID ya está registrado']);
        exit();
    }
    
    // Crear nueva mascota
    $petId = uniqid('pet_');
    
    $query = "INSERT INTO pets (id, name, species, breed, age, rfid_tag, owner_id, description, image_url, created_at, updated_at) 
              VALUES (:id, :name, :species, :breed, :age, :rfid_tag, :owner_id, :description, :image_url, NOW(), NOW())";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $petId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':species', $species);
    $stmt->bindParam(':breed', $breed);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':rfid_tag', $rfidTag);
    $stmt->bindParam(':owner_id', $user['id']);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image_url', $imageUrl);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Mascota registrada exitosamente',
            'petId' => $petId
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al registrar la mascota']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
}
?>