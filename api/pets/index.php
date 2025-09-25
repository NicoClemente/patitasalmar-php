<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
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
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            handleGetPets($db, $user);
            break;
        case 'POST':
            handleCreatePet($db, $user);
            break;
        case 'PUT':
            handleUpdatePet($db, $user);
            break;
        case 'DELETE':
            handleDeletePet($db, $user);
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            break;
    }
    
} catch (Exception $e) {
    error_log("Error in pets API: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor']);
}

function handleGetPets($db, $user) {
    $page = (int)($_GET['page'] ?? 1);
    $limit = (int)($_GET['limit'] ?? 10);
    $search = sanitizeInput($_GET['search'] ?? '');
    $species = sanitizeInput($_GET['species'] ?? '');
    
    $offset = ($page - 1) * $limit;
    
    // Construir consulta según rol del usuario
    $whereClause = '';
    $params = [];
    
    if ($user['role'] === 'admin') {
        // Admin ve todas las mascotas
        $baseQuery = "FROM pets p LEFT JOIN users u ON p.owner_id = u.id";
        $selectFields = "p.*, u.name as owner_name, u.email as owner_email, u.phone as owner_phone";
    } else {
        // Usuario normal solo ve sus mascotas
        $baseQuery = "FROM pets p";
        $selectFields = "p.*";
        $whereClause = "WHERE p.owner_id = :owner_id";
        $params['owner_id'] = $user['id'];
    }
    
    // Agregar filtros de búsqueda
    $searchConditions = [];
    
    if (!empty($search)) {
        $searchConditions[] = "(p.name LIKE :search OR p.rfid_tag LIKE :search OR p.description LIKE :search)";
        $params['search'] = "%{$search}%";
    }
    
    if (!empty($species)) {
        $searchConditions[] = "p.species = :species";
        $params['species'] = $species;
    }
    
    if (!empty($searchConditions)) {
        if (!empty($whereClause)) {
            $whereClause .= " AND " . implode(' AND ', $searchConditions);
        } else {
            $whereClause = "WHERE " . implode(' AND ', $searchConditions);
        }
    }
    
    // Contar total de registros
    $countQuery = "SELECT COUNT(*) as total {$baseQuery} {$whereClause}";
    $countStmt = $db->prepare($countQuery);
    foreach ($params as $key => $value) {
        $countStmt->bindValue(":{$key}", $value);
    }
    $countStmt->execute();
    $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Obtener registros paginados
    $query = "SELECT {$selectFields} {$baseQuery} {$whereClause} ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
    $stmt = $db->prepare($query);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue(":{$key}", $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear respuesta
    $formattedPets = array_map(function($pet) {
        return [
            'id' => $pet['id'],
            'name' => $pet['name'],
            'species' => $pet['species'],
            'breed' => $pet['breed'],
            'age' => $pet['age'] ? (int)$pet['age'] : null,
            'rfid_tag' => $pet['rfid_tag'],
            'description' => $pet['description'],
            'image_url' => $pet['image_url'],
            'created_at' => $pet['created_at'],
            'updated_at' => $pet['updated_at'],
            'owner' => isset($pet['owner_name']) ? [
                'name' => $pet['owner_name'],
                'email' => $pet['owner_email'],
                'phone' => $pet['owner_phone']
            ] : null
        ];
    }, $pets);
    
    echo json_encode([
        'success' => true,
        'pets' => $formattedPets,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $limit,
            'total' => (int)$totalCount,
            'total_pages' => ceil($totalCount / $limit)
        ]
    ]);
}

function handleCreatePet($db, $user) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }
    
    $name = sanitizeInput($input['name'] ?? '');
    $species = sanitizeInput($input['species'] ?? '');
    $breed = sanitizeInput($input['breed'] ?? '');
    $age = isset($input['age']) ? (int)$input['age'] : null;
    $rfidTag = sanitizeInput($input['rfidTag'] ?? '');
    $description = sanitizeInput($input['description'] ?? '');
    $imageUrl = sanitizeInput($input['imageUrl'] ?? '');
    
    // Validaciones
    if (empty($name) || empty($species) || empty($rfidTag)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nombre, especie y tag RFID son requeridos']);
        return;
    }
    
    if (!validateRFIDTag($rfidTag)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Tag RFID debe tener entre 4 y 20 caracteres alfanuméricos']);
        return;
    }
    
    if ($age !== null && ($age < 0 || $age > 30)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'La edad debe estar entre 0 y 30 años']);
        return;
    }
    
    // Verificar que el tag RFID no esté en uso
    $checkQuery = "SELECT id FROM pets WHERE rfid_tag = :rfid_tag";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':rfid_tag', $rfidTag);
    $checkStmt->execute();
    
    if ($checkStmt->fetch()) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Este tag RFID ya está registrado']);
        return;
    }
    
    // Crear nueva mascota
    $petId = generateUniqueId('pet_');
    
    $insertQuery = "INSERT INTO pets (id, name, species, breed, age, rfid_tag, owner_id, description, image_url, created_at, updated_at) 
                    VALUES (:id, :name, :species, :breed, :age, :rfid_tag, :owner_id, :description, :image_url, NOW(), NOW())";
    
    $insertStmt = $db->prepare($insertQuery);
    $insertStmt->bindParam(':id', $petId);
    $insertStmt->bindParam(':name', $name);
    $insertStmt->bindParam(':species', $species);
    $insertStmt->bindParam(':breed', $breed);
    $insertStmt->bindParam(':age', $age, PDO::PARAM_INT);
    $insertStmt->bindParam(':rfid_tag', $rfidTag);
    $insertStmt->bindParam(':owner_id', $user['id']);
    $insertStmt->bindParam(':description', $description);
    $insertStmt->bindParam(':image_url', $imageUrl);
    
    if ($insertStmt->execute()) {
        // Log de la actividad
        logActivity('pet_created', "Mascota creada: {$name} ({$rfidTag})", $user['id']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Mascota registrada exitosamente',
            'pet_id' => $petId
        ]);
    } else {
        throw new Exception('Error al crear la mascota');
    }
}

