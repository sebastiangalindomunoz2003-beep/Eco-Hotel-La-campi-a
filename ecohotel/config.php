<?php
// config.php - Configuración centralizada

// 1. Conexión a la base de datos
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ecohotel';

// 2. Establecer conexión
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// 3. Configuración básica
$site_name = 'EcoHotel Doradal';

// 4. Función única de sanitización
if (!function_exists('sanitizarInput')) {
    function sanitizarInput($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}

// 5. Función para verificar sesión
if (!function_exists('verificarSesion')) {
    function verificarSesion() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header('Location: login.php');
            exit;
        }
    }
}