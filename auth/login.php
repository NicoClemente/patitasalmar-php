<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: /patitasalmar-php/dashboard');
    exit();
}

$pageTitle = "Iniciar Sesión";
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
                <h2 class="text-3xl font-bold text-gray-800 mb-3">¡Bienvenido de vuelta!</h2>
                <p class="text-gray-600 text-lg">Accede a tu cuenta para gestionar tus mascotas</p>
            </div>
            
            <div class="card" style="box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: none;">
                <form id="loginForm">
                    <div class="form-group">
                        <label class="form-label">📧 Correo electrónico</label>
                        <input type="email" class="form-input" id="email" name="email" required 
                               placeholder="tu@email.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">🔒 Contraseña</label>
                        <input type="password" class="form-input" id="password" name="password" required
                               placeholder="Tu contraseña">
                    </div>

                    <div id="error-message" class="text-red-600 mb-4 p-4 bg-red-50 border border-red-200 rounded-lg" style="display: none; font-weight: 500;"></div>

                    <button type="submit" class="btn btn-primary w-full">
                        🚀 Iniciar Sesión
                    </button>

                    <div class="text-center mt-6">
                        <p class="text-gray-600 text-lg">
                            ¿No tienes cuenta? 
                            <a href="/patitasalmar-php/register" class="text-blue-600 hover:text-blue-500 font-semibold hover:underline">Regístrate aquí</a>
                        </p>
                    </div>
                </form>
                
                <!-- Demo credentials info -->
                <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos específicos para login */
.back-button {
    position: absolute;
    top: 1rem;
    left: 1rem;
    z-index: 10;
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 1rem;
    justify-content: center;
    text-align: center;
}

.logo-icon {
    font-size: 4rem;
    animation: float 3s ease-in-out infinite;
}

.logo-text h1 {
    margin: 0;
    color: #1f2937;
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.2;
}

.logo-text p {
    margin: 0;
    color: #6b7280;
    font-size: 1rem;
    font-weight: 500;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.w-full {
    width: 100%;
}

/* Centrado perfecto - usando estilos del CSS responsive */

/* Responsive específico */
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