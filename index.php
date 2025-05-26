<?php include 'header.php'; ?>
<main class="container mt-5">
    <h1 class="text-center">Bienvenido a Ferramas</h1>
    <?php if (isset($_SESSION['username'])): ?>
        <p class="text-center">Hola, <?= $_SESSION['username']; ?>. Nos alegra verte de nuevo.</p>
    <?php endif; ?>
    <p class="text-center">Tu tienda de confianza para herramientas y más.</p>

    <!-- Carrusel de Fotos -->
    <div id="simpleCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="imagenes/carrusel/1.png" class="d-block w-100" alt="Primera Imagen">
            </div>
            <div class="carousel-item">
                <img src="imagenes/carrusel/2.png" class="d-block w-100" alt="Segunda Imagen">
            </div>
            <div class="carousel-item">
                <img src="imagenes/carrusel/3.png" class="d-block w-100" alt="Tercera Imagen">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#simpleCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#simpleCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    
    <section class="mt-5">
        <h2 class="text-center">Noticias y Actualizaciones</h2>
        <article class="mt-3 card">
            <div class="card-body">
                <h3 class="card-title">Nueva colección de herramientas</h3>
                <p class="card-text">Descubre nuestra última colección de herramientas lanzada este mes.</p>
            </div>
        </article>
        <article class="mt-3 card">
            <div class="card-body">
                <h3 class="card-title">Oferta especial</h3>
                <p class="card-text">
                    <?php if (isset($_SESSION['username'])): ?>
                        <a href="ofertas.php" class="btn btn-primary">Haz click para ver las ofertas</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Inicia sesión para ver las ofertas</a>
                    <?php endif; ?>
                </p>
            </div>
        </article>
    </section>

 
    <section class="mt-5 mb-5">
        <h2 class="text-center">Productos Destacados</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img src="imagenes/productos/1001.jpg" class="card-img-top" alt="Producto 1">
                    <div class="card-body">
                        <h5 class="card-title">Martillo</h5>
                        <p class="card-text">Martillo de acero con mango de madera</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="imagenes/productos/2001.jpg" class="card-img-top" alt="Producto 2">
                    <div class="card-body">
                        <h5 class="card-title">Taladro Eléctrico</h5>
                        <p class="card-text">Taladro eléctrico con múltiples velocidades brr br</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="imagenes/productos/3001.jpg" class="card-img-top" alt="Producto 3">
                    <div class="card-body">
                        <h5 class="card-title">Llave de Impacto Neumática</h5>
                        <p class="card-text">Llave de impacto con motor neumático</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<footer class="footer">
    <p>&copy; 2025 Ferramas. Todos los derechos reservados.</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+8iJ6j5U4yAuENc+RTuv3I4iAzSzx" crossorigin="anonymous"></script>
</body>
</html>
