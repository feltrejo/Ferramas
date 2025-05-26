<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Imagen de Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Subir Imagen de Producto</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="producto" class="form-label">Producto</label>
            <select name="id_producto" class="form-control" id="producto">
               
                <?php
                include 'config.php';
                $result = $conn->query("SELECT id_producto, nombre FROM producto");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id_producto']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Seleccionar Imagen</label>
            <input type="file" name="imagen" class="form-control" id="imagen" required>
        </div>
        <button type="submit" class="btn btn-primary">Subir Imagen</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
