<?php
include 'config.php';

$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']) : '';
$registros = isset($_POST['registros']) ? (int)$_POST['registros'] : 10;
$pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
$orderCol = isset($_POST['orderCol']) ? (int)$_POST['orderCol'] : 0;
$orderType = isset($_POST['orderType']) && in_array($_POST['orderType'], ['asc', 'desc']) ? $_POST['orderType'] : 'asc';
$es_oferta = isset($_POST['es_oferta']) ? (int)$_POST['es_oferta'] : null;
$limit = $registros;
$offset = ($pagina - 1) * $registros;

$orderColumns = ['id_producto', 'nombre', 'descripcion', 'precio', 'stock', 'id_tipo_producto'];
$orderBy = $orderColumns[$orderCol] . ' ' . $orderType;

$sql = "SELECT p.id_producto, p.precio, p.nombre, p.descripcion, p.stock, p.descuento, 
        t.nombre_tipo AS tipo_producto,
        CASE WHEN p.es_oferta = 1 THEN p.precio - (p.precio * p.descuento / 100) ELSE p.precio END AS precio_con_descuento
        FROM producto p
        JOIN tipo_producto t ON p.id_tipo_producto = t.id_tipo_producto
        WHERE (p.nombre LIKE '%$campo%' OR p.descripcion LIKE '%$campo%' OR t.nombre_tipo LIKE '%$campo%')";
        
if ($es_oferta !== null) {
    $sql .= " AND p.es_oferta = $es_oferta";
}

$sql .= " ORDER BY $orderBy LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$productos = [];
while ($row = $result->fetch_assoc()) {
    $row['image_url'] = 'imagenes/productos/' . $row['id_producto'] . '.jpg'; // Ajusta según el nombre de tus archivos de imagen
    $productos[] = $row;
}

$sqlTotal = "SELECT COUNT(*) as total FROM producto";
$resultTotal = $conn->query($sqlTotal);
$totalRegistros = $resultTotal->fetch_assoc()['total'];

$sqlTotalFiltro = "SELECT COUNT(*) as totalFiltro
                   FROM producto p
                   JOIN tipo_producto t ON p.id_tipo_producto = t.id_tipo_producto
                   WHERE (p.nombre LIKE '%$campo%' OR p.descripcion LIKE '%$campo%' OR t.nombre_tipo LIKE '%$campo%')";
                   
if ($es_oferta !== null) {
    $sqlTotalFiltro .= " AND p.es_oferta = $es_oferta";
}

$resultTotalFiltro = $conn->query($sqlTotalFiltro);
$totalFiltro = $resultTotalFiltro->fetch_assoc()['totalFiltro'];

$totalPaginas = ceil($totalFiltro / $registros);
$paginacion = '';
for ($i = 1; $i <= $totalPaginas; $i++) {
    $paginacion .= "<button class='btn btn-link' onclick='changePage($i)'>$i</button> ";
}

echo json_encode([
    'data' => $productos,
    'totalFiltro' => $totalFiltro,
    'totalRegistros' => $totalRegistros,
    'paginacion' => $paginacion
]);
?>
