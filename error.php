<?php
$pageTitle = "Página No Encontrada";
$hideHeader = true;

// Determinar el tipo de error
$error_code = $_GET['404'] ?? '404';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - PatitasAlMar</title>
    <link rel="stylesheet" href="/patitasalmar-php/assets/css/style.css">
</head>
<body>
    <div class="min-h-screen flex items-center justify-center" style="background: linear-gradient(135deg, #f0f9ff, #fef3c7);">
        <div class="container">
            <div style="max-width: 500px; margin: 0 auto; text-align: center;">
                <div class="card">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🐕‍🦺</div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">¡Ups! Página no encontrada</h1>
                    <p class="text-gray-600 mb-6">
                        La página que buscas parece haberse perdido como una mascota sin collar RFID.
                    </p>
                    
                    <div class="space-y-4">
                        <a href="/patitasalmar-php/" class="btn btn-primary block">
                            🏠 Volver al inicio
                        </a>
                        
                        <div class="flex gap-4">
                            <a href="/patitasalmar-php/rfid-scanner" class="btn btn-secondary flex-1">
                                🔍 Escáner RFID
                            </a>
                            <a href="/patitasalmar-php/login" class="btn btn-secondary flex-1">
                                👤 Iniciar Sesión
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">💡 ¿Qué puedes hacer?</h3>
                        <ul class="text-sm text-blue-700 text-left space-y-1">
                            <li>• Verificar que la URL esté correcta</li>
                            <li>• Usar el escáner RFID para encontrar mascotas</li>
                            <li>• Registrar tu mascota en el sistema</li>
                            <li>• Contactar al administrador si el problema persiste</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // Redireccionar automáticamente después de 10 segundos
    setTimeout(() => {
        const countdown = document.createElement('p');
        countdown.className = 'text-sm text-gray-500 mt-4';
        countdown.textContent = 'Redirigiendo al inicio en 10 segundos...';
        document.querySelector('.card').appendChild(countdown);
        
        let seconds = 10;
        const interval = setInterval(() => {
            seconds--;
            countdown.textContent = `Redirigiendo al inicio en ${seconds} segundos...`;
            
            if (seconds <= 0) {
                window.location.href = '/patitasalmar-php/';
            }
        }, 1000);
        
        // Cancelar redirección si el usuario interactúa
        document.addEventListener('click', () => {
            clearInterval(interval);
            if (countdown.parentNode) {
                countdown.remove();
            }
        });
        
    }, 5000);
    </script>
</body>
</html>