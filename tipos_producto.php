<?php include 'header.php'; ?>
<main class="container mt-5">
    <div class="text-center">
        <h2>Tipos de Productos</h2>
    </div>
    <div class="row">
        <?php
       
        $result = $conn->query("SELECT id_tipo_producto, nombre_tipo FROM tipo_producto");
        while ($row = $result->fetch_assoc()) {
            echo '<div class="col-md-4 mb-4">';
            echo '<a href="productos.php?tipo=' . $row['id_tipo_producto'] . '" class="text-decoration-none">';
            echo '<div class="card bg-dark text-white">';
            echo '<img src="imagenes/tipo_producto' . $row['id_tipo_producto'] . '.jpg" class="card-img" alt="' . $row['nombre_tipo'] . '">';
            echo '<div class="card-img-overlay d-flex align-items-center justify-content-center">';
            echo '<h5 class="card-title">' . $row['nombre_tipo'] . '</h5>';
            echo '</div></div></a></div>';
        }
        ?>
    </div>
</main>
<footer class="footer">
    <p>&copy; 2024 Ferramas. Todos los derechos reservados.</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+8iJ6j5U4yAuENc+RTuv3I4iAzSzx" crossorigin="anonymous"></script>
</body>
</html>
