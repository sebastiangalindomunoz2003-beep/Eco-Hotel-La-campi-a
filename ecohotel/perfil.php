<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include __DIR__ . '/config.php';
include __DIR__ . '/theme-toggle.php';

$title = 'Mi Perfil - EcoHotel Doradal';
include __DIR__ . '/header.php';
include __DIR__ . '/navbar.php';

$usuario_id = $_SESSION['user_id'];

// Cancelar reserva
if (isset($_GET['cancelar']) && is_numeric($_GET['cancelar'])) {
    $reserva_id = $_GET['cancelar'];
    $stmt = $conn->prepare("UPDATE reservas SET estado = 'cancelada' WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $reserva_id, $usuario_id);
    $stmt->execute();
    $stmt->close();
    header("Location: perfil.php");
    exit;
}

// Obtener reservas del usuario
$stmt = $conn->prepare("
    SELECT r.id, th.nombre AS tipo_habitacion, h.numero, 
           r.fecha_entrada, r.fecha_salida, r.estado, r.precio_total
    FROM reservas r
    JOIN habitaciones h ON r.habitacion_id = h.id
    JOIN tipos_habitacion th ON h.tipo_id = th.id
    WHERE r.usuario_id = ? AND r.estado != 'cancelada'
    ORDER BY r.fecha_entrada DESC
");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<div class="container profile-container">
    <?php renderThemeToggle(); ?>
    
    <main class="profile-main">
        <h2 class="section-title">Mis Reservas</h2>

        <?php if ($resultado->num_rows > 0): ?>
            <div class="reservas-grid">
                <?php while ($row = $resultado->fetch_assoc()): ?>
                    <div class="reserva-card">
                        <div class="reserva-header">
                            <h3>Habitación <?= htmlspecialchars($row['tipo_habitacion']) ?> #<?= htmlspecialchars($row['numero']) ?></h3>
                            <span class="reserva-status <?= htmlspecialchars($row['estado']) ?>">
                                <?= ucfirst(htmlspecialchars($row['estado'])) ?>
                            </span>
                        </div>
                        
                        <div class="reserva-details">
                            <div class="detail-item">
                                <span class="detail-label">Check-in:</span>
                                <span class="detail-value"><?= date('d/m/Y', strtotime($row['fecha_entrada'])) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Check-out:</span>
                                <span class="detail-value"><?= date('d/m/Y', strtotime($row['fecha_salida'])) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Total:</span>
                                <span class="detail-value">$<?= number_format($row['precio_total'], 0, ',', '.') ?> COP</span>
                            </div>
                        </div>
                        
                        <?php if ($row['estado'] == 'pendiente' || $row['estado'] == 'confirmada'): ?>
                            <a href="perfil.php?cancelar=<?= $row['id'] ?>" class="btn-cancelar" onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-reservas">
                <i class="fas fa-calendar-times"></i>
                <p>No tienes reservas activas.</p>
                <a href="index.php#habitaciones" class="btn-reservar">Reservar ahora</a>
            </div>
        <?php endif; ?>
    </main>

    <style>
        /* Estilos específicos para la página de perfil */
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-main {
            margin-top: 30px;
        }

        .reservas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .reserva-card {
            background: var(--color-card);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--color-border);
        }

        .reserva-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .reserva-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--color-border);
        }

        .reserva-header h3 {
            margin: 0;
            font-size: 1.2rem;
            color: var(--color-primary);
        }

        .reserva-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .reserva-status.pendiente {
            background-color: #fff3cd;
            color: #856404;
        }

        .reserva-status.confirmada {
            background-color: #d4edda;
            color: #155724;
        }

        .reserva-status.completada {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .reserva-details {
            margin-bottom: 15px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .detail-label {
            font-weight: 600;
            color: var(--color-text);
        }

        .detail-value {
            color: var(--color-text);
        }

        .btn-cancelar {
            display: inline-block;
            background-color: #f8d7da;
            color: #721c24;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 90%;
            text-align: center;
            margin-top: 10px;
        }

        .btn-cancelar:hover {
            background-color: #f5c6cb;
            color: #721c24;
        }

        .no-reservas {
            text-align: center;
            padding: 40px 20px;
            background: var(--color-card);
            border-radius: 10px;
            margin-top: 30px;
        }

        .no-reservas i {
            font-size: 3rem;
            color: var(--color-primary);
            margin-bottom: 15px;
        }

        .no-reservas p {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: var(--color-text);
        }

        .btn-reservar {
            display: inline-block;
            background-color: var(--color-primary);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-reservar:hover {
            background-color: var(--color-secondary);
        }

        @media (max-width: 768px) {
            .reservas-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-container {
                padding: 15px;
            }
        }
    </style>

    <?php 
    $stmt->close(); 
    $conn->close(); 
    ?>
    <?php include __DIR__ . '/footer.php'; ?>
</div>

<script src="js/main.js"></script>
</body>
</html>