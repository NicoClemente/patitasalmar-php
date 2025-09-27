<?php
$pageTitle = "Guía para iPhone/iPad - Escaneo NFC";
include 'includes/header.php';
?>

<div class="container py-8">
    <div style="max-width: 800px; margin: 0 auto;">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="text-6xl mb-4">📱</div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Guía para iPhone/iPad</h1>
            <p class="text-gray-600 text-lg">
                Cómo escanear etiquetas NFC y registrar mascotas en iOS
            </p>
        </div>

        <!-- Limitación de iOS -->
        <div class="card mb-6" style="border: 2px solid #f59e0b; background: #fffbeb;">
            <div class="text-center">
                <div class="text-4xl mb-4">⚠️</div>
                <h2 class="text-2xl font-semibold text-orange-800 mb-2">Limitación de iOS</h2>
                <p class="text-orange-700 mb-4">
                    Apple no permite que las aplicaciones web accedan directamente al hardware NFC en iPhone/iPad. 
                    Solo funciona para Apple Pay y algunas apps específicas.
                </p>
                <div class="bg-orange-100 border border-orange-300 rounded-lg p-3">
                    <p class="text-orange-800 text-sm">
                        <strong>¿Por qué?</strong> Apple restringe el acceso a NFC por seguridad y para mantener 
                        el control sobre el ecosistema de pagos.
                    </p>
                </div>
            </div>
        </div>

        <!-- Alternativas para iOS -->
        <div class="card mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">🔄 Alternativas para iPhone/iPad</h2>
            
            <div class="space-y-6">
                <!-- Opción 1: Entrada Manual -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <div class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">1</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">✍️ Entrada Manual (Recomendado)</h3>
                            <p class="text-gray-600 mb-3">
                                La forma más fácil y rápida de registrar tu mascota.
                            </p>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <h4 class="font-semibold text-green-800 mb-2">Cómo hacerlo:</h4>
                                <ol class="text-green-700 text-sm space-y-1">
                                    <li>1. Ve al formulario de registro de mascotas</li>
                                    <li>2. Escribe un código único (ej: "MASCOTA001")</li>
                                    <li>3. Completa el resto de la información</li>
                                    <li>4. ¡Listo! Tu mascota está registrada</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opción 2: Códigos QR -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">2</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">📷 Códigos QR</h3>
                            <p class="text-gray-600 mb-3">
                                Si tienes una etiqueta con código QR, puedes escanearla con la cámara de iOS.
                            </p>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <h4 class="font-semibold text-blue-800 mb-2">Cómo escanear QR:</h4>
                                <ol class="text-blue-700 text-sm space-y-1">
                                    <li>1. Abre la app "Cámara" de iOS</li>
                                    <li>2. Apunta a un código QR con el código de la mascota</li>
                                    <li>3. Toca la notificación que aparece</li>
                                    <li>4. Copia el código y pégalo en el formulario</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opción 3: Apps de Terceros -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <div class="bg-purple-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">3</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">📱 Apps de Terceros</h3>
                            <p class="text-gray-600 mb-3">
                                Algunas apps pueden leer etiquetas NFC en iOS, pero con limitaciones.
                            </p>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                                <h4 class="font-semibold text-purple-800 mb-2">Apps recomendadas:</h4>
                                <ul class="text-purple-700 text-sm space-y-1">
                                    <li>• <strong>NFC Tools:</strong> Lee etiquetas NFC básicas</li>
                                    <li>• <strong>NFC TagInfo:</strong> Información detallada de etiquetas</li>
                                    <li>• <strong>TagWriter:</strong> Escribe y lee etiquetas NFC</li>
                                </ul>
                                <p class="text-purple-600 text-xs mt-2">
                                    Nota: Estas apps pueden leer el código pero no integrarse directamente con PatitasAlMar
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opción 4: Dispositivo Android -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <div class="bg-orange-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">4</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">🤝 Usar un Dispositivo Android</h3>
                            <p class="text-gray-600 mb-3">
                                Si tienes acceso a un teléfono Android, puedes usarlo para escanear.
                            </p>
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                                <h4 class="font-semibold text-orange-800 mb-2">Pasos:</h4>
                                <ol class="text-orange-700 text-sm space-y-1">
                                    <li>1. Abre Chrome en el dispositivo Android</li>
                                    <li>2. Ve a PatitasAlMar</li>
                                    <li>3. Usa el escáner NFC integrado</li>
                                    <li>4. Copia el código escaneado</li>
                                    <li>5. Regresa a tu iPhone para completar el registro</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Etiquetas Recomendadas para iOS -->
        <div class="card mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">🏷️ Etiquetas Recomendadas para iOS</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">📱 Etiquetas con Código QR</h3>
                    <p class="text-gray-600 text-sm mb-2">
                        Etiquetas que incluyen tanto NFC como código QR visible.
                    </p>
                    <ul class="text-gray-700 text-xs space-y-1">
                        <li>• Escaneable con cámara de iOS</li>
                        <li>• Código visible impreso</li>
                        <li>• Funciona en todos los dispositivos</li>
                        <li>• Precio: $8-20 USD</li>
                    </ul>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">✍️ Etiquetas con Código Impreso</h3>
                    <p class="text-gray-600 text-sm mb-2">
                        Etiquetas con el código claramente visible para entrada manual.
                    </p>
                    <ul class="text-gray-700 text-xs space-y-1">
                        <li>• Código grande y legible</li>
                        <li>• Resistente al agua</li>
                        <li>• Fácil de leer</li>
                        <li>• Precio: $5-15 USD</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Demo de Entrada Manual -->
        <div class="card mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">🎯 Demo: Entrada Manual</h2>
            
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-3">Prueba el sistema de entrada manual:</h3>
                
                <div class="space-y-3">
                    <div class="flex gap-2">
                        <input type="text" id="demoInput" class="form-input flex-1" 
                               placeholder="Escribe un código de prueba (ej: MASCOTA001)" 
                               maxlength="20">
                        <button id="demoButton" class="btn btn-primary">Probar</button>
                    </div>
                    
                    <div id="demoResult" class="hidden">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                            <p class="text-green-700 text-sm">
                                ✅ Código válido: <strong id="demoCode"></strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="text-center space-y-4">
            <a href="/dashboard/pets/new" class="btn btn-primary text-lg px-8">
                🐾 Registrar Mascota (Entrada Manual)
            </a>
            
            <div class="flex gap-4 justify-center">
                <a href="/nfc-tags-info" class="btn btn-secondary">
                    🏷️ Ver Etiquetas Recomendadas
                </a>
                <a href="/rfid-scanner" class="btn btn-secondary">
                    🔍 Probar Escáner
                </a>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>
                ¿Necesitas ayuda? El sistema funciona perfectamente con entrada manual en iOS. 
                No necesitas NFC para registrar tu mascota.
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const demoInput = document.getElementById('demoInput');
    const demoButton = document.getElementById('demoButton');
    const demoResult = document.getElementById('demoResult');
    const demoCode = document.getElementById('demoCode');
    
    if (demoButton) {
        demoButton.addEventListener('click', function() {
            const code = demoInput.value.trim().toUpperCase();
            
            if (!code) {
                alert('Por favor escribe un código de prueba');
                return;
            }
            
            if (!/^[A-Z0-9]{3,20}$/.test(code)) {
                alert('El código debe tener entre 3 y 20 caracteres (solo letras y números)');
                return;
            }
            
            demoCode.textContent = code;
            demoResult.classList.remove('hidden');
            
            // Simular validación exitosa
            setTimeout(() => {
                alert('¡Código válido! Así es como funciona la entrada manual en iOS.');
            }, 500);
        });
    }
    
    // Auto-mayúsculas en el input
    if (demoInput) {
        demoInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
