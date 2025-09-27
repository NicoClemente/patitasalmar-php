<?php
/**
 * Configuración segura de base de datos para producción
 * Usa variables de entorno para credenciales sensibles
 */

class Database {
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Cargar configuración desde variables de entorno o archivo config
        $this->loadConfig();
    }

    private function loadConfig() {
        // Intentar cargar desde variables de entorno primero (producción)
        if ($this->loadFromEnvironment()) {
            return;
        }
        
        // Si no hay variables de entorno, cargar desde archivo local (desarrollo)
        if ($this->loadFromConfigFile()) {
            return;
        }
        
        // Fallback para desarrollo local (XAMPP)
        $this->loadDefaultConfig();
    }

    private function loadFromEnvironment() {
        $host = getenv('DB_HOST') ?: $_ENV['DB_HOST'] ?? null;
        $port = getenv('DB_PORT') ?: $_ENV['DB_PORT'] ?? null;
        $database = getenv('DB_NAME') ?: $_ENV['DB_NAME'] ?? null;
        $username = getenv('DB_USER') ?: $_ENV['DB_USER'] ?? null;
        $password = getenv('DB_PASS') ?: $_ENV['DB_PASS'] ?? null;
        
        if ($host && $database && $username !== null && $password !== null) {
            $this->host = $host;
            $this->port = $port ?: '3306';
            $this->db_name = $database;
            $this->username = $username;
            $this->password = $password;
            
            error_log("Database: Configuración cargada desde variables de entorno");
            return true;
        }
        
        return false;
    }

    private function loadFromConfigFile() {
        $configFile = __DIR__ . '/config.local.php';
        
        if (file_exists($configFile)) {
            $config = include $configFile;
            
            if (is_array($config) && isset($config['database'])) {
                $db = $config['database'];
                $this->host = $db['host'];
                $this->port = $db['port'] ?? '3306';
                $this->db_name = $db['name'];
                $this->username = $db['user'];
                $this->password = $db['password'];
                
                error_log("Database: Configuración cargada desde config.local.php");
                return true;
            }
        }
        
        return false;
    }

    private function loadDefaultConfig() {
        // Solo para desarrollo local (XAMPP)
        $this->host = 'localhost';
        $this->port = '3307';  // Puerto XAMPP local
        $this->db_name = 'patitasalmar_db';
        $this->username = 'root';
        $this->password = '';
        
        error_log("Database: Usando configuración por defecto (desarrollo)");
    }

    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";
            
            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
            
            // Log de conexión exitosa (sin mostrar credenciales)
            error_log("Database: Conexión exitosa a {$this->host}:{$this->port}/{$this->db_name}");
            
        } catch(PDOException $exception) {
            error_log("Database: Error de conexión: " . $exception->getMessage());
            
            // En desarrollo, mostrar el error. En producción, no.
            if ($this->isDevelopment()) {
                echo "Error de conexión: " . $exception->getMessage();
            } else {
                echo "Error de conexión a la base de datos";
            }
        }
        
        return $this->conn;
    }

    /**
     * Verificar si estamos en ambiente de desarrollo
     */
    private function isDevelopment() {
        return in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']) || 
               isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'development';
    }

    /**
     * Método para probar la conexión
     */
    public function testConnection() {
        try {
            $conn = $this->getConnection();
            if ($conn) {
                $stmt = $conn->prepare("SELECT 1 as test, @@version as mysql_version, @@port as mysql_port");
                $stmt->execute();
                $result = $stmt->fetch();
                
                if ($result && $result['test'] == 1) {
                    return array(
                        'success' => true,
                        'message' => 'Conexión exitosa a la base de datos',
                        'mysql_version' => $result['mysql_version'],
                        'mysql_port' => $result['mysql_port'],
                        'host' => $this->host,
                        'port' => $this->port,
                        'database' => $this->db_name,
                        'user' => $this->username,
                        'environment' => $this->isDevelopment() ? 'development' : 'production'
                    );
                }
            }
            return array(
                'success' => false,
                'message' => 'No se pudo establecer conexión'
            );
        } catch(Exception $e) {
            return array(
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            );
        }
    }

    /**
     * Obtener información de configuración (sin credenciales)
     */
    public function getConfigInfo() {
        return [
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->db_name,
            'username' => $this->username,
            'password_set' => !empty($this->password),
            'environment' => $this->isDevelopment() ? 'development' : 'production'
        ];
    }
}