function handleUpdatePet($db, $user) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $petId = sanitizeInput($input['id'] ?? '');
    $name = sanitizeInput($input['name'] ?? '');
    $species = sanitizeInput($input['species'] ?? '');
    $breed = sanitizeInput($input['breed'] ?? '');
    $age = isset($input['age']) ? (int)$input['age'] : null;
    $description = sanitizeInput($input['description'] ?? '');
    $imageUrl = sanitizeInput($input['imageUrl'] ?? '');
    
    if (empty($petId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID de mascota requerido']);
        return;
    }
    
    // Verificar que la mascota pertenece al usuario (o que sea admin)
    $checkQuery = $user['role'] === 'admin' 
        ? "SELECT name FROM pets WHERE id = :pet_id"
        : "SELECT name FROM pets WHERE id = :pet_id AND owner_id = :owner_id";
    
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':pet_id', $petId);
    if ($user['role'] !== 'admin') {
        $checkStmt->bindParam(':owner_id', $user['id']);
    }
    $checkStmt->execute();
    
    $pet = $checkStmt->fetch(PDO::FETCH_ASSOC);
    if (!$pet) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Mascota no encontrada o sin permisos']);
        return;
    }
    
    // Eliminar mascota
    $deleteQuery = "DELETE FROM pets WHERE id = :pet_id";
    $deleteStmt = $db->prepare($deleteQuery);
    $deleteStmt->bindParam(':pet_id', $petId);
    
    if ($deleteStmt->execute()) {
        logActivity('pet_deleted', "Mascota eliminada: {$pet['name']}", $user['id']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Mascota eliminada exitosamente'
        ]);
    } else {
        throw new Exception('Error al eliminar la mascota');
    }
}
?>