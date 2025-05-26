<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function addToCart($productId, $price, $quantity, $originalPrice = null) {
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = [
            'quantity' => $quantity,
            'price' => $price,
            'original_price' => $originalPrice ?? $price,
        ];
    } else {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    }
}

function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity']--;
        if ($_SESSION['cart'][$productId]['quantity'] <= 0) {
            unset($_SESSION['cart'][$productId]);
        }
    }
}

function clearCart() {
    $_SESSION['cart'] = [];
}

function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    if ($_GET['action'] === 'add' && isset($_POST['productId']) && isset($_POST['price']) && isset($_POST['quantity'])) {
        $quantity = max(1, (int)$_POST['quantity']);
        $originalPrice = isset($_POST['original_price']) ? (float)$_POST['original_price'] : null;
        addToCart($_POST['productId'], (float)$_POST['price'], $quantity, $originalPrice);
        $response = [
            'status' => 'success',
            'cartCount' => array_sum(array_column($_SESSION['cart'], 'quantity')),
        ];
        echo json_encode($response);
        exit;
    }
    if ($_GET['action'] === 'remove' && isset($_POST['productId'])) {
        removeFromCart($_POST['productId']);
        $response = [
            'status' => 'success',
            'cartCount' => array_sum(array_column($_SESSION['cart'], 'quantity')),
        ];
        echo json_encode($response);
        exit;
    }
    if ($_GET['action'] === 'clear') {
        clearCart();
        $response = [
            'status' => 'success',
            'cartCount' => 0,
        ];
        echo json_encode($response);
        exit;
    }
}
