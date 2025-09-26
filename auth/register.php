
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

<div class="paw-pattern" style="min-height: 100vh; display: flex; align-items: center;">
    <div class="container">
        <div style="max-width: 400px; margin: 0 auto;">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">🐾 Crear Cuenta</h1>
                <p class="text-gray-600">Únete a PatitasAlMar</p>
            </div>
            
            <div class="card">
                <form id="registerForm">
                    <div class="form-group">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" class="form-input" id="name" name="name" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" class="form-input" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-input" id="password" name="password" required minlength="6">
                        <small class="form-help">Mínimo 6 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-input" id="confirmPassword" name="confirmPassword" required>
                    </div>

                    <div id="error-message" class="text-red-600 mb-4" style="display: none;"></div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Crear Cuenta
                    </button>

                    <div class="text-center mt-4">
                        <a href="/patitasalmar-php/login" class="text-blue-600">¿Ya tienes cuenta? Inicia sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="/patitasalmar-php/assets/js/auth.js"></script>
<?php include '../includes/footer.php'; ?>