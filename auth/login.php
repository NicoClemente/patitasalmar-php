<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: /dashboard');
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
                <h1 class="text-3xl font-bold text-gray-800 mb-2">🐾 Iniciar Sesión</h1>
                <p class="text-gray-600">Accede a tu cuenta de PetID</p>
            </div>
            
            <div class="card">
                <form id="loginForm">
                    <div class="form-group">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" class="form-input" id="email" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-input" id="password" required>
                    </div>

                    <div id="error-message" class="text-red-600 mb-4" style="display: none;"></div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Iniciar Sesión
                    </button>

                    <div class="text-center mt-4">
                        <a href="/register" class="text-blue-600">¿No tienes cuenta? Regístrate aquí</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/auth.js"></script>
<?php include '../includes/footer.php'; ?>