<?php

require_once 'vendor/autoload.php';

use Transbank\Webpay\WebpayPlus\Transaction;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST["amount"];
    $currency = $_POST["currency"];
    $buyOrder = 'orden-' . rand();
    $sessionId = session_id();
    $returnUrl = 'http://localhost/ferramas/retorno.php'; 

    try {
        $webpay = new Transaction();
        $response = $webpay->create($buyOrder, $sessionId, $amount, $returnUrl);

        $token = $response->getToken();
        $url = $response->getUrl();

   
        echo "<html><body onload=\"document.forms['payment'].submit()\">
        <form name=\"payment\" action=\"$url\" method=\"POST\">
            <input type=\"hidden\" name=\"token_ws\" value=\"$token\" />
            <input type=\"hidden\" name=\"amount\" value=\"$amount\" />
        </form>
      </body></html>";

        exit();
    } catch (Exception $e) {
        echo 'Error: ',  $e->getMessage(), "\n";
    }
}
?>
