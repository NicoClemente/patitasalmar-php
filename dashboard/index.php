<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit();
}

$pageTitle = "Dashboard";
$user = $_SESSION['user'];

require_once '../config/database.php';
require_once '../includes/functions.php';

// Obtener estadísticas del usuario
$database = new Database();
$db = $database->getConnection();

$stats = [];

try {
    if ($user['role'] === 'admin') {
        // Estadísticas para administrador
        
        // Total de mascotas en el sistema
        $stmt = $db->prepare("SELECT COUNT(*) as total_pets FROM pets");
        $stmt->execute();
        $stats['totalPets'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total_pets'];
        
        // Total de usuarios
        $stmt = $db->prepare("SELECT COUNT(*) as total_users FROM users WHERE role = 'user'");
        $stmt->execute();
        $stats['totalUsers'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
        
        // Escaneos recientes (últimos 7 días)
        $stmt = $db->prepare("SELECT COUNT(*) as recent_scans FROM scans WHERE scanned_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $stmt->execute();
        $stats['recentScans'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['recent_scans'];
        
        // Mascotas registradas hoy
        $stmt = $db->prepare("SELECT COUNT(*) as today_pets FROM pets WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        $stats['todayPets'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['today_pets'];
        
        // Últimas mascotas registradas
        $stmt = $db->prepare("
            SELECT p.name, p.species, p.created_at, u.name as owner_name 
            FROM pets p 
            LEFT JOIN users u ON p.owner_id = u.id 
            ORDER BY p.created_at DESC 
            LIMIT 5
        ");
        $stmt->execute();
        $stats['recentPets'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Estadísticas por especie
        $stmt = $db->prepare("
            SELECT species, COUNT(*) as count 
            FROM pets 
            GROUP BY species 
            ORDER BY count DESC
        ");
        $stmt->execute();
        $stats['speciesStats'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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
        
        // Sus mascotas
        $stmt = $db->prepare("
            SELECT name, species, rfid_tag, created_at 
            FROM pets 
            WHERE owner_id = :owner_id 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        $stmt->bindParam(':owner_id', $user['id']);
        $stmt->execute();
        $stats['userPets'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Últimos escaneos de sus mascotas
        $stmt = $db->prepare("
            SELECT p.name, p.species, s.scanned_at, s.ip_address
            FROM scans s 
            JOIN pets p ON s.pet_id = p.id 
            WHERE p.owner_id = :owner_id 
            ORDER BY s.scanned_at DESC 
            LIMIT 5
        ");
        $stmt->bindParam(':owner_id', $user['id']);
        $stmt->execute();
        $stats['recentScansDetails'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
} catch (PDOException $e) {
    error_log("Database error in dashboard: " . $e->getMessage());
    // Continuar con stats vacías en caso de error
}

include '../includes/header.php';
?>

<div class="container py-8">
    <!-- Saludo y bienvenida -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            ¡Hola <?php echo htmlspecialchars($user['name']); ?>! 👋
        </h1>
        <p class="text-gray-600">
            Bienvenido a tu panel de control de PatitasAlMar
        </p>
        <p class="text-sm text-gray-500">
            Último acceso: <?php echo date('d/m/Y H:i'); ?>
        </p>
    </div>

    <!-- Estadísticas principales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stat-card text-center">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🐕</div>
            <h3 class="text-2xl font-bold text-gray-800" data-stat="pets">
                <?php echo $stats['totalPets']; ?>
            </h3>
            <p class="text-gray-600">
                <?php echo $user['role'] === 'admin' ? 'Mascotas totales' : 'Mis mascotas'; ?>
            </p>
        </div>

        <?php if ($user['role'] === 'admin'): ?>
        <div class="stat-card text-center">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">👥</div>
            <h3 class="text-2xl font-bold text-gray-800" data-stat="users">
                <?php echo $stats['totalUsers']; ?>
            </h3>
            <p class="text-gray-600">Usuarios registrados</p>
        </div>
        <?php endif; ?>

        <div class="stat-card text-center">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🔍</div>
            <h3 class="text-2xl font-bold text-gray-800" data-stat="scans">
                <?php echo $stats['recentScans']; ?>
            </h3>
            <p class="text-gray-600">Escaneos (7 días)</p>
        </div>

        <?php if ($user['role'] === 'admin' && isset($stats['todayPets'])): ?>
        <div class="stat-card text-center">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⭐</div>
            <h3 class="text-2xl font-bold text-gray-800">
                <?php echo $stats['todayPets']; ?>
            </h3>
            <p class="text-gray-600">Registros hoy</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Acciones rápidas -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">🚀 Acciones Rápidas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="/dashboard/pets/new" class="card hover:shadow-lg transition-all duration-300 text-center" style="text-decoration: none; border: 2px solid #dcfce7;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">➕</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Registrar Mascota</h3>
                <p class="text-gray-600">Añade una nueva mascota con su tag RFID</p>
            </a>

            <a href="/dashboard/pets" class="card hover:shadow-lg transition-all duration-300 text-center" style="text-decoration: none; border: 2px solid #dbeafe;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">📋</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Ver Mascotas</h3>
                <p class="text-gray-600">Gestiona la información de tus mascotas</p>
            </a>

            <a href="/rfid-scanner" class="card hover:shadow-lg transition-all duration-300 text-center" style="text-decoration: none; border: 2px solid #e0e7ff;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🏷️</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Escanear RFID</h3>
                <p class="text-gray-600">Busca mascotas por su tag RFID</p>
            </a>

            <?php if ($user['role'] === 'admin'): ?>
            <a href="/dashboard/users" class="card hover:shadow-lg transition-all duration-300 text-center" style="text-decoration: none; border: 2px solid #fed7aa;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">⚙️</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Gestionar Usuarios</h3>
                <p class="text-gray-600">Administra cuentas de usuarios</p>
            </a>

            <a href="/dashboard/infractions" class="card hover:shadow-lg transition-all duration-300 text-center" style="text-decoration: none; border: 2px solid #fecaca;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">📋</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Infracciones</h3>
                <p class="text-gray-600">Gestiona infracciones y multas</p>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Información reciente -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <?php if ($user['role'] === 'admin' && !empty($stats['recentPets'])): ?>
        <!-- Mascotas registradas recientemente -->
        <div class="card">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">📅 Últimas Mascotas Registradas</h3>
            <div class="space-y-3">
                <?php foreach ($stats['recentPets'] as $pet): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl"><?php echo getSpeciesEmoji($pet['species']); ?></span>
                        <div>
                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($pet['name']); ?></p>
                            <p class="text-sm text-gray-600">
                                Por: <?php echo htmlspecialchars($pet['owner_name'] ?? 'Usuario desconocido'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        <?php echo formatDate($pet['created_at'], 'd/m'); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($user['role'] !== 'admin' && !empty($stats['userPets'])): ?>
        <!-- Mascotas del usuario -->
        <div class="card">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">🐾 Tus Mascotas</h3>
            <div class="space-y-3">
                <?php foreach ($stats['userPets'] as $pet): ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl"><?php echo getSpeciesEmoji($pet['species']); ?></span>
                        <div>
                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($pet['name']); ?></p>
                            <p class="text-sm text-gray-600 font-mono"><?php echo htmlspecialchars($pet['rfid_tag']); ?></p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        <?php echo formatDate($pet['created_at'], 'd/m/Y'); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-4 text-center">
                <a href="/dashboard/pets" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                    Ver todas mis mascotas →
                </a>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($stats['recentScansDetails'])): ?>
        <!-- Últimos escaneos -->
        <div class="card">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">🔍 Últimos Escaneos</h3>
            <div class="space-y-3">
                <?php foreach ($stats['recentScansDetails'] as $scan): ?>
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl"><?php echo getSpeciesEmoji($scan['species']); ?></span>
                        <div>
                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($scan['name']); ?></p>
                            <p class="text-sm text-gray-600">
                                <?php echo formatDate($scan['scanned_at'], 'd/m/Y H:i'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        <?php 
                        $ip = $scan['ip_address'];
                        if ($ip !== 'unknown') {
                            // Mostrar solo los primeros segmentos de la IP por privacidad
                            $ipParts = explode('.', $ip);
                            if (count($ipParts) >= 2) {
                                echo $ipParts[0] . '.' . $ipParts[1] . '.xxx.xxx';
                            } else {
                                echo 'IP oculta';
                            }
                        } else {
                            echo 'IP desconocida';
                        }
                        ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($user['role'] === 'admin' && !empty($stats['speciesStats'])): ?>
        <!-- Estadísticas por especie -->
        <div class="card">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">📊 Mascotas por Especie</h3>
            <div class="space-y-3">
                <?php foreach ($stats['speciesStats'] as $species): ?>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl"><?php echo getSpeciesEmoji($species['species']); ?></span>
                        <span class="font-medium text-gray-800"><?php echo ucfirst($species['species']); ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-200 rounded-full h-2" style="width: <?php echo min(100, ($species['count'] / max(1, $stats['totalPets'])) * 100); ?>px;"></div>
                        <span class="font-bold text-blue-600"><?php echo $species['count']; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- Tips y consejos -->
    <div class="card mt-8" style="background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 2px solid #f59e0b;">
        <div class="text-center">
            <h3 class="text-xl font-semibold text-amber-800 mb-3">💡 Consejos Útiles</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                <div>
                    <h4 class="font-semibold text-amber-800 mb-2">🔒 Mantén actualizada tu información</h4>
                    <p class="text-sm text-amber-700">
                        Asegúrate de que tu teléfono y email estén actualizados para que puedan contactarte si encuentran tu mascota.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold text-amber-800 mb-2">📱 Comparte el código RFID</h4>
                    <p class="text-sm text-amber-700">
                        En redes sociales, incluye el código RFID de tu mascota para que cualquier persona pueda escanearlo.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold text-amber-800 mb-2">🏷️ Revisa el collar regularmente</h4>
                    <p class="text-sm text-amber-700">
                        Verifica que el tag RFID esté bien sujeto al collar y que no se haya dañado.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold text-amber-800 mb-2">🌊 Perfecto para la playa</h4>
                    <p class="text-sm text-amber-700">
                        Los tags RFID son resistentes al agua, ideales para mascotas que disfrutan de Las Grutas.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlaces rápidos adicionales -->
    <?php if ($stats['totalPets'] === 0): ?>
    <div class="card mt-8 text-center" style="border: 2px solid #10b981;">
        <div class="py-6">
            <div class="text-4xl mb-4">🎉</div>
            <h3 class="text-2xl font-bold text-green-800 mb-3">¡Bienvenido a PatitasAlMar!</h3>
            <p class="text-green-700 mb-6">
                Parece que aún no has registrado ninguna mascota. ¡Comencemos!
            </p>
            <div class="space-y-3">
                <a href="/dashboard/pets/new" class="btn btn-primary inline-block">
                    🐾 Registrar mi primera mascota
                </a>
                <br>
                <a href="/rfid-scanner" class="text-green-600 hover:text-green-500 text-sm">
                    ¿Encontraste una mascota? Escanea su RFID aquí
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
// Auto-refresh de estadísticas cada 5 minutos
setInterval(function() {
    // Solo refrescar si la página está visible
    if (!document.hidden) {
        fetch('/api/dashboard/stats', {
            headers: {
                'Authorization': 'Bearer ' + (localStorage.getItem('token') || '')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar contadores con animación suave
                updateStatCounter('pets', data.stats.totalPets);
                updateStatCounter('scans', data.stats.recentScans);
                <?php if ($user['role'] === 'admin'): ?>
                updateStatCounter('users', data.stats.totalUsers);
                <?php endif; ?>
            }
        })
        .catch(error => {
            console.log('Error actualizando estadísticas:', error);
        });
    }
}, 300000); // 5 minutos

function updateStatCounter(statName, newValue) {
    const element = document.querySelector(`[data-stat="${statName}"]`);
    if (element) {
        const currentValue = parseInt(element.textContent);
        if (currentValue !== newValue) {
            // Animación simple de contador
            element.style.transform = 'scale(1.1)';
            element.style.color = '#2563eb';
            setTimeout(() => {
                element.textContent = newValue;
                element.style.transform = 'scale(1)';
                element.style.color = '';
            }, 200);
        }
    }
}

// Mostrar notificación si hay mascotas escaneadas recientemente
<?php if (!empty($stats['recentScansDetails']) && count($stats['recentScansDetails']) > 0): ?>
const latestScan = <?php echo json_encode($stats['recentScansDetails'][0]); ?>;
const scanTime = new Date('<?php echo $stats['recentScansDetails'][0]['scanned_at']; ?>');
const now = new Date();
const minutesAgo = Math.floor((now - scanTime) / (1000 * 60));

if (minutesAgo < 30) {
    setTimeout(() => {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg z-50 animate-slide-in';
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="text-2xl"><?php echo getSpeciesEmoji($stats['recentScansDetails'][0]['species']); ?></span>
                <div>
                    <p class="font-semibold">¡Tu mascota fue escaneada!</p>
                    <p class="text-sm">${latestScan.name} - hace ${minutesAgo} minutos</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">×</button>
            </div>
        `;
        document.body.appendChild(notification);
        
        // Auto-remove después de 10 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 10000);
    }, 2000);
}
<?php endif; ?>
</script>

<style>
@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}
</style>

<?php 
$additionalScripts = ['/assets/js/main.js'];
include '../includes/footer.php'; 
?>