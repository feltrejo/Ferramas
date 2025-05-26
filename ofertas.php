<?php include 'header.php'; ?>
<?php
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'user' && $_SESSION['role'] != 'admin')) {
    header("Location: index.php");
    exit;
}
?>
<main class="container mt-5">
    <div class="text-center">
        <h2>Ofertas</h2>
    </div>
    <div class="row" id="offer-list">
       
    </div>
</main>
<script>
    document.addEventListener("DOMContentLoaded", getOffers);

    function getOffers() {
        let formaData = new FormData();
        formaData.append('es_oferta', 1);
        fetch("load.php", {
            method: "POST",
            body: formaData
        })
        .then(response => response.json())
        .then(data => {
            let offerList = document.getElementById("offer-list");
            let cards = data.data.map(producto => {
                let precioOriginal = Math.floor(producto.precio);
                let precioDescuento = Math.floor(producto.precio_con_descuento);

                return `<div class="col-md-4 d-flex align-items-stretch">
                            <div class="card mb-4">
                                <img src="${producto.image_url}" class="card-img-top" alt="${producto.nombre}">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">${producto.nombre}</h5>
                                    <p class="card-text flex-grow-1">${producto.descripcion}</p>
                                    <p class="card-text">
                                        <strong>Precio:</strong> $${precioOriginal}
                                        ${precioOriginal != precioDescuento ? `<br><strong class="text-danger">Con descuento: $${precioDescuento}</strong>` : ''}
                                    </p>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="quantity-${producto.id_producto}" value="1" min="1">
                                        <button class="btn btn-primary" onclick="addToCart(${producto.id_producto}, ${precioDescuento})">Agregar al Carrito</button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
            }).join('');
            offerList.innerHTML = cards;
        })
        .catch(err => console.log(err));
    }

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
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
