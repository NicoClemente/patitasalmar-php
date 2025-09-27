<?php
$pageTitle = "Guía de Compatibilidad - NFC y Dispositivos";
include 'includes/header.php';
?>

<div class="container py-8">
    <div style="max-width: 1000px; margin: 0 auto;">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">📱 Guía de Compatibilidad</h1>
            <p class="text-gray-600 text-lg">
                Comparación de funcionalidades NFC entre diferentes dispositivos y navegadores
            </p>
        </div>

        <!-- Tabla de Compatibilidad -->
        <div class="card mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">🔍 Tabla de Compatibilidad</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left">Dispositivo/Navegador</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Web NFC API</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Cámara QR</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Entrada Manual</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Recomendación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Android Chrome -->
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-medium">Android + Chrome</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600">NFC + QR</span>
                            </td>
                        </tr>
                        
                        <!-- Android Firefox -->
                        <tr class="bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 font-medium">Android + Firefox</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-red-600 font-bold">❌ No</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-blue-600">QR + Manual</span>
                            </td>
                        </tr>
                        
                        <!-- iOS Safari -->
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-medium">iOS + Safari</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-red-600 font-bold">❌ No</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-orange-600">Manual + QR</span>
                            </td>
                        </tr>
                        
                        <!-- iOS Chrome -->
                        <tr class="bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 font-medium">iOS + Chrome</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-red-600 font-bold">❌ No</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-orange-600">Manual + QR</span>
                            </td>
                        </tr>
                        
                        <!-- Windows Chrome -->
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-medium">Windows + Chrome</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-yellow-600 font-bold">⚠️ Limitado</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-blue-600">Manual + QR</span>
                            </td>
                        </tr>
                        
                        <!-- Mac Safari -->
                        <tr class="bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 font-medium">Mac + Safari</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-red-600 font-bold">❌ No</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-green-600 font-bold">✅ Sí</span>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <span class="text-orange-600">Manual + QR</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Explicación de Símbolos -->
        <div class="card mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">📋 Explicación de Funcionalidades</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-green-600 font-bold">✅ Sí</span>
                        <span class="text-gray-700">Funcionalidad completamente soportada</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-yellow-600 font-bold">⚠️ Limitado</span>
                        <span class="text-gray-700">Funcionalidad con restricciones o limitaciones</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-red-600 font-bold">❌ No</span>
                        <span class="text-gray-700">Funcionalidad no soportada</span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-green-600">NFC + QR</span>
                        <span class="text-gray-700">Máxima funcionalidad disponible</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-blue-600">QR + Manual</span>
                        <span class="text-gray-700">Buena funcionalidad sin NFC</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-orange-600">Manual + QR</span>
                        <span class="text-gray-700">Funcionalidad básica pero completa</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recomendaciones por Dispositivo -->
        <div class="card mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">💡 Recomendaciones por Dispositivo</h2>
            
            <div class="space-y-4">
                <!-- Android -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="text-green-600">🤖 Android</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-1">✅ Mejor Opción:</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Chrome + NFC (automático)</li>
                                <li>• Códigos QR con cámara</li>
                                <li>• Entrada manual</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-1">📱 Pasos:</h4>
                            <ol class="text-sm text-gray-600 space-y-1">
                                <li>1. Usa Chrome para Android</li>
                                <li>2. Activa NFC en configuración</li>
                                <li>3. Acerca etiqueta NFC al teléfono</li>
                                <li>4. ¡Listo! Código se llena automáticamente</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- iOS -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="text-blue-600">🍎 iOS (iPhone/iPad)</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-1">✅ Mejor Opción:</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Entrada manual (recomendado)</li>
                                <li>• Códigos QR con cámara</li>
                                <li>• Apps de terceros para NFC</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-1">📱 Pasos:</h4>
                            <ol class="text-sm text-gray-600 space-y-1">
                                <li>1. Escribe el código manualmente</li>
                                <li>2. O escanea QR con cámara</li>
                                <li>3. Completa la información</li>
                                <li>4. ¡Listo! Mascota registrada</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Windows/Mac -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="text-gray-600">💻 Windows/Mac</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-1">✅ Mejor Opción:</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Entrada manual</li>
                                <li>• Códigos QR con cámara</li>
                                <li>• Lector RFID USB (opcional)</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-1">💻 Pasos:</h4>
                            <ol class="text-sm text-gray-600 space-y-1">
                                <li>1. Escribe el código manualmente</li>
                                <li>2. O usa cámara para QR</li>
                                <li>3. Completa el formulario</li>
                                <li>4. ¡Listo! Mascota registrada</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Etiquetas Recomendadas por Dispositivo -->
        <div class="card mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">🏷️ Etiquetas Recomendadas por Dispositivo</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">🤖 Para Android</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <strong>Etiquetas NFC:</strong> NTAG213, NTAG215</li>
                        <li>• <strong>Frecuencia:</strong> 13.56 MHz</li>
                        <li>• <strong>Estándar:</strong> ISO 14443</li>
                        <li>• <strong>Precio:</strong> $5-20 USD</li>
                    </ul>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-2">🍎 Para iOS</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <strong>Etiquetas QR:</strong> Con código visible</li>
                        <li>• <strong>Etiquetas mixtas:</strong> NFC + QR</li>
                        <li>• <strong>Código impreso:</strong> Legible a simple vista</li>
                        <li>• <strong>Precio:</strong> $8-25 USD</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="text-center space-y-4">
            <div class="flex gap-4 justify-center">
                <a href="/dashboard/pets/new" class="btn btn-primary text-lg px-8">
                    🐾 Registrar Mascota
                </a>
                <a href="/ios-nfc-guide" class="btn btn-secondary">
                    📱 Guía iOS
                </a>
            </div>
            
            <div class="flex gap-4 justify-center">
                <a href="/nfc-tags-info" class="btn btn-secondary">
                    🏷️ Etiquetas NFC
                </a>
                <a href="/rfid-scanner" class="btn btn-secondary">
                    🔍 Probar Escáner
                </a>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>
                El sistema PatitasAlMar funciona en todos los dispositivos. 
                Solo cambia la forma de escanear, pero el resultado es el mismo.
            </p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
