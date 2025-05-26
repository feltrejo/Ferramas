<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];

    // Simulamos que cualquier código es válido
    if ($code) {
        // Redirigir a la página de restablecimiento de contraseña
        header("Location: reset_password.php");
        exit();
    } else {
        echo "Código incorrecto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Código</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Verificar Código</h1>
    <form method="post" action="verify_code.php">
        <div class="mb-3">
            <label for="code" class="form-label">Ingresa el código enviado a tu correo</label>
            <input type="text" class="form-control" id="code" name="code" required>
        </div>
        <button type="submit" class="btn btn-primary">Verificar</button>
    </form>
</div>
</body>
</html>
