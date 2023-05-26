<?php
require_once('../static/utils/allFunc.php');

$cart = getCart();
if(is_array($cart)) {
    echo json_encode($cart);
    exit;
}else{
    echo json_encode('error');
    exit;
}