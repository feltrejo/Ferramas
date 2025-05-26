// checkout.php
<?php
session_start();
include 'header.php';

function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

$total = calculateTotal();
$currency = isset($_POST['currency']) ? $_POST['currency'] : 'CLP';
?>
<main class="container mt-5">
    <h1>Proceso de Pago</h1>
    <p>Total a pagar: <span id="total-amount"><?= $total ?></span> <span id="total-currency"><?= $currency ?></span></p>
    <form action="https://webpay.transbank.cl/" method="POST">
        <input type="hidden" name="amount" value="<?= $total ?>" id="payment-amount">
        <input type="hidden" name="description" value="Compra en Ferramas">
        <input type="hidden" name="currency" value="<?= $currency ?>" id="payment-currency">
    
        <button type="submit" class="btn btn-success">Pagar con WebPay</button>
    </form>
</main>
<footer class="footer">
    <p>&copy; 2024 Ferramas. Todos los derechos reservados.</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const totalElement = document.getElementById('total-amount');
        const currencyElement = document.getElementById('total-currency');
        const paymentAmountInput = document.getElementById('payment-amount');
        const paymentCurrencyInput = document.getElementById('payment-currency');

        function updatePaymentForm() {
            paymentAmountInput.value = totalElement.textContent;
            paymentCurrencyInput.value = currencyElement.textContent;
        }

       
        document.getElementById('convert-to-usd').addEventListener('click', function() {
            convertTotalToUSD();
            updatePaymentForm();
        });

        document.getElementById('convert-to-clp').addEventListener('click', function() {
            convertTotalToCLP();
            updatePaymentForm();
        });

        updatePaymentForm();
    });
</script>

</body>
</html>
