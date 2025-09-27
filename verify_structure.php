<?php
/**
 * Verificar estructura de archivos y rutas del proyecto
 * Ejecutar en: http://localhost/patitasalmar-php/verify_structure.php
 */

echo "<h1>🔍 Verificación de Estructura - PatitasAlMar</h1>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; }";
echo ".success { color: green; background: #e8f5e8; padding: 5px 10px; border-radius: 3px; }";
echo ".error { color: red; background: #ffe8e8; padding: 5px 10px; border-radius: 3px; }";
echo ".warning { color: orange; background: #fff8e1; padding: 5px 10px; border-radius: 3px; }";
echo "table { border-collapse: collapse; width: 100%; margin: 10px 0; }";
echo "th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }";
echo "th { background: #f0f0f0; }";
echo "</style>";

$baseDir = __DIR__;
echo "<p><strong>Directorio base:</strong> $baseDir</p>";

// Archivos y carpetas que deben existir
$requiredStructure = [
    // APIs
    'api/rfid/scan.php' => 'file',
    'api/auth/login.php' => 'file',
    'api/auth/register.php' => 'file',
    'api/pets/create.php' => 'file',
    'api/pets/index.php' => 'file',
    'api/dashboard/stats.php' => 'file',
    
    // Configuración
    'config/database.php' => 'file',
    
    // Páginas principales
    'index.php' => 'file',
    'rfid-scanner/index.php' => 'file',
    'auth/login.php' => 'file',
    'auth/register.php' => 'file',
    'dashboard/index.php' => 'file',
    'dashboard/pets/new.php' => 'file',
    
    // Includes
    'includes/header.php' => 'file',
    'includes/footer.php' => 'file',
    'includes/functions.php' => 'file',
    
    // Assets
    'assets/css/style.css' => 'file',
    'assets/js/main.js' => 'file',
    'assets/js/utils.js' => 'file',
    'assets/js/rfid-scanner.js' => 'file',
    
    // Carpetas
    'api' => 'dir',
    'config' => 'dir',
    'assets' => 'dir',
    'dashboard' => 'dir',
    'auth' => 'dir',
    'includes' => 'dir'
];

echo "<h2>📁 Verificación de Estructura</h2>";
echo "<table>";
echo "<tr><th>Archivo/Carpeta</th><th>Tipo</th><th>Estado</th><th>Permisos</th></tr>";

foreach ($requiredStructure as $path => $type) {
    $fullPath = $baseDir . '/' . $path;
    $exists = ($type === 'file') ? file_exists($fullPath) : is_dir($fullPath);
    $permissions = $exists ? substr(sprintf('%o', fileperms($fullPath)), -4) : '-';
    
    $status = $exists ? 
        "<span class='success'>✅ Existe</span>" : 
        "<span class='error'>❌ No existe</span>";
    
    echo "<tr>";
    echo "<td>$path</td>";
    echo "<td>" . ucfirst($type) . "</td>";
    echo "<td>$status</td>";
    echo "<td>$permissions</td>";
    echo "</tr>";
}
echo "</table>";

// Verificar APIs específicamente
echo "<h2>🔌 Verificación de APIs</h2>";

$apiTests = [
    'api/rfid/scan.php' => 'POST',
    'api/auth/login.php' => 'POST',
    'api/pets/create.php' => 'POST'
];

foreach ($apiTests as $api => $method) {
    $fullPath = $baseDir . '/' . $api;
    $url = "http://localhost/patitasalmar-php/$api";
    
    echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 5px;'>";
    echo "<strong>$api</strong><br>";
    echo "Archivo: " . (file_exists($fullPath) ? 
        "<span class='success'>✅ Existe</span>" : 
        "<span class='error'>❌ No existe</span>");
    echo "<br>";
    echo "URL: <a href='$url' target='_blank'>$url</a><br>";
    
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        $hasHeader = strpos($content, 'Content-Type: application/json') !== false;
        echo "JSON Header: " . ($hasHeader ? 
            "<span class='success'>✅ Correcto</span>" : 
            "<span class='warning'>⚠️ Falta</span>");
    }
    echo "</div>";
}

// Verificar configuración de base de datos
echo "<h2>🗄️ Verificación de Base de Datos</h2>";

try {
    require_once 'config/database.php';
    
    $database = new Database();
    $test = $database->testConnection();
    
    if ($test['success']) {
        echo "<div class='success'>";
        echo "<h3>✅ Conexión exitosa</h3>";
        echo "<p><strong>Host:</strong> {$test['host']}</p>";
        echo "<p><strong>Puerto:</strong> {$test['port']}</p>";
        echo "<p><strong>Base de datos:</strong> {$test['database']}</p>";
        echo "<p><strong>MySQL:</strong> {$test['mysql_version']}</p>";
        echo "</div>";
    } else {
        echo "<div class='error'>";
        echo "<h3>❌ Error de conexión</h3>";
        echo "<p>{$test['message']}</p>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ Error cargando configuración</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

// Verificar permisos web
echo "<h2>🌐 Verificación Web</h2>";

$webTests = [
    'index.php' => 'Página principal',
    'rfid-scanner/index.php' => 'Escáner RFID',
    'auth/login.php' => 'Login',
    'api/rfid/scan.php' => 'API RFID'
];

echo "<table>";
echo "<tr><th>Página</th><th>Descripción</th><th>URL</th><th>Test</th></tr>";

foreach ($webTests as $page => $description) {
    $url = "http://localhost/patitasalmar-php/$page";
    $testUrl = $url;
    
    echo "<tr>";
    echo "<td>$page</td>";
    echo "<td>$description</td>";
    echo "<td><a href='$url' target='_blank'>$url</a></td>";
    echo "<td><button onclick=\"testUrl('$testUrl')\">🧪 Probar</button></td>";
    echo "</tr>";
}
echo "</table>";

// JavaScript para probar URLs
echo "<script>";
echo "function testUrl(url) {";
echo "  window.open(url, '_blank');";
echo "}";
echo "</script>";

// Información del servidor
echo "<h2>⚙️ Información del Servidor</h2>";
echo "<table>";
echo "<tr><th>Variable</th><th>Valor</th></tr>";
echo "<tr><td>PHP Version</td><td>" . phpversion() . "</td></tr>";
echo "<tr><td>Server Software</td><td>" . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Document Root</td><td>" . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Server Name</td><td>" . ($_SERVER['SERVER_NAME'] ?? 'Unknown') . "</td></tr>";
echo "<tr><td>Request URI</td><td>" . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "</td></tr>";
echo "</table>";

// Próximos pasos
echo "<h2>🚀 Próximos Pasos</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>Para arreglar el escáner RFID:</h3>";
echo "<ol>";
echo "<li><strong>Verifica que api/rfid/scan.php exista</strong> y tenga el contenido correcto</li>";
echo "<li><strong>Asegúrate de que la conexión a BD funcione</strong> (debe estar en verde arriba)</li>";
echo "<li><strong>Prueba la API directamente:</strong> <a href='api/rfid/scan.php' target='_blank'>api/rfid/scan.php</a></li>";
echo "<li><strong>Revisa la consola del navegador</strong> (F12) para ver errores JavaScript</li>";
echo "<li><strong>Verifica que .htaccess esté configurado</strong> para las rutas</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p style='color: #666; font-size: 12px;'>Verificación completada: " . date('Y-m-d H:i:s') . "</p>";
?>