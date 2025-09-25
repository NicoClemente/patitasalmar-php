<?php
// Iniciar sesión solo si no existe una activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user']);
$user = $isLoggedIn ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?>PatitasAlMar</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php if ($isLoggedIn && !isset($hideHeader)): ?>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="/dashboard" class="logo">
                    <span class="logo-icon">🐾</span>
                    <div class="logo-text">
                        <div class="logo-title">PatitasAlMar</div>
                        <div class="logo-subtitle">Las Grutas</div>
                    </div>
                </a>
                
                <nav class="nav">
                    <a href="/dashboard" class="nav-link">Dashboard</a>
                    <a href="/dashboard/pets" class="nav-link">Mascotas</a>
                    <a href="/rfid-scanner" class="nav-link">Escáner</a>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="/dashboard/users" class="nav-link">Usuarios</a>
                    <?php endif; ?>
                </nav>
                
                <div class="user-menu">
                    <div class="user-info">
                        <span><?php echo htmlspecialchars($user['name']); ?></span>
                        <span class="user-role"><?php echo $user['role'] === 'admin' ? 'Administrador' : 'Usuario'; ?></span>
                    </div>
                    <a href="/logout" class="btn-logout">Salir</a>
                </div>

                <!-- Botón menú móvil -->
                <button class="mobile-menu-btn" id="mobile-menu-btn">
                    ☰
                </button>
            </div>
            
            <!-- Navegación móvil -->
            <nav class="mobile-nav hidden" id="mobile-nav">
                <div class="container">
                    <a href="/dashboard" class="mobile-nav-link">Dashboard</a>
                    <a href="/dashboard/pets" class="mobile-nav-link">Mascotas</a>
                    <a href="/rfid-scanner" class="mobile-nav-link">Escáner</a>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="/dashboard/users" class="mobile-nav-link">Usuarios</a>
                    <?php endif; ?>
                    <div class="mobile-nav-link" style="border-top: 1px solid #e5e7eb; padding-top: 0.75rem; margin-top: 0.75rem;">
                        <strong><?php echo htmlspecialchars($user['name']); ?></strong> (<?php echo $user['role'] === 'admin' ? 'Admin' : 'Usuario'; ?>)
                    </div>
                    <a href="/logout" class="mobile-nav-link text-red-600">Cerrar Sesión</a>
                </div>
            </nav>
        </div>
    </header>
    <?php endif; ?>
    
    <main class="main-content">