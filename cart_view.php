<?php
session_start();
include 'header.php';
include 'config.php';

function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

function getProductName($productId) {
    global $conn;
    $result = $conn->query("SELECT nombre FROM producto WHERE id_producto = $productId");
    $row = $result->fetch_assoc();
    return $row['nombre'];
}

function getOriginalPrice($productId) {
    global $conn;
    $result = $conn->query("SELECT precio FROM producto WHERE id_producto = $productId");
    $row = $result->fetch_assoc();
    return $row['precio'];
}
?>

<main class="container mt-5">
    <h1>Carrito de Compras</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($_SESSION['cart'] as $id => $item):
                $originalPrice = getOriginalPrice($id);
                $discounted = $item['price'] < $originalPrice;
            ?>
                <tr>
                    <td><?= getProductName($id) ?></td>
                    <td>
                        <?= $item['price'] ?> CLP
                        <?php if ($discounted): ?>
                            <br><small><del><?= $originalPrice ?> CLP</del></small>
                        <?php endif; ?>
                    </td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= $item['price'] * $item['quantity'] ?> CLP</td>
                    <td>
                        <button class="btn btn-danger" onclick="removeFromCart(<?= $id ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td id="total" data-total="<?= calculateTotal() ?>" data-currency="CLP"><?= calculateTotal() ?> CLP</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" onclick="window.location.href='productos.php'">Seguir Comprando</button>
        <button class="btn btn-warning" onclick="clearCart()">Vaciar Carrito</button>
        <form id="payment-form" action="process_payment.php" method="POST">
            <input type="hidden" name="amount" id="payment-amount" value="<?= calculateTotal() ?>">
            <input type="hidden" name="currency" id="payment-currency" value="CLP">
            <button class="btn btn-success" type="button" onclick="proceedToPayment()">Ir al Pago</button>
        </form>
    </div>
    <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-info" id="convert-to-usd">Convertir a USD</button>
        <button class="btn btn-info" id="convert-to-clp">Convertir a CLP</button>
    </div>
</main>

<footer class="footer">
    <p>&copy; 2024 Ferramas. Todos los derechos reservados.</p>
</footer>

<script>
    function removeFromCart(productId) {
        let formData = new FormData();
        formData.append('productId', productId);
        fetch('cart.php?action=remove', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                console.error('Error removing from cart');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function clearCart() {
        fetch('cart.php?action=clear', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                console.error('Error clearing cart');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function proceedToPayment() {
        const isLoggedIn = <?= isset($_SESSION['username']) ? 'true' : 'false' ?>;
        const totalElement = document.getElementById('total');
        const totalAmount = parseFloat(totalElement.dataset.total);
        const currency = totalElement.dataset.currency;
        if (!isLoggedIn) {
            alert('Por favor, inicie sesión para proceder al pago.');
        } else if (totalAmount === 0) {
            alert('El carrito está vacío. Añade productos antes de proceder al pago.');
        } else {
            let amountForPayment = currency === 'USD' ? Math.floor(totalAmount * 100) : totalAmount.toString().split('.')[0];
            document.getElementById('payment-amount').value = amountForPayment;
            document.getElementById('payment-currency').value = currency;
            document.getElementById('payment-form').submit();
        }
    }

    async function convertTotalToUSD() {
        const totalElement = document.getElementById('total');
        if (totalElement.dataset.currency === 'USD') {
            return; // Already in USD, do nothing
        }
        const response = await fetch('https://api.exchangerate-api.com/v4/latest/CLP');
        const data = await response.json();
        const rate = data.rates.USD;
        const totalCLP = parseFloat(totalElement.dataset.total);
        const totalUSD = totalCLP * rate;
        totalElement.textContent = totalUSD.toFixed(2) + " USD";
        totalElement.dataset.currency = 'USD';
        totalElement.dataset.total = totalUSD.toFixed(2); 
        document.getElementById('payment-amount').value = totalUSD.toFixed(2);
        document.getElementById('payment-currency').value = 'USD';
    }

    async function convertTotalToCLP() {
        const totalElement = document.getElementById('total');
        if (totalElement.dataset.currency === 'CLP') {
            return; 
        }
        const response = await fetch('https://api.exchangerate-api.com/v4/latest/USD');
        const data = await response.json();
        const rate = data.rates.CLP;
        const totalUSD = parseFloat(totalElement.dataset.total);
        const totalCLP = totalUSD * rate;
        totalElement.textContent = totalCLP.toFixed(0) + " CLP"; 
        totalElement.dataset.currency = 'CLP';
        totalElement.dataset.total = totalCLP.toFixed(0);  
        document.getElementById('payment-amount').value = totalCLP.toFixed(0);
        document.getElementById('payment-currency').value = 'CLP';
    }

    document.getElementById('convert-to-usd').addEventListener('click', convertTotalToUSD);
    document.getElementById('convert-to-clp').addEventListener('click', convertTotalToCLP);
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
