<?php
include 'config.php';

$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']) : '';
$tipo_producto = isset($_POST['tipo']) ? intval($_POST['tipo']) : '';

$query = $conn->prepare("
    SELECT p.id_producto, p.precio, p.nombre, p.descripcion, p.stock, p.descuento,
           CASE WHEN p.es_oferta = 1 THEN p.precio - (p.precio * p.descuento / 100) ELSE p.precio END AS precio_con_descuento
    FROM producto p
    JOIN tipo_producto t ON p.id_tipo_producto = t.id_tipo_producto
    WHERE p.id_tipo_producto = ? AND (p.nombre LIKE ? OR p.descripcion LIKE ? OR t.nombre_tipo LIKE ?)
");
$searchTerm = "%{$campo}%";
$query->bind_param("isss", $tipo_producto, $searchTerm, $searchTerm, $searchTerm);
$query->execute();
$result = $query->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($productos);
?>
