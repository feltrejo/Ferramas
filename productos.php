<?php include 'header.php'; ?>
<main class="container mt-5">
    <div class="text-center">
        <h2>Productos</h2>
    </div>
    <div class="row mb-3">
        <div class="col-6 col-md-3">
            <input type="text" name="campo" id="campo" class="form-control" placeholder="Buscar productos">
        </div>
        <div class="col-auto">
            <button id="btn-limpiar" class="btn btn-secondary">Limpiar</button>
        </div>
    </div>
    <div class="row" id="product-list">
     
    </div>
    <div class="row justify-content-between">
        <div class="col-12">
            <label id="lbl-total"></label>
        </div>
    </div>
    <div class="row justify-content-center" id="nav-paginacion">
       
    </div>
    <input type="hidden" id="pagina" value="1">
    <input type="hidden" id="orderCol" value="0">
    <input type="hidden" id="orderType" value="asc">
</main>
<script>
    document.addEventListener("DOMContentLoaded", getData);

    function getData() {
        let input = document.getElementById("campo").value;
        let pagina = document.getElementById("pagina").value || 1;
        let orderCol = document.getElementById("orderCol").value;
        let orderType = document.getElementById("orderType").value;
        let formaData = new FormData();
        formaData.append('campo', input);
        formaData.append('pagina', pagina);
        formaData.append('orderCol', orderCol);
        formaData.append('orderType', orderType);
        formaData.append('registros', 50); 
        fetch("load.php", {
            method: "POST",
            body: formaData
        })
        .then(response => response.json())
        .then(data => {
            let productList = document.getElementById("product-list");
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
            productList.innerHTML = cards;
            document.getElementById("lbl-total").innerHTML = `Mostrando ${data.totalFiltro} de ${data.totalRegistros} registros`;
            document.getElementById("nav-paginacion").innerHTML = data.paginacion;
            document.querySelectorAll("#nav-paginacion button").forEach(button => {
                button.addEventListener("click", function() {
                    changePage(this.innerText);
                });
            });
        })
        .catch(err => console.log(err));
    }

    function changePage(page) {
        document.getElementById("pagina").value = page;
        getData();
    }

    document.getElementById("campo").addEventListener("input", function() {
        getData();
    });

    document.getElementById("btn-limpiar").addEventListener("click", function() {
        document.getElementById("campo").value = '';
        document.getElementById("pagina").value = 1;
        getData();
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
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
