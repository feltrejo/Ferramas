<?php
if (isset($_GET['token']) && isset($_GET['url'])) {
    $token = $_GET['token'];
    $url = $_GET['url'];
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Pagar con Webpay</title>
    </head>
    <body>
        <form action="<?php echo $url; ?>" method="POST">
            <input type="hidden" name="token_ws" value="<?php echo $token; ?>"/>
            <input type="submit" value="Pagar"/>
        </form>
    </body>
    </html>
    <?php
} else {
    echo "Error: no se pudo iniciar la transacción.";
}
?>
