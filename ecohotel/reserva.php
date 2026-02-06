<?php
session_start();
include __DIR__ . '/config.php';
include __DIR__ . '/theme-toggle.php';

verificarSesion();

$tipo_habitacion_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM tipos_habitacion WHERE id = ?");
$stmt->bind_param("i", $tipo_habitacion_id);
$stmt->execute();
$resultado = $stmt->get_result();
$habitacion = $resultado->fetch_assoc();

if (!$habitacion) {
    header('Location: index.php');
    exit;
}

$title = 'Reservar ' . htmlspecialchars($habitacion['nombre']) . ' - EcoHotel Doradal';
include __DIR__ . '/header.php';
include __DIR__ . '/navbar.php';
?>

<div class="container">
    <?php renderThemeToggle(); ?>

    <section class="reserva-container">
        <h1 class="section-title">Reservar <?php echo htmlspecialchars($habitacion['nombre']); ?></h1>
        
        <div class="reserva-content">
            <div class="reserva-imagen">
                <img src="<?php echo htmlspecialchars($habitacion['imagen']); ?>" alt="<?php echo htmlspecialchars($habitacion['nombre']); ?>" />
                <div class="reserva-precio">
                    <span><?php echo '$' . number_format($habitacion['precio_noche'], 0, ',', '.'); ?> COP / noche</span>
                </div>
            </div>
            
            <div class="reserva-info">
                <h2><?php echo htmlspecialchars($habitacion['nombre']); ?></h2>
                <p><?php echo htmlspecialchars($habitacion['descripcion']); ?></p>
                <p>Capacidad: <?php echo htmlspecialchars($habitacion['capacidad']); ?> personas</p>
                
                <form id="reservaForm" method="POST" action="procesar_reserva.php" class="needs-validation" novalidate>
                    <input type="hidden" name="tipo_habitacion_id" value="<?php echo $tipo_habitacion_id; ?>">
                    <input type="hidden" name="precio_noche" value="<?php echo htmlspecialchars($habitacion['precio_noche']); ?>">
                    
                    <div class="form-group">
                        <label for="fecha_entrada">Fecha de entrada:</label>
                        <input type="date" id="fecha_entrada" name="fecha_entrada" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        <div class="invalid-feedback">Por favor selecciona una fecha de entrada válida</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_salida">Fecha de salida:</label>
                        <input type="date" id="fecha_salida" name="fecha_salida" class="form-control" required>
                        <div class="invalid-feedback">Por favor selecciona una fecha de salida válida</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="huespedes">Número de huéspedes:</label>
                        <select id="huespedes" name="huespedes" class="form-control" required>
                            <option value="" disabled selected>Seleccione...</option>
                            <?php for ($i = 1; $i <= $habitacion['capacidad']; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                        <div class="invalid-feedback">Por favor selecciona el número de huéspedes</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="comentarios">Comentarios adicionales:</label>
                        <textarea id="comentarios" name="comentarios" class="form-control" rows="4"></textarea>
                    </div>
                    
                    <div class="form-group form-check">
                        <input type="checkbox" id="terminos" name="terminos" class="form-check-input" required>
                        <label for="terminos" class="form-check-label">Acepto los términos y condiciones</label>
                        <div class="invalid-feedback">Debes aceptar los términos y condiciones</div>
                    </div>
                    
                    <button type="submit" class="btn btn-reservar">
                        <i class="fas fa-calendar-check"></i> Confirmar Reserva
                    </button>
                </form>
            </div>
        </div>
    </section>
    
    <?php include __DIR__ . '/footer.php'; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="js/main.js"></script>
<script>
AOS.init();

(function() {
    'use strict';
    window.addEventListener('load', function() {
        document.getElementById('fecha_entrada').addEventListener('change', function() {
            const entrada = new Date(this.value);
            const salida = document.getElementById('fecha_salida');
            salida.min = this.value;
            
            if (new Date(salida.value) < entrada) {
                salida.value = '';
            }
        });

        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
</body>
</html>