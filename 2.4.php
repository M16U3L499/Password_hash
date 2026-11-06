<?php
session_start();

if (!isset($_SESSION['usuarios']) || !is_array($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = [];
}

$error = '';
$login = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
    unset($_SESSION['usuario_autenticado']);
    header('Location: 2.4.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $usuario = $_SESSION['usuarios'][$login] ?? null;

    if (
        is_array($usuario)
        && isset($usuario['password'])
        && password_verify($password, $usuario['password']) 
    ) {
        $_SESSION['usuario_autenticado'] = [
            'nombre' => $usuario['nombre'],
            'login' => $usuario['login'],
        ];
    } else {
        $error = 'Nombre de usuario o contraseña incorrectos.';
    }
}

function escapar(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="2.4.css">
</head>
<body>
    <?php if (isset($_SESSION['usuario_autenticado'])): ?>
        <h1>¡Bienvenido, <?= escapar($_SESSION['usuario_autenticado']['nombre']) ?>!</h1>
        <p>Has iniciado sesión como <strong><?= escapar($_SESSION['usuario_autenticado']['login']) ?></strong>.</p>
        <form method="post" action="2.4.php">
            <button type="submit" name="cerrar_sesion" value="1">Cerrar sesión</button>
        </form>
    <?php else: ?>
        <h1>Inicio de sesión</h1>

        <?php if (($_GET['registro'] ?? '') === 'exitoso'): ?>
            <p role="status">Registro exitoso. Ahora puedes iniciar sesión.</p>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <p class="error" role="alert"><?= escapar($error) ?></p>
        <?php endif; ?>

        <form method="post" action="2.4.php">
            <label for="login">Nombre de usuario (login)</label>
            <input type="text" id="login" name="login" value="<?= escapar($login) ?>" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Iniciar sesión</button>
        </form>

        <p>¿Aún no tienes una cuenta? <a href="2.4_2.php">Regístrate</a>.</p>
    <?php endif; ?>
</body>
</html>
