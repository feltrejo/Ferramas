<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';

$tipo_producto = isset($_GET['tipo']) ? intval($_GET['tipo']) : '';
$categoria_query = $conn->prepare("SELECT nombre_tipo FROM tipo_producto WHERE id_tipo_producto = ?");
$categoria_query->bind_param("i", $tipo_producto);
$categoria_query->execute();
$categoria_result = $categoria_query->get_result();
$categoria = $categoria_result->fetch_assoc();

$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']) : '';
$query = $conn->prepare("
    SELECT p.id_producto, p.precio, p.nombre, p.descripcion, p.stock, p.descuento,
           CASE WHEN p.es_oferta = 1 THEN p.precio - (p.precio * p.descuento / 100) ELSE p.precio END AS precio_con_descuento
    FROM producto p
    JOIN tipo_producto t ON p.id_tipo_producto = t.id_tipo_producto
    WHERE p.id_tipo_producto = ? AND (p.nombre LIKE ? OR p.descripcion LIKE ? OR t.nombre_tipo LIKE ?)
");
$searchTerm = "%{$campo}%";
$query->bind_param("isss", $tipo_producto, $searchTerm, $searchTerm, $searchTerm);
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categoría de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<?php include 'header.php'; ?> 
<div class="container">
    <h1 class="mt-5">Categoría: <span id="categoria-nombre"><?= htmlspecialchars($categoria['nombre_tipo']) ?></span></h1>
    <form method="post" class="mt-3 d-flex">
        <input type="text" name="campo" class="form-control me-2" id="search-field" placeholder="Buscar productos...">
        <button type="button" class="btn btn-secondary" onclick="clearSearch()">Limpiar</button>
    </form>
    <div id="product-list" class="row mt-3">
        
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 d-flex align-items-stretch">
                    <div class="card mb-4">
                        <img src="imagenes/productos/<?= htmlspecialchars($row['id_producto']) ?>.jpg" class="card-img-top" alt="<?= htmlspecialchars($row['nombre']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
                            <p class="card-text flex-grow-1"><?= htmlspecialchars($row['descripcion']) ?></p>
                            <p class="card-text">
                                <strong>Precio:</strong> $<?= htmlspecialchars($row['precio']) ?>
                                <?php if ($row['precio'] != $row['precio_con_descuento']): ?>
                                    <br><strong class="text-danger">Con descuento: $<?= htmlspecialchars($row['precio_con_descuento']) ?></strong>
                                <?php endif; ?>
                            </p>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" id="quantity-<?= $row['id_producto'] ?>" value="1" min="1">
                                <button class="btn btn-primary" onclick="addToCart(<?= $row['id_producto'] ?>, <?= htmlspecialchars($row['precio_con_descuento']) ?>)">Agregar al Carrito</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="mt-3">No hay productos disponibles en esta categoría.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('search-field').addEventListener('input', function() {
    fetch('buscar_productos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'campo=' + encodeURIComponent(this.value) + '&tipo=' + encodeURIComponent('<?= $tipo_producto ?>')
    })
    .then(response => response.json())
    .then(data => {
        const productList = document.getElementById('product-list');
        productList.innerHTML = '';
        if (data.length > 0) {
            data.forEach(producto => {
                productList.innerHTML += `
                    <div class="col-md-4 d-flex align-items-stretch">
                        <div class="card mb-4">
                            <img src="imagenes/productos/${producto.id_producto}.jpg" class="card-img-top" alt="${producto.nombre}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${producto.nombre}</h5>
                                <p class="card-text flex-grow-1">${producto.descripcion}</p>
                                <p class="card-text">
                                    <strong>Precio:</strong> $${producto.precio}
                                    ${producto.precio != producto.precio_con_descuento ? `<br><strong class="text-danger">Con descuento: $${producto.precio_con_descuento}</strong>` : ''}
                                </p>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" id="quantity-${producto.id_producto}" value="1" min="1">
                                    <button class="btn btn-primary" onclick="addToCart(${producto.id_producto}, ${producto.precio_con_descuento})">Agregar al Carrito</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            productList.innerHTML = '<p class="mt-3">No hay productos disponibles en esta categoría.</p>';
        }
    })
    .catch(error => console.error('Error:', error));
});

function addToCart(productId, price) {
    let quantity = document.getElementById(`quantity-${productId}`).value;
    let formData = new FormData();
    formData.append('productId', productId);
    formData.append('price', price);
    formData.append('quantity', quantity);
    fetch('cart.php?action=add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('cart-count').innerText = data.cartCount;
            document.getElementById(`quantity-${productId}`).value = 1;  
        } else {
            console.error('Error adding to cart');
        }
    })
    .catch(error => console.error('Error:', error));
}

function clearSearch() {
    document.getElementById('search-field').value = '';
    document.getElementById('product-list').innerHTML = '<?php foreach ($result as $row): ?><div class="col-md-4 d-flex align-items-stretch"><div class="card mb-4"><img src="imagenes/productos/<?= htmlspecialchars($row["id_producto"]) ?>.jpg" class="card-img-top" alt="<?= htmlspecialchars($row["nombre"]) ?>"><div class="card-body d-flex flex-column"><h5 class="card-title"><?= htmlspecialchars($row["nombre"]) ?></h5><p class="card-text flex-grow-1"><?= htmlspecialchars($row["descripcion"]) ?></p><p class="card-text"><strong>Precio:</strong> $<?= htmlspecialchars($row["precio"]) ?><?php if ($row["precio"] != $row["precio_con_descuento"]): ?><br><strong class="text-danger">Con descuento: $<?= htmlspecialchars($row["precio_con_descuento"]) ?></strong><?php endif; ?></p><div class="input-group mb-3"><input type="number" class="form-control" id="quantity-<?= $row["id_producto"] ?>" value="1" min="1"><button class="btn btn-primary" onclick="addToCart(<?= $row["id_producto"] ?>, <?= htmlspecialchars($row["precio_con_descuento"]) ?>)">Agregar al Carrito</button></div></div></div></div><?php endforeach; ?>';
}
</script>
</body>
</html>
