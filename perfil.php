<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];


$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

$update_success = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    if (password_verify($current_password, $user['password'])) {
        
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $update_query->bind_param("ss", $hashed_password, $username);
        $update_query->execute();

        $update_success = true;
    } else {
        $update_success = false;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mt-5">
    <h1>Perfil de Usuario</h1>
    <p>Nombre de usuario: <?= htmlspecialchars($user['username']); ?></p>

    <?php if ($update_success === true): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Contraseña actualizada con éxito.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($update_success === false): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            La contraseña actual no es correcta.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="post" action="perfil.php">
        <div class="mb-3">
            <label for="current_password" class="form-label">Contraseña Actual</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
    </form>

    
    <div class="mt-3">
        <a href="forgot_password.php" class="link-primary">¿Olvidaste tu contraseña?</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
