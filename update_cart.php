<?php
session_start(); 

$itemId = $_POST['item_id'];
$action = $_POST['action'];

if (!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if ($action === 'increase'){
    $_SESSION['cart'][$itemId]++;
}elseif ($action === 'decrease'){
    $_SESSION['cart'][$itemId]--;
    if ($_SESSION['cart'][$itemId] <=0){
        unset ($_SESSION['cart'][$itemId]);
    }
}

echo json_encode($_SESSION['cart']);
?>