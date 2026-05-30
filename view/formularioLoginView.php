<?php
include_once 'public/header.php';
?>

<section>
    <h1>Login</h1>
    <?php if (isset($_SESSION['login_error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['login_error'];
            unset($_SESSION['login_error']); ?>
        </div>
    <?php endif; ?>
    <form action="?controlador=Usuario&accion=login" method="post">
        <div>
            <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Nombre de usuario" required>
            <input type="password" name="contrasena" id="contrasena" placeholder="******" required>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </form>
</section>

<?php
include_once 'public/footer.php';
?>