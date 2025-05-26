<?php
require_once 'vendor/autoload.php';
use Transbank\Webpay\WebpayPlus\Transaction;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "POST request received<br>";
    $token = $_POST["token_ws"];
} else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["token_ws"])) {
    echo "GET request received<br>";
    $token = $_GET["token_ws"];
} else {
    echo "No valid request received";
    exit();
}

echo "Token received: " . htmlspecialchars($token) . "<br>";

try {
    $webpay = new Transaction();
    $response = $webpay->commit($token);
    echo "Response received: <br>";
    var_dump($response);

    if ($response->isApproved()) {
        header("Location: exito.php");
        exit();
    } else {
        header("Location: error.php");
        exit();
    }
} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
    error_log("Exception occurred: " . $e->getMessage());
}
?>
