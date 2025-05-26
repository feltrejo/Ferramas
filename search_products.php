<?php
include 'config.php';

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

$result = $conn->query("SELECT id_producto, nombre FROM producto WHERE id_producto LIKE '%$query%' OR nombre LIKE '%$query%'");

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
?>
