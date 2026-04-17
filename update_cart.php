<?php
session_start(); 

// get the item id and action from the form
$itemId = $_POST['item_id'];
$action = $_POST['action'];

// initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// update the quantity based on the action
if ($action === 'increase'){
    $_SESSION['cart'][$itemId]++;
}elseif ($action === 'decrease'){
    $_SESSION['cart'][$itemId]--;
    if ($_SESSION['cart'][$itemId] <=0){
        unset ($_SESSION['cart'][$itemId]);
    }
}

// return the updated cart as JSON
echo json_encode($_SESSION['cart']);
?>