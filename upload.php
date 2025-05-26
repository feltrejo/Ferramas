<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_producto = intval($_POST['id_producto']);
    $imagen = $_FILES['imagen'];

    
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
    $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
    if (!in_array($extension, $extensionesPermitidas)) {
        die("Tipo de archivo no permitido.");
    }

   
    $nombreArchivo = 'imagenes/' . basename($imagen['name']);
    if (move_uploaded_file($imagen['tmp_name'], $nombreArchivo)) {
        
        $query = $conn->prepare("UPDATE producto SET imagen = ? WHERE id_producto = ?");
        $query->bind_param("si", $nombreArchivo, $id_producto);
        if ($query->execute()) {
            echo "Imagen subida y ruta actualizada correctamente.";
        } else {
            echo "Error al actualizar la ruta en la base de datos.";
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>