// ============================================
// FUNCIONES AUXILIARES (sin cambios)
// ============================================

function generateUniqueId($prefix = '') {
    return $prefix . uniqid() . '_' . mt_rand(1000, 9999);
}

function validateRFIDTag($tag) {
    return preg_match('/^[A-Za-z0-9]{4,20}$/', $tag);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
}

function checkRateLimit($key, $limit, $window) {
    return true; // Implementar en producción
}

function logActivity($action, $description, $userId = null) {
    $timestamp = date('Y-m-d H:i:s');
    $userInfo = $userId ? "User: $userId" : "Guest";
    error_log("[$timestamp] [ACTIVITY] $action: $description ($userInfo)");
}

function formatDate($date, $format = 'd/m/Y H:i') {
    if (!$date || $date === '0000-00-00 00:00:00') {
        return '-';
    }
    
    try {
        $timestamp = strtotime($date);
        if ($timestamp === false) return '-';
        
        return date($format, $timestamp);
    } catch (Exception $e) {
        return '-';
    }
}

function getSpeciesEmoji($species) {
    $emojis = [
        'perro' => '🐕',
        'gato' => '🐱',
        'ave' => '🐦',
        'conejo' => '🐰',
        'pez' => '🐟',
        'reptil' => '🦎',
        'hamster' => '🐹',
        'otro' => '🐾'
    ];
    
    return $emojis[strtolower($species)] ?? '🐾';
}

function sanitizeInput($data) {
    if (is_null($data)) {
        return null;
    }
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    return $data;
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function generateToken($user) {
    $payload = [
        'id' => $user['id'],
        'email' => $user['email'],
        'role' => $user['role'],
        'exp' => time() + (24 * 60 * 60)
    ];
    
    return base64_encode(json_encode($payload));
}

function verifyToken($token) {
    try {
        $decoded = json_decode(base64_decode($token), true);
        
        if ($decoded && isset($decoded['exp']) && $decoded['exp'] > time()) {
            return $decoded;
        }
    } catch (Exception $e) {
        return false;
    }
    
    return false;
}

function requireAuth() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    }
    
    $headers = getallheaders();
    $token = null;
    
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
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

function jsonResponse($success, $message = '', $data = []) {
    header('Content-Type: application/json; charset=utf-8');
    
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if (!empty($data)) {
        $response = array_merge($response, $data);
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

function validatePetData($data) {
    $errors = [];
    
    if (empty($data['name'])) {
        $errors[] = 'El nombre es requerido';
    }
    
    if (empty($data['species'])) {
        $errors[] = 'La especie es requerida';
    }
    
    if (empty($data['rfidTag'])) {
        $errors[] = 'El tag RFID es requerido';
    } elseif (!validateRFIDTag($data['rfidTag'])) {
        $errors[] = 'El tag RFID debe tener entre 4 y 20 caracteres alfanuméricos';
    }
    
    if (isset($data['age']) && $data['age'] !== null) {
        $age = intval($data['age']);
        if ($age < 0 || $age > 30) {
            $errors[] = 'La edad debe estar entre 0 y 30 años';
        }
    }
    
    if (!empty($data['imageUrl'])) {
        if (!filter_var($data['imageUrl'], FILTER_VALIDATE_URL)) {
            $errors[] = 'La URL de la imagen no es válida';
        }
    }
    
    return $errors;
}

function handleDatabaseError($e, $operation = 'operación') {
    error_log("Database Error in $operation: " . $e->getMessage());
    
    $isDevelopment = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']);
    
    if ($isDevelopment) {
        return "Error de base de datos: " . $e->getMessage();
    } else {
        return "Error interno del servidor. Por favor intenta más tarde.";
    }
}

function setCORSHeaders() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

function debugLog($data, $label = 'DEBUG') {
    if (in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1'])) {
        $timestamp = date('Y-m-d H:i:s');
        error_log("[$timestamp] [$label] " . print_r($data, true));
    }
}

function generatePetQRCode($petId, $rfidTag) {
    return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode("https://patitasalmar.com/pet/$petId");
}
?>