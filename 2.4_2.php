<?php
session_start();

if (!isset($_SESSION['usuarios']) || !is_array($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = [];
}

$errores = [];
$nombre = '';
$login = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmacion = $_POST['confirmacion'] ?? '';

    if ($nombre === '' || $login === '' || $password === '' || $confirmacion === '') {
        $errores[] = 'Todos los campos son obligatorios.';
    }

    $longitudPassword = preg_match_all('/./us', $password);
    if ($longitudPassword === false || $longitudPassword < 6) {
        $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    }

    if ($password !== $confirmacion) {
        $errores[] = 'La contraseña y su confirmación no coinciden.';
    }

    if (isset($_SESSION['usuarios'][$login])) {
        $errores[] = 'Ese nombre de usuario ya está registrado.';
    }

    if ($errores === []) {
        $_SESSION['usuarios'][$login] = [
            'nombre' => $nombre,
            'login' => $login,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        header('Location: 2.4.php?registro=exitoso');
        exit;
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
    <title>Registro de usuarios</title>
    <link rel="stylesheet" href="2.4_2.css">
</head>
<body>
    <img class="registration-image" src="imagenes/img2.jpg" alt="Ilustración de una pantalla de inicio de sesión">
    <h1>Registro de usuarios</h1>

    <?php if ($errores !== []): ?>
        <ul class="error" role="alert">
            <?php foreach ($errores as $error): ?>
                <li><?= escapar($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="2.4_2.php">
        <label for="nombre">Nombre del usuario</label>
        <input type="text" id="nombre" name="nombre" value="<?= escapar($nombre) ?>" required>

        <label for="login">Nombre de usuario (login)</label>
        <input type="text" id="login" name="login" value="<?= escapar($login) ?>" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" minlength="6" required>

        <label for="confirmacion">Confirmación de contraseña</label>
        <input type="password" id="confirmacion" name="confirmacion" minlength="6" required>

        <button type="submit">Registrarse</button>
    </form>

    <p>¿Ya tienes una cuenta? <a href="2.4.php">Inicia sesión</a>.</p>
</body>
</html>
