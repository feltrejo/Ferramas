<?php $conn = new mysqli("localhost", "root", "", "ferramas"); ?>

<?php
session_start();
if ($_SESSION['role'] != 'user') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuario - Ferramas</title>
</head>
<body>
    <h2>Página del Usuario</h2>
    <p>Bienvenido, Usuario.</p>
    
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
