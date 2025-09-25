<?php
session_start();

// Destruir la sesión
session_destroy();

// Limpiar cookies si existen
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Redirigir al inicio
header('Location: /');
exit();
?>