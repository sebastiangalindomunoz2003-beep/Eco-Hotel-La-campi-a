<?php
session_start();
require __DIR__ . '/config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];

            header("Location: index.php");
            exit;
        } else {
            $message = "Contraseña incorrecta.";
        }
    } else {
        $message = "No existe usuario con ese correo.";
    }
}

$title = 'Iniciar Sesión - EcoHotel Doradal';
$auth_page = true;
include __DIR__ . '/header.php';
include __DIR__ . '/navbar.php';
include __DIR__ . '/theme-toggle.php';
?>

<div class="container">
    <?php renderThemeToggle(); ?>
    
    <div class="auth-container-wrapper">
        <div class="auth-container">
            <h2>Iniciar Sesión</h2>

            <?php if ($message): ?>
                <div class="auth-message auth-error"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="auth-form" autocomplete="on">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required placeholder="tu@email.com" autocomplete="email" />

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required placeholder="********" autocomplete="current-password" />

                <button type="submit" class="auth-btn">Entrar</button>
            </form>

            <p class="auth-footer-text">
                ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>.
            </p>
        </div>
    </div>
    
    <?php include __DIR__ . '/footer.php'; ?>
</div>

<script src="js/main.js"></script>
</body>
</html>