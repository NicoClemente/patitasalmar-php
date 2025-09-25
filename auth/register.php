<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: /dashboard');
    exit();
}

$pageTitle = "Registrarse";
$hideHeader = true;
include '../includes/header.php';
?>

<div class="paw-pattern" style="min-height: 100vh; display: flex; align-items: center;">
    <div class="container">
        <div style="max-width: 400px; margin: 0 auto;">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">🐾 Crear Cuenta</h1>
                <p class="text-gray-600">Únete a la comunidad PetID</p>
            </div>
            
            <div class="card">
                <form id="registerForm">
                    <div class="form-group">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" class="form-input" id="name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" class="form-input" id="email" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-input" id="password" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" class="form-input" id="confirmPassword" required>
                    </div>

                    <div id="error-message" class="text-red-600 mb-4" style="display: none;"></div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Crear Cuenta
                    </button>

                    <div class="text-center mt-4">
                        <a href="/login" class="text-blue-600">¿Ya tienes cuenta? Inicia sesión aquí</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/auth.js"></script>
<?php include '../includes/footer.php'; ?>