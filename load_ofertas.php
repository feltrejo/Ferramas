<?php
require 'config.php';
require 'convertir.php';


$columns = ['id_producto', 'nombre', 'descripcion', 'precio', 'stock', 'id_tipo_producto', 'es_oferta'];


$table = "producto";


$id = 'id_producto';


$where = 'WHERE es_oferta = 1';


$limit = isset($_POST['registros']) ? $conn->real_escape_string($_POST['registros']) : 10;
$pagina = isset($_POST['pagina']) ? $conn->real_escape_string($_POST['pagina']) : 0;
if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $limit;
}
$sLimit = "LIMIT $inicio , $limit";


$sOrder = "";
if (isset($_POST['orderCol'])) {
    $orderCol = $_POST['orderCol'];
    $orderType = isset($_POST['orderType']) ? $_POST['orderType'] : 'asc';
    $sOrder = "ORDER BY " . $columns[intval($orderCol)] . ' ' . $orderType;
}


$sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . " FROM $table $where $sOrder $sLimit";
$resultado = $conn->query($sql);
$num_rows = $resultado->num_rows;


$sqlFiltro = "SELECT FOUND_ROWS()";
$resFiltro = $conn->query($sqlFiltro);
$row_filtro = $resFiltro->fetch_array();
$totalFiltro = $row_filtro[0];


$sqlTotal = "SELECT count($id) FROM $table ";
$resTotal = $conn->query($sqlTotal);
$row_total = $resTotal->fetch_array();
$totalRegistros = $row_total[0];


$tasaCambio = obtenerTasaCambio();


$output = [];
$output['totalRegistros'] = $totalRegistros;
$output['totalFiltro'] = $totalFiltro;
$output['data'] = '';

if ($num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $precioEnDolares = $row['precio'] / $tasaCambio;
        $output['data'] .= '<div class="col-md-4">';
        $output['data'] .= '<div class="card mb-4">';
        $output['data'] .= '<img src="imagenes/productos/' . $row['id_producto'] . '.jpg" class="card-img-top" alt="' . $row['nombre'] . '">';
        $output['data'] .= '<div class="card-body">';
        $output['data'] .= '<h5 class="card-title">' . $row['nombre'] . '</h5>';
        $output['data'] .= '<p class="card-text">' . $row['descripcion'] . '</p>';
        $output['data'] .= '<p class="card-text"><strong>Precio:</strong> ' . $row['precio'] . ' CLP</p>';
        $output['data'] .= '<p class="card-text"><strong>Precio en USD:</strong> $' . number_format($precioEnDolares, 2) . '</p>';
        $output['data'] .= '<button class="btn btn-primary">Agregar al Carrito</button>';
        $output['data'] .= '</div></div></div>';
    }
} else {
    $output['data'] .= '<div class="col-12 text-center">';
    $output['data'] .= '<p>No hay productos en oferta en este momento.</p>';
    $output['data'] .= '</div>';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
