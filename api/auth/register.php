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
$name = sanitizeInput($input['name'] ?? '');
$email = sanitizeInput($input['email'] ?? '');
$password = $input['password'] ?? '';

if (empty($name) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
    exit();
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verificar si el usuario ya existe
    $query = "SELECT id FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'El usuario ya existe']);
        exit();
    }
    
    // Crear nuevo usuario
    $hashedPassword = hashPassword($password);
    $userId = uniqid('user_');
    
    $query = "INSERT INTO users (id, name, email, password, role, created_at) VALUES (:id, :name, :email, :password, 'user', NOW())";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $userId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    
    if ($stmt->execute()) {
        $user = [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'role' => 'user'
        ];
        
        $token = generateToken($user);
        
        echo json_encode([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al crear el usuario']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
}
?>