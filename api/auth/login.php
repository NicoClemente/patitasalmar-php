<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../config/database.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

// Obtener datos del request
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    $input = $_POST;
}

$email = sanitizeInput($input['email'] ?? '');
$password = $input['password'] ?? '';

// Validaciones básicas
if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email y contraseña son requeridos']);
    exit();
}

if (!validateEmail($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email no válido']);
    exit();
}

// Rate limiting básico
$clientIP = getClientIP();
if (!checkRateLimit('login_' . $clientIP, 5, 300)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Demasiados intentos. Intenta en 5 minutos.']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('Error de conexión a la base de datos');
    }
    
    // Buscar usuario por email
    $query = "SELECT * FROM users WHERE email = :email AND role != 'disabled'";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || !verifyPassword($password, $user['password'])) {
        // Log del intento fallido
        logActivity('login_failed', 'Email: ' . $email, null);
        
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
        exit();
    }
    
    // Generar token
    $token = generateToken($user);
    
    // Iniciar sesión
    session_start();
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role']
    ];
    $_SESSION['token'] = $token;
    
    // Log del login exitoso
    logActivity('login_success', 'Usuario inició sesión', $user['id']);
    
    // Actualizar último login
    $updateQuery = "UPDATE users SET updated_at = NOW() WHERE id = :id";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindParam(':id', $user['id']);
    $updateStmt->execute();
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'message' => 'Login exitoso',
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'created_at' => $user['created_at']
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Database error in login: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
} catch (Exception $e) {
    error_log("General error in login: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
}
?>