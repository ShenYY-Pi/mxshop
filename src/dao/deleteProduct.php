<?php
require_once('../static/utils/allFunc.php');

try {
    $productId = postMapping('id');
    $productId = (int)$productId;
    $sql = "delete from product where product_id = '$productId'";
    $result = mysqli_query($link, $sql);

    echo 'success';
    exit;
} catch (Exception $e) {
    echo 'error';
    exit;
}