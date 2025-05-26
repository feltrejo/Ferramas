<?php

$conn = new mysqli("localhost", "root", "", "ferramas");


if ($conn->connect_error) {
    echo 'Error de conexión ' . $conn->connect_error;
    exit;
}
?>
