<?php
$conn = new mysqli("localhost", "root", "", "ferramas");

$username = 'admin';
$password = password_hash('Ferr@mas2024!', PASSWORD_DEFAULT);
$role = 'admin'; 

$conn->query("INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");
echo "Usuario creado con éxito!";
?>
