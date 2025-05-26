<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    
    $result = $conn->query("SELECT * FROM producto WHERE id_producto = $id");
    $product = $result->fetch_assoc();

    
    $tipos_result = $conn->query("SELECT id_tipo_producto, nombre_tipo FROM tipo_producto");
    $tipos_producto = [];
    while ($tipo = $tipos_result->fetch_assoc()) {
        $tipos_producto[] = $tipo;
    }

   
    echo json_encode([
        'id_producto' => $product['id_producto'],
        'nombre' => $product['nombre'],
        'descripcion' => $product['descripcion'],
        'precio' => $product['precio'],
        'stock' => $product['stock'],
        'id_tipo_producto' => $product['id_tipo_producto'],
        'es_oferta' => $product['es_oferta'],
        'descuento' => $product['descuento'],
        'tipos_producto' => $tipos_producto
    ]);
}
?>
