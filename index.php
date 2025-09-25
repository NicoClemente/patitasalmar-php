<?php
session_start();
$pageTitle = "Inicio";
$hideHeader = true;
include 'includes/header.php';
?>

<div class="hero-section paw-pattern" style="min-height: 100vh; display: flex; align-items: center;">
    <div class="container">
        <div class="text-center mb-8">
            <div class="animate-float mb-6">
                <span style="font-size: 4rem;">🐾</span>
            </div>
            <h1 class="text-4xl font-bold mb-6" style="background: linear-gradient(135deg, #2563eb, #9333ea); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                PatitasAlMar
            </h1>
            <p class="text-xl text-gray-600 mb-4">
                Sistema inteligente de identificación y gestión de mascotas con tecnología RFID
            </p>
            <p class="text-blue-600 font-semibold">Las Grutas, Río Negro</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="card text-center">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🏠</div>
                <h3 class="text-xl font-bold mb-2">Registro Fácil</h3>
                <p class="text-gray-600">Registra a tus mascotas con información detallada, fotos y tags RFID únicos</p>
            </div>
            <div class="card text-center">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🌊</div>
                <h3 class="text-xl font-bold mb-2">Tecnología RFID</h3>
                <p class="text-gray-600">Identifica mascotas perdidas al instante con chips RFID cerca del mar</p>
            </div>
            <div class="card text-center">
                <div style="font-size: 3rem; margin-bottom: 1rem;">👨‍⚕️</div>
                <h3 class="text-xl font-bold mb-2">Gestión Completa</h3>
                <p class="text-gray-600">Panel administrativo para municipio, veterinarios y dueños</p>
            </div>
        </div>

        <div class="text-center">
            <div class="flex justify-center gap-4 mb-6">
                <a href="/login" class="btn btn-primary">Iniciar Sesión</a>
                <a href="/register" class="btn btn-secondary">Registrarse</a>
            </div>
            <div>
                <a href="/rfid-scanner" class="text-blue-600">
                    ¿Encontraste una mascota? Escanea su RFID aquí
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>