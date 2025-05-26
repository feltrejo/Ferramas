<?php
session_start();
include 'header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM producto WHERE id_producto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: eliminar_producto.php?success=1");
    exit;
}


$productos = $conn->query("SELECT id_producto, nombre FROM producto");
?>

<main class="container mt-5">
    <h1>Eliminar Producto</h1>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success fade-out" id="success-message">Producto eliminado con éxito</div>
    <?php endif; ?>

    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="Buscar por ID o Nombre">
    </div>

    <form method="POST" action="eliminar_producto.php" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este producto?')">
        <div class="mb-3">
            <label for="id" class="form-label">Seleccionar Producto</label>
            <select class="form-control" id="product-list" name="id" required>
                <?php while ($producto = $productos->fetch_assoc()): ?>
                    <option value="<?= $producto['id_producto'] ?>"><?= $producto['id_producto'] ?> - <?= $producto['nombre'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-danger">Eliminar Producto</button>
    </form>
</main>

<footer class="footer">
    <p>&copy; 2024 Ferramas. Todos los derechos reservados.</p>
</footer>
<script>
    document.getElementById('search').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        fetch("search_products.php?query=" + filter)
            .then(response => response.json())
            .then(data => {
                let options = '';
                data.forEach(product => {
                    options += `<option value="${product.id_producto}">${product.id_producto} - ${product.nombre}</option>`;
                });
                document.getElementById('product-list').innerHTML = options;
            });
    });

   
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
