<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Simulamos enviar un código al correo
    // En un caso real, aquí se enviaría el correo con el código

    // Guardar el correo en la sesión para usarlo más adelante
    session_start();
    $_SESSION['email'] = $email;

    // Redirigir a la página de verificación de código
    header("Location: verify_code.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Recuperar Contraseña</h1>
    <form method="post" action="forgot_password.php">
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Enviar código</button>
    </form>
</div>
</body>
</html>
