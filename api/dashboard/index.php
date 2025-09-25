<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit();
}

$pageTitle = "Dashboard";
include '../includes/header.php';
$user = $_SESSION['user'];

// Obtener estadísticas
require_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

if ($user['role'] === 'admin') {
    $query = "SELECT COUNT(*) as total_pets FROM pets";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $totalPets = $stmt->fetch(PDO::FETCH_ASSOC)['total_pets'];
    
    $query = "SELECT COUNT(*) as total_users FROM users WHERE role = 'user'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
    
    $query = "SELECT COUNT(*) as recent_scans FROM scans WHERE scanned_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $recentScans = $stmt->fetch(PDO::FETCH_ASSOC)['recent_scans'];
} else {
    $query = "SELECT COUNT(*) as total_pets FROM pets WHERE owner_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->execute();
    $totalPets = $stmt->fetch(PDO::FETCH_ASSOC)['total_pets'];
    
    $totalUsers = 0;
    
    $query = "SELECT COUNT(*) as recent_scans FROM scans s 
              JOIN pets p ON s.pet_id = p.id 
              WHERE p.owner_id = :user_id AND s.scanned_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->execute();
    $recentScans = $stmt->fetch(PDO::FETCH_ASSOC)['recent_scans'];
}
?>

<div class="container py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            ¡Hola <?php echo htmlspecialchars($user['name']); ?>! 👋
        </h1>
        <p class="text-gray-600">Bienvenido a tu panel de control de PetID</p>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card text-center">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🐕</div>
            <h3 class="text-2xl font-bold text-gray-800"><?php echo $totalPets; ?></h3>
            <p class="text-gray-600">
                <?php echo $user['role'] === 'admin' ? 'Mascotas totales' : 'Mis mascotas'; ?>
            </p>
        </div>

        <?php if ($user['role'] === 'admin'): ?>
        <div class="card text-center">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">👥</div>
            <h3 class="text-2xl font-bold text-gray-800"><?php echo $totalUsers; ?></h3>
            <p class="text-gray-600">Usuarios registrados</p>
        </div>
        <?php endif; ?>

        <div class="card text-center">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🔍</div>
            <h3 class="text-2xl font-bold text-gray-800"><?php echo $recentScans; ?></h3>
            <p class="text-gray-600">Escaneos recientes</p>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="/dashboard/pets/new" class="card text-center" style="text-decoration: none; transition: transform 0.2s; border: 2px solid #dcfce7;">
            <div style="font-size: 2.5rem; margin-bottom: 1rem;">➕</div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Registrar Mascota</h3>
            <p class="text-gray-600">Añade una nueva mascota con su tag RFID</p>
        </a>

        <a href="/dashboard/pets" class="card text-center" style="text-decoration: none; transition: transform 0.2s; border: 2px solid #dbeafe;">
            <div style="font-size: 2.5rem; margin-bottom: 1rem;">📋</div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Ver Mascotas</h3>
            <p class="text-gray-600">Gestiona la información de tus mascotas</p>
        </a>

        <a href="/rfid-scanner" class="card text-center" style="text-decoration: none; transition: transform 0.2s; border: 2px solid #e0e7ff;">
            <div style="font-size: 2.5rem; margin-bottom: 1rem;">🏷️</div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Escanear RFID</h3>
            <p class="text-gray-600">Busca mascotas por su tag RFID</p>
        </a>

        <?php if ($user['role'] === 'admin'): ?>
        <a href="/dashboard/users" class="card text-center" style="text-decoration: none; transition: transform 0.2s; border: 2px solid #fed7aa;">
            <div style="font-size: 2.5rem; margin-bottom: 1rem;">⚙️</div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Gestionar Usuarios</h3>
            <p class="text-gray-600">Administra cuentas de usuarios</p>
        </a>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
