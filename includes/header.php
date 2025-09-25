<?php
session_start();
$isLoggedIn = isset($_SESSION['user']);
$user = $isLoggedIn ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>PatitasAlMar</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/components.css">
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
                    <a href="/dashboard">Dashboard</a>
                    <a href="/dashboard/pets">Mascotas</a>
                    <a href="/dashboard/infractions">Infracciones</a>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="/dashboard/users">Usuarios</a>
                    <?php endif; ?>
                    <a href="/rfid-scanner">Escáner</a>
                </nav>
                
                <div class="user-menu">
                    <span class="user-info"><?php echo htmlspecialchars($user['name']); ?></span>
                    <a href="/logout" class="btn-logout">Salir</a>
                </div>
            </div>
        </div>
    </header>
    <?php endif; ?>
    
    <main class="main-content">