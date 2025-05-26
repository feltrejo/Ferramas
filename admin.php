<?php include 'header.php'; ?>
<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}
?>
<main class="container mt-5 text-center">
    <h1>Gestor de Productos</h1>
    <div class="d-flex justify-content-center mb-4">
        <a href="ingresar_producto.php" class="btn btn-success mx-2">Ingresar Productos</a>
        <a href="editar_producto.php" class="btn btn-warning mx-2">Editar Productos</a>
        <a href="eliminar_producto.php" class="btn btn-danger mx-2">Eliminar Productos</a>
    </div>
</main>

<footer class="footer">
    <p>&copy; 2024 Ferramas. Todos los derechos reservados.</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
