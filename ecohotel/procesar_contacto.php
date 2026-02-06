<?php
session_start();
require __DIR__ . '/config.php';

// 1. Verificación de sesión
verificarSesion();

// 2. Validar método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php#contacto?error=metodo_invalido');
    exit;
}

// 3. Obtener y sanitizar datos
$datos = [
    'nombre' => sanitizarInput($_POST['nombre']),
    'email' => isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '',
    'telefono' => isset($_POST['telefono']) ? sanitizarInput($_POST['telefono']) : '',
    'mensaje' => sanitizarInput($_POST['mensaje']),
    'usuario_id' => $_SESSION['user_id']
];

// 4. Validaciones
$errores = [];

// Validar campos obligatorios
if (empty($datos['nombre']) || empty($datos['mensaje'])) {
    $errores[] = "Nombre y mensaje son campos obligatorios";
}

// Validar longitud mínima del mensaje
if (strlen($datos['mensaje']) < 10) {
    $errores[] = "El mensaje debe tener al menos 10 caracteres";
}

// 5. Manejar errores
if (!empty($errores)) {
    $_SESSION['contacto_errores'] = $errores;
    $_SESSION['datos_contacto'] = $datos;
    header('Location: index.php#contacto');
    exit;
}

// 6. Insertar en base de datos
try {
    $stmt = $conn->prepare("INSERT INTO mensajes_contacto 
        (nombre, email, telefono, mensaje, usuario_id, fecha_envio) 
        VALUES (?, ?, ?, ?, ?, NOW())");
    
    $stmt->bind_param("ssssi", 
        $datos['nombre'],
        $datos['email'],
        $datos['telefono'],
        $datos['mensaje'],
        $datos['usuario_id']
    );

    if ($stmt->execute()) {
        // 7. Notificación de éxito
        $_SESSION['contacto_exito'] = "Tu mensaje ha sido enviado. Te responderemos pronto.";
        
        // 8. Opcional: Enviar notificación por email
        // enviarNotificacionContacto($datos);
        
    } else {
        throw new Exception("Error al guardar el mensaje");
    }
} catch (Exception $e) {
    // 9. Manejo de errores de base de datos
    error_log("Error en procesar_contacto: " . $e->getMessage());
    $_SESSION['contacto_error'] = "Error al enviar tu mensaje. Por favor intenta nuevamente.";
}

// 10. Redireccionar
header('Location: index.php#contacto');
exit;
?>