<?php
session_start();
require __DIR__ . '/config.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Obtener y sanitizar datos
$datos = [
    'usuario_id' => $_SESSION['user_id'],
    'tipo_habitacion_id' => (int)$_POST['tipo_habitacion_id'],
    'fecha_entrada' => $_POST['fecha_entrada'],
    'fecha_salida' => $_POST['fecha_salida'],
    'huespedes' => (int)$_POST['huespedes'],
    'comentarios' => isset($_POST['comentarios']) ? $_POST['comentarios'] : '',
    'estado' => 'pendiente'
];

// Validaciones
$errores = [];
if ($datos['tipo_habitacion_id'] <= 0) {
    $errores[] = "Tipo de habitación inválido";
}

if (strtotime($datos['fecha_entrada']) >= strtotime($datos['fecha_salida'])) {
    $errores[] = "La fecha de salida debe ser posterior a la de entrada";
}

// Buscar habitación disponible
$stmt = $conn->prepare("
    SELECT h.id 
    FROM habitaciones h
    WHERE h.tipo_id = ? 
    AND h.estado = 'disponible'
    AND NOT EXISTS (
        SELECT 1 FROM reservas r 
        WHERE r.habitacion_id = h.id 
        AND r.fecha_entrada < ? AND r.fecha_salida > ?
        AND r.estado IN ('pendiente', 'confirmada')
    )
    LIMIT 1
");

$stmt->bind_param(
    "iss", 
    $datos['tipo_habitacion_id'],
    $datos['fecha_salida'],
    $datos['fecha_entrada']
);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_reserva'] = "No hay habitaciones disponibles para las fechas seleccionadas.";
    header('Location: reserva.php?id=' . $datos['tipo_habitacion_id']);
    exit;
}

$habitacion = $result->fetch_assoc();
$datos['habitacion_id'] = $habitacion['id'];

// Calcular precio total
$noches = (strtotime($datos['fecha_salida']) - strtotime($datos['fecha_entrada'])) / (60 * 60 * 24);

// Obtener precio real de la base de datos (Seguridad)
$stmt_precio = $conn->prepare("SELECT precio_noche FROM tipos_habitacion WHERE id = ?");
$stmt_precio->bind_param("i", $datos['tipo_habitacion_id']);
$stmt_precio->execute();
$res_precio = $stmt_precio->get_result();
$tipo_habitacion = $res_precio->fetch_assoc();
$precio_noche = (float)$tipo_habitacion['precio_noche'];

$datos['precio_total'] = $noches * $precio_noche;

// Insertar reserva
try {
    $stmt = $conn->prepare("INSERT INTO reservas (
        usuario_id, habitacion_id, fecha_entrada, fecha_salida,
        huespedes, precio_total, estado, fecha_reserva, comentarios
    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
    
    $stmt->bind_param("iissidss", 
        $datos['usuario_id'],
        $datos['habitacion_id'],
        $datos['fecha_entrada'],
        $datos['fecha_salida'],
        $datos['huespedes'],
        $datos['precio_total'],
        $datos['estado'],
        $datos['comentarios']
    );

    if ($stmt->execute()) {
        $_SESSION['reserva_exitosa'] = [
            'id' => $stmt->insert_id,
            'datos' => $datos
        ];
        header('Location: confirmacion_reserva.php');
        exit;
    } else {
        throw new Exception("Error al guardar la reserva: " . $stmt->error);
    }
} catch (Exception $e) {
    error_log("Error en procesar_reserva: " . $e->getMessage());
    $_SESSION['error_reserva'] = "Ocurrió un error al procesar tu reserva. Por favor intenta nuevamente.";
    header('Location: reserva.php?id=' . $datos['tipo_habitacion_id']);
    exit;
}
?>