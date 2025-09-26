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

<div class="paw-pattern" style="min-height: 100vh; display: flex; align-items: center;">
    <div class="container">
        <div style="max-width: 400px; margin: 0 auto;">
            <div class="text-center mb-8">
                <a href="/patitasalmar-php/" class="logo mb-6" style="text-decoration: none; display: inline-block;">
                    <span class="logo-icon" style="font-size: 3rem;">🐾</span>
                    <div class="logo-text">
                        <h1 style="margin: 0; color: #1f2937;">PatitasAlMar</h1>
                        <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">Las Grutas</p>
                    </div>
                </a>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Iniciar Sesión</h2>
                <p class="text-gray-600">Accede a tu cuenta de PatitasAlMar</p>
            </div>
            
            <div class="card">
                <form id="loginForm">
                    <div class="form-group">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" class="form-input" id="email" name="email" required 
                               placeholder="tu@email.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-input" id="password" name="password" required
                               placeholder="Tu contraseña">
                    </div>

                    <div id="error-message" class="text-red-600 mb-4 p-3 bg-red-50 border border-red-200 rounded" style="display: none;"></div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Iniciar Sesión
                    </button>

                    <div class="text-center mt-4">
                        <p class="text-gray-600">
                            ¿No tienes cuenta? 
                            <a href="/patitasalmar-php/register" class="text-blue-600 hover:text-blue-500 font-medium">Regístrate aquí</a>
                        </p>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="/patitasalmar-php/" class="text-gray-500 hover:text-gray-400 text-sm">
                            ← Volver al inicio
                        </a>
                    </div>
                </form>
                
                <!-- Demo credentials info -->
                <div class="mt-6 p-3 bg-blue-50 border border-blue-200 rounded text-sm">
                    <p class="font-semibold text-blue-800 mb-2">💡 Para probar:</p>
                    <p class="text-blue-700">
                        <strong>Admin:</strong> admin@patitasalmar.com / password<br>
                        <strong>Usuario:</strong> maria@ejemplo.com / password
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/patitasalmar-php/assets/js/auth.js"></script>
<?php include '../includes/footer.php'; ?>