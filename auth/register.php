
<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: /patitasalmar-php/dashboard');
    exit();
}

$pageTitle = "Crear Cuenta";
$hideHeader = true;
include '../includes/header.php';
?>

<div class="paw-pattern auth-container" style="position: relative;">    
    
    <div class="container">
        <div class="auth-form">
            <div class="text-center mb-8">
                <a href="/patitasalmar-php/" class="logo mb-6">
                    <div class="logo-container">
                        <div class="logo-icon">🐾</div>
                        <div class="logo-text">
                            <h1>PatitasAlMar</h1>
                            <p>Las Grutas, Río Negro</p>
                        </div>
                    </div>
                </a>
                <h2 class="text-3xl font-bold text-gray-800 mb-3">¡Únete a nuestra comunidad!</h2>
                <p class="text-gray-600 text-lg">Registra tus mascotas y manténlas seguras</p>
            </div>
            
            <div class="card" style="box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: none;">
                <form id="registerForm">
                    <div class="form-group">
                        <label class="form-label">👤 Nombre completo</label>
                        <input type="text" class="form-input" id="name" name="name" required maxlength="100" 
                               placeholder="Tu nombre completo">
                    </div>

                    <div class="form-group">
                        <label class="form-label">📧 Correo electrónico</label>
                        <input type="email" class="form-input" id="email" name="email" required 
                               placeholder="tu@email.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">🔒 Contraseña</label>
                        <input type="password" class="form-input" id="password" name="password" required minlength="6"
                               placeholder="Mínimo 6 caracteres">
                        <small class="form-help">Mínimo 6 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">🔐 Confirmar contraseña</label>
                        <input type="password" class="form-input" id="confirmPassword" name="confirmPassword" required
                               placeholder="Repite tu contraseña">
                    </div>

                    <div id="error-message" class="text-red-600 mb-4 p-4 bg-red-50 border border-red-200 rounded-lg" style="display: none; font-weight: 500;"></div>

                    <button type="submit" class="btn btn-primary w-full">
                        🎉 Crear Cuenta
                    </button>

                    <div class="text-center mt-6">
                        <p class="text-gray-600 text-lg">
                            ¿Ya tienes cuenta? 
                            <a href="/patitasalmar-php/login" class="text-blue-600 hover:text-blue-500 font-semibold hover:underline">Inicia sesión aquí</a>
                        </p>
                    </div>
                </form>
                
                <!-- Información adicional -->
                <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                    <!-- <div class="flex items-start gap-3">
                        <div class="text-green-600 text-xl">✨</div>
                        <div>
                            <p class="font-semibold text-green-800 mb-2">¿Qué obtienes al registrarte?</p>
                            <ul class="text-sm text-green-700 space-y-1">
                                <li>• Registra hasta 5 mascotas</li>
                                <li>• Escaneo NFC automático</li>
                                <li>• Notificaciones de seguridad</li>
                                <li>• Acceso al dashboard personal</li>
                            </ul>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos específicos para registro */
.back-button {
    position: absolute;
    top: 1rem;
    left: 1rem;
    z-index: 10;
}

/* Contenedor general del logo */
.logo {
    display: flex;
    justify-content: center; /* Centra el logo en su contenedor */
    text-decoration: none;   /* Evita subrayado en el enlace */
}

/* Contenedor interno del icono + texto */
.logo-container {
    display: flex;
    flex-direction: column;  /* Icono arriba, texto abajo */
    align-items: center;     /* Centra horizontal */
    justify-content: center; /* Centra vertical */
    text-align: center;      /* Centra el texto */
    gap: 0.5rem;
}

/* Icono del logo */
.logo-icon {
    font-size: 4rem;
    animation: float 3s ease-in-out infinite;
}

/* Título del logo */
.logo-text h1 {
    margin: 0;
    color: #1f2937;
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.2;
}

/* Subtítulo del logo */
.logo-text p {
    margin: 0;
    color: #6b7280;
    font-size: 1rem;
    font-weight: 500;
}

/* Animación flotante para el icono */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Utility */
.w-full {
    width: 100%;
}

/* Responsive */
@media (max-width: 767px) {
    .back-button {
        position: relative;
        top: auto;
        left: auto;
        margin-bottom: 1rem;
        text-align: center;
    }
    
    .logo-container {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .logo-text h1 {
        font-size: 2rem;
    }
    
    .logo-icon {
        font-size: 3rem;
    }
    
    .auth-container {
        padding-top: 5rem;
    }
}

@media (max-width: 479px) {
    .logo-text h1 {
        font-size: 1.75rem;
    }
    
    .logo-icon {
        font-size: 2.5rem;
    }
    
    .text-3xl {
        font-size: 1.5rem;
    }
    
    .auth-container {
        padding: 0.5rem;
        padding-top: 5rem;
    }
}
</style>

<script src="/patitasalmar-php/assets/js/auth.js"></script>
<?php include '../includes/footer.php'; ?>