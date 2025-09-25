<?php
require_once '../config/database.php';

function generateToken($user) {
    $payload = array(
        'id' => $user['id'],
        'email' => $user['email'],
        'role' => $user['role'],
        'exp' => time() + (24 * 60 * 60) // 24 horas
    );
    return base64_encode(json_encode($payload));
}

function verifyToken($token) {
    try {
        $decoded = json_decode(base64_decode($token), true);
        if ($decoded && $decoded['exp'] > time()) {
            return $decoded;
        }
    } catch (Exception $e) {
        return false;
    }
    return false;
}

function requireAuth() {
    $headers = getallheaders();
    $token = null;
    
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
    } elseif (isset($_SESSION['token'])) {
        $token = $_SESSION['token'];
    }
    
    if (!$token) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Token requerido']);
        exit();
    }
    
    $user = verifyToken($token);
    if (!$user) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Token inválido']);
        exit();
    }
    
    return $user;
}

function requireRole($requiredRole) {
    $user = requireAuth();
    if ($user['role'] !== $requiredRole && $user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
        exit();
    }
    return $user;
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function getSpeciesEmoji($species) {
    $emojis = array(
        'perro' => '🐕',
        'gato' => '🐱',
        'ave' => '🐦',
        'conejo' => '🐰',
        'pez' => '🐟'
    );
    return isset($emojis[strtolower($species)]) ? $emojis[strtolower($species)] : '🐾';
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>