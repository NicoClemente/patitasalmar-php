<?php
// Iniciar sesión solo si no existe una activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user']);
$user = $isLoggedIn ? $_SESSION['user'] : null;

// Determinar la base URL según la ubicación del archivo
$base_url = '/patitasalmar-php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?>PatitasAlMar</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Meta tags adicionales -->
    <meta name="description" content="Sistema de identificación de mascotas con tecnología RFID - Las Grutas, Río Negro">
    <meta name="keywords" content="mascotas, RFID, identificación, Las Grutas, Río Negro, perros, gatos">
    <meta name="author" content="PatitasAlMar">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?>PatitasAlMar">
    <meta property="og:description" content="Sistema de identificación de mascotas con tecnología RFID">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
</head>
<body>
    <?php if ($isLoggedIn && !isset($hideHeader)): ?>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="<?php echo $base_url; ?>/dashboard" class="logo">
                    <span class="logo-icon">🐾</span>
                    <div class="logo-text">
                        <div class="logo-title">PatitasAlMar</div>
                        <div class="logo-subtitle">Las Grutas</div>
                    </div>
                </a>
                
                <nav class="nav">
                    <a href="<?php echo $base_url; ?>/dashboard" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false && strpos($_SERVER['REQUEST_URI'], '/dashboard/') === false ? 'active' : ''; ?>">Dashboard</a>
                    <a href="<?php echo $base_url; ?>/dashboard/pets" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/dashboard/pets') !== false ? 'active' : ''; ?>">Mascotas</a>
                    <a href="<?php echo $base_url; ?>/rfid-scanner" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/rfid-scanner') !== false ? 'active' : ''; ?>">Escáner</a>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="<?php echo $base_url; ?>/dashboard/users" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/dashboard/users') !== false ? 'active' : ''; ?>">Usuarios</a>
                    <?php endif; ?>
                </nav>
                
                <div class="user-menu">
                    <div class="user-info">
                        <span class="user-name"><?php echo htmlspecialchars($user['name']); ?></span>
                        <span class="user-role"><?php echo $user['role'] === 'admin' ? 'Administrador' : 'Usuario'; ?></span>
                    </div>
                    <a href="<?php echo $base_url; ?>/logout" class="btn-logout">Salir</a>
                </div>

                <!-- Botón menú móvil -->
                <button class="mobile-menu-btn" id="mobile-menu-btn" style="display: none;">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
            
            <!-- Navegación móvil -->
            <nav class="mobile-nav hidden" id="mobile-nav">
                <div class="mobile-nav-content">
                    <a href="<?php echo $base_url; ?>/dashboard" class="mobile-nav-link">📊 Dashboard</a>
                    <a href="<?php echo $base_url; ?>/dashboard/pets" class="mobile-nav-link">🐾 Mascotas</a>
                    <a href="<?php echo $base_url; ?>/rfid-scanner" class="mobile-nav-link">🔍 Escáner</a>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="<?php echo $base_url; ?>/dashboard/users" class="mobile-nav-link">👥 Usuarios</a>
                    <?php endif; ?>
                    <div class="mobile-nav-divider"></div>
                    <div class="mobile-nav-user">
                        <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                        <span>(<?php echo $user['role'] === 'admin' ? 'Admin' : 'Usuario'; ?>)</span>
                    </div>
                    <a href="<?php echo $base_url; ?>/logout" class="mobile-nav-link text-red-600">🚪 Cerrar Sesión</a>
                </div>
            </nav>
        </div>
    </header>
    
    <script>
    // Manejo del menú móvil
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileNav = document.getElementById('mobile-nav');
        
        if (mobileMenuBtn && mobileNav) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileNav.classList.toggle('hidden');
                this.classList.toggle('active');
            });
            
            // Cerrar menú al hacer click fuera
            document.addEventListener('click', function(e) {
                if (!mobileMenuBtn.contains(e.target) && !mobileNav.contains(e.target)) {
                    mobileNav.classList.add('hidden');
                    mobileMenuBtn.classList.remove('active');
                }
            });
            
            // Cerrar menú al hacer click en un enlace
            const mobileNavLinks = mobileNav.querySelectorAll('.mobile-nav-link');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileNav.classList.add('hidden');
                    mobileMenuBtn.classList.remove('active');
                });
            });
        }
        
        // Mostrar botón móvil en pantallas pequeñas
        function toggleMobileMenu() {
            if (window.innerWidth <= 768) {
                if (mobileMenuBtn) mobileMenuBtn.style.display = 'block';
                document.querySelector('.nav').style.display = 'none';
            } else {
                if (mobileMenuBtn) mobileMenuBtn.style.display = 'none';
                document.querySelector('.nav').style.display = 'flex';
                mobileNav.classList.add('hidden');
            }
        }
        
        toggleMobileMenu();
        window.addEventListener('resize', toggleMobileMenu);
    });
    </script>
    <?php endif; ?>
    
    <main class="main-content">
    
    <style>
    /* Estilos para el menú móvil */
    .mobile-menu-btn {
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        gap: 3px;
        padding: 8px;
    }
    
    .mobile-menu-btn span {
        width: 20px;
        height: 2px;
        background: var(--gray-600);
        transition: all 0.3s ease;
        border-radius: 1px;
    }
    
    .mobile-menu-btn.active span:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }
    
    .mobile-menu-btn.active span:nth-child(2) {
        opacity: 0;
    }
    
    .mobile-menu-btn.active span:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -6px);
    }
    
    .mobile-nav {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid var(--gray-200);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 50;
    }
    
    .mobile-nav-content {
        padding: 1rem;
    }
    
    .mobile-nav-link {
        display: block;
        padding: 0.75rem 0;
        color: var(--gray-700);
        text-decoration: none;
        font-weight: 500;
        border-bottom: 1px solid var(--gray-100);
        transition: color 0.2s ease;
    }
    
    .mobile-nav-link:hover {
        color: var(--primary);
    }
    
    .mobile-nav-link:last-child {
        border-bottom: none;
    }
    
    .mobile-nav-divider {
        height: 1px;
        background: var(--gray-200);
        margin: 0.5rem 0;
    }
    
    .mobile-nav-user {
        padding: 0.75rem 0;
        font-size: 0.875rem;
        color: var(--gray-600);
    }
    
    .mobile-nav-user strong {
        display: block;
        color: var(--gray-800);
    }
    
    /* Estilos para el header */
    .user-menu {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .user-info {
        text-align: right;
    }
    
    .user-name {
        display: block;
        font-weight: 600;
        color: var(--gray-800);
        font-size: 0.875rem;
    }
    
    .user-role {
        font-size: 0.75rem;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-logout {
        background: var(--gray-100);
        color: var(--gray-700);
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        border: 1px solid var(--gray-200);
    }
    
    .btn-logout:hover {
        background: var(--gray-200);
        color: var(--gray-900);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .user-menu {
            display: none;
        }
        
        .header-content {
            gap: 1rem;
        }
        
        .user-info {
            display: none;
        }
    }
    
    @media (min-width: 769px) {
        .mobile-menu-btn {
            display: none !important;
        }
        
        .mobile-nav {
            display: none !important;
        }
    }
    </style>