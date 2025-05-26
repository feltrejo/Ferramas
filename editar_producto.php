<?php
session_start();
include 'header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_tipo_producto = $_POST['id_tipo_producto'];
    $es_oferta = isset($_POST['es_oferta']) ? 1 : 0;
    $descuento = $_POST['descuento'];

    
    if (!empty($_FILES['imagen']['name'])) {
        $imageName = $_FILES['imagen']['name'];
        $imageTmpName = $_FILES['imagen']['tmp_name'];
        $imageSize = $_FILES['imagen']['size'];
        $imageError = $_FILES['imagen']['error'];
        $imageType = $_FILES['imagen']['type'];

        $allowed = ['jpg'];
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        if (in_array($imageExt, $allowed)) {
            if ($imageError === 0) {
                if ($imageSize < 5000000) { 
                    $imageNewName = uniqid('', true) . "." . $imageExt;
                    $imageDestination = 'imagenes/productos/' . $imageNewName;
                    move_uploaded_file($imageTmpName, $imageDestination);

                    
                    $stmt = $conn->prepare("UPDATE producto SET nombre=?, descripcion=?, precio=?, stock=?, id_tipo_producto=?, es_oferta=?, descuento=?, imagen=? WHERE id_producto=?");
                    $stmt->bind_param("ssiiisisi", $nombre, $descripcion, $precio, $stock, $id_tipo_producto, $es_oferta, $descuento, $imageNewName, $id);
                    $stmt->execute();
                } else {
                    echo "Tu archivo es demasiado grande.";
                }
            } else {
                echo "Hubo un error subiendo tu archivo.";
            }
        } else {
            echo "Solo se permiten archivos en formato JPG.";
        }
    } else {
        
        $stmt = $conn->prepare("UPDATE producto SET nombre=?, descripcion=?, precio=?, stock=?, id_tipo_producto=?, es_oferta=?, descuento=? WHERE id_producto=?");
        $stmt->bind_param("ssiiisii", $nombre, $descripcion, $precio, $stock, $id_tipo_producto, $es_oferta, $descuento, $id);
        $stmt->execute();
    }
    header("Location: editar_producto.php?success=1");
    exit;
}


$sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, t.nombre_tipo AS tipo_producto
        FROM producto p
        JOIN tipo_producto t ON p.id_tipo_producto = t.id_tipo_producto";
$result = $conn->query($sql);
$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

?>

<main class="container mt-5">
    <h1>Editar Producto</h1>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success fade-out" id="success-message">Producto actualizado con éxito</div>
    <?php endif; ?>
    <div class="row py-4">
        <div class="col">
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID producto</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Tipo Producto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= $producto['id_producto'] ?></td>
                            <td><?= $producto['nombre'] ?></td>
                            <td><?= $producto['descripcion'] ?></td>
                            <td><?= $producto['precio'] ?></td>
                            <td><?= $producto['stock'] ?></td>
                            <td><?= $producto['tipo_producto'] ?></td>
                            <td>
                                <button class="btn btn-secondary btn-sm" onclick="editProduct(<?= $producto['id_producto'] ?>)">Editar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit-nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="edit-descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-precio" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="edit-precio" name="precio" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="edit-stock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-id_tipo_producto" class="form-label">Tipo de Producto</label>
                            <select class="form-control" id="edit-id_tipo_producto" name="id_tipo_producto" required>
                          
                            </select>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit-es_oferta" name="es_oferta">
                            <label for="edit-es_oferta" class="form-check-label">¿Es oferta?</label>
                        </div>
                        <div class="mb-3">
                            <label for="edit-descuento" class="form-label">Descuento</label>
                            <input type="number" class="form-control" id="edit-descuento" name="descuento">
                        </div>
                        <div class="mb-3">
                            <label for="edit-imagen" class="form-label">Imagen del Producto (solo JPG)</label>
                            <input type="file" class="form-control" id="edit-imagen" name="imagen">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function editProduct(id) {
  
    fetch(`get_product.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit-id').value = data.id_producto;
            document.getElementById('edit-nombre').value = data.nombre;
            document.getElementById('edit-descripcion').value = data.descripcion;
            document.getElementById('edit-precio').value = data.precio;
            document.getElementById('edit-stock').value = data.stock;
            document.getElementById('edit-id_tipo_producto').innerHTML = data.tipos_producto.map(tipo => {
                return `<option value="${tipo.id_tipo_producto}" ${data.id_tipo_producto == tipo.id_tipo_producto ? 'selected' : ''}>${tipo.nombre_tipo}</option>`;
            }).join('');
            document.getElementById('edit-es_oferta').checked = data.es_oferta == 1;
            document.getElementById('edit-descuento').value = data.descuento;

           
            new bootstrap.Modal(document.getElementById('editModal')).show();
        })
        .catch(err => console.error(err));
}
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
