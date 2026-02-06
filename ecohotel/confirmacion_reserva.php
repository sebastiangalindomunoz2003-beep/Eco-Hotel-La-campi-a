<?php
session_start();

// Verificar si hay una reserva exitosa en la sesión
if (!isset($_SESSION['reserva_exitosa'])) {
    header('Location: index.php');
    exit;
}

// Obtener datos de la reserva
$reserva = $_SESSION['reserva_exitosa'];
unset($_SESSION['reserva_exitosa']);

// Configurar título de página
$title = "Reserva Confirmada #{$reserva['id']} - EcoHotel Doradal";
include __DIR__ . '/config.php';
require_once __DIR__ . '/config.php';
include __DIR__ . '/header.php';
?>

<div class="confirmacion-container">
    <div class="confirmacion-card">
        <div class="confirmacion-header">
            <div class="icono-exito">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="var(--color-primary)">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
            <h1>¡Reserva Confirmada!</h1>
            <p class="numero-reserva">Número de reserva: #<?= htmlspecialchars($reserva['id']) ?></p>
        </div>

        <div class="confirmacion-body">
            <div class="resumen-reserva">
                <h2>Gracias, <?= htmlspecialchars($_SESSION['user_name']) ?></h2>
                <p class="mensaje-confirmacion">
                    Hemos recibido tu solicitud de reserva
                    <strong><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></strong>.
                </p>

                <div class="detalles-reserva">
                    <h3>Detalles de tu reserva:</h3>
                    <ul>
                        <li>
                            <span class="detalle-label">Habitación:</span>
                            <span class="detalle-valor">
                                <?php 
                                // Obtener nombre del tipo de habitación
                                $stmt = $conn->prepare("SELECT nombre FROM tipos_habitacion WHERE id = ?");
                                $stmt->bind_param("i", $reserva['datos']['tipo_habitacion_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $tipo_habitacion = $result->fetch_assoc();
                                echo htmlspecialchars(ucfirst($tipo_habitacion['nombre']));
                                ?>
                            </span>
                        </li>
                        <li>
                            <span class="detalle-label">Check-in:</span>
                            <span class="detalle-valor"><?= date('d/m/Y', strtotime($reserva['datos']['fecha_entrada'])) ?></span>
                        </li>
                        <li>
                            <span class="detalle-label">Check-out:</span>
                            <span class="detalle-valor"><?= date('d/m/Y', strtotime($reserva['datos']['fecha_salida'])) ?></span>
                        </li>
                        <li>
                            <span class="detalle-label">Noches:</span>
                            <span class="detalle-valor">
                                <?= (strtotime($reserva['datos']['fecha_salida']) - strtotime($reserva['datos']['fecha_entrada'])) / (60 * 60 * 24) ?>
                            </span>
                        </li>
                        <li>
                            <span class="detalle-label">Huéspedes:</span>
                            <span class="detalle-valor"><?= htmlspecialchars($reserva['datos']['huespedes']) ?></span>
                        </li>
                        <li>
                            <span class="detalle-label">Total reserva:</span>
                            <span class="detalle-valor">$<?= number_format($reserva['datos']['precio_total'], 0, ',', '.') ?> COP</span>
                        </li>
                        <?php if (!empty($reserva['datos']['comentarios'])): ?>
                        <li>
                            <span class="detalle-label">Tus comentarios:</span>
                            <span class="detalle-valor"><?= htmlspecialchars($reserva['datos']['comentarios']) ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="nota-reserva">
                    <p>
                        <i class="fas fa-info-circle"></i> 
                        Nos pondremos en contacto contigo dentro de las próximas 24 horas para confirmar la disponibilidad.
                    </p>
                </div>
            </div>

            <div class="acciones-reserva">
                <a href="index.php" class="btn btn-volver">
                    <i class="fas fa-home"></i> Volver al inicio
                </a>
                <a href="perfil.php" class="btn btn-perfil">
                    <i class="fas fa-user"></i> Ver mi perfil
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para la página de confirmación */
.confirmacion-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 1rem;
}

.confirmacion-card {
    background: var(--color-card);
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.confirmacion-header {
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    padding: 2.5rem;
    text-align: center;
    position: relative;
}

.icono-exito {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icono-exito svg {
    width: 50px;
    height: 50px;
}

.confirmacion-header h1 {
    margin: 0;
    font-size: 2.2rem;
    font-weight: 700;
}

.numero-reserva {
    margin-top: 0.5rem;
    font-size: 1.1rem;
    opacity: 0.9;
}

.confirmacion-body {
    padding: 2.5rem;
}

.resumen-reserva h2 {
    color: var(--color-primary);
    margin-top: 0;
    font-size: 1.8rem;
}

.mensaje-confirmacion {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.detalles-reserva {
    background: var(--color-bg);
    padding: 1.5rem;
    border-radius: 12px;
    margin: 2rem 0;
}

.detalles-reserva h3 {
    margin-top: 0;
    color: var(--color-primary);
    font-size: 1.4rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--color-border);
}

.detalles-reserva ul {
    list-style: none;
    padding: 0;
    margin: 1.5rem 0 0;
}

.detalles-reserva li {
    display: flex;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--color-border);
}

.detalle-label {
    font-weight: 600;
    width: 150px;
    color: var(--color-text);
}

.detalle-valor {
    flex: 1;
    color: var(--color-text);
}

.nota-reserva {
    background: rgba(86, 201, 165, 0.1);
    border-left: 4px solid var(--color-primary);
    padding: 1rem;
    border-radius: 0 8px 8px 0;
    margin: 2rem 0;
}

.nota-reserva p {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--color-text);
}

.nota-reserva i {
    color: var(--color-primary);
}

.acciones-reserva {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 2rem;
}

.btn {
    padding: 1rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-volver {
    background: var(--color-primary);
    color: white;
}

.btn-volver:hover {
    background: var(--color-secondary);
    transform: translateY(-2px);
}

.btn-perfil {
    background: var(--color-bg);
    color: var(--color-primary);
    border: 2px solid var(--color-primary);
}

.btn-perfil:hover {
    background: var(--color-primary);
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .confirmacion-header {
        padding: 2rem 1.5rem;
    }
    
    .confirmacion-body {
        padding: 1.5rem;
    }
    
    .detalles-reserva li {
        flex-direction: column;
        gap: 0.3rem;
    }
    
    .detalle-label {
        width: 100%;
    }
    
    .acciones-reserva {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .confirmacion-header h1 {
        font-size: 1.8rem;
    }
    
    .resumen-reserva h2 {
        font-size: 1.5rem;
    }
}
</style>

<?php include __DIR__ . '/footer.php'; ?>