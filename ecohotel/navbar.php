<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
  <ul>
    <li>
      <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
        Inicio
      </a>
    </li>
    <li><a href="index.php#habitaciones">Habitaciones</a></li>
    <li><a href="index.php#contacto">Contacto</a></li>

    <?php if (isset($_SESSION['user_id'])): ?>
      <li class="user-greeting">
        <a href="perfil.php" style="color: inherit; text-decoration: none;">
          Hola, <?= htmlspecialchars($_SESSION['user_name']) ?>
        </a>
      </li>
      <li><a href="logout.php" class="btn-logout">Cerrar Sesión</a></li>
    <?php else: ?>
      <li><a href="login.php" class="btn-login">Iniciar Sesión</a></li>
      <li><a href="registro.php" class="btn-register">Registrarse</a></li>
    <?php endif; ?>
  </ul>
</nav>