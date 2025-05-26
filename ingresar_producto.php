<?php
session_start();
include 'header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_tipo_producto = $_POST['id_tipo_producto'];
    $es_oferta = isset($_POST['es_oferta']) ? 1 : 0;
    $descuento = $_POST['descuento'];


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

               
                $result = $conn->query("SELECT MAX(id_producto) AS max_id FROM producto WHERE id_tipo_producto = $id_tipo_producto");
                $row = $result->fetch_assoc();
                $max_id = $row['max_id'];
                $new_id = $max_id ? $max_id + 1 : ($id_tipo_producto * 1000) + 1;  

                
                $stmt = $conn->prepare("INSERT INTO producto (id_producto, nombre, descripcion, precio, stock, id_tipo_producto, es_oferta, descuento, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issiiisis", $new_id, $nombre, $descripcion, $precio, $stock, $id_tipo_producto, $es_oferta, $descuento, $imageNewName);
                $stmt->execute();

                header("Location: ingresar_producto.php?success=1");
                exit;
            } else {
                echo "Tu archivo es demasiado grande.";
            }
        } else {
            echo "Hubo un error subiendo tu archivo.";
        }
    } else {
        echo "Solo se permiten archivos en formato JPG.";
    }
}
?>

<main class="container mt-5">
    <h1>Ingresar Nuevo Producto</h1>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success fade-out" id="success-message">Producto ingresado con éxito</div>
    <?php endif; ?>

    <form method="POST" action="ingresar_producto.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>
        <div class="mb-3">
            <label for="id_tipo_producto" class="form-label">Tipo de Producto</label>
            <select class="form-control" id="id_tipo_producto" name="id_tipo_producto" required>
                <?php
                $tipos_result = $conn->query("SELECT id_tipo_producto, nombre_tipo FROM tipo_producto");
                while ($tipo = $tipos_result->fetch_assoc()) {
                    echo "<option value='{$tipo['id_tipo_producto']}'>{$tipo['nombre_tipo']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="es_oferta" name="es_oferta">
            <label for="es_oferta" class="form-check-label">¿Es oferta?</label>
        </div>
        <div class="mb-3">
            <label for="descuento" class="form-label">Descuento</label>
            <input type="number" class="form-control" id="descuento" name="descuento">
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Producto (solo JPG)</label>
            <input type="file" class="form-control" id="imagen" name="imagen" required>
        </div>
        <button type="submit" class="btn btn-primary">Ingresar Producto</button>
    </form>
</main>

<footer class="footer mt-5">
    <p>&copy; 2024 Ferramas. Todos los derechos reservados.</p>
</footer>
<script>
    
    setTimeout(function() {
        let message = document.getElementById('success-message');
        if (message) {
            message.classList.add('hide');
        }
    }, 3000);
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
