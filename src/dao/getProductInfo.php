<?php

require_once('../static/utils/allFunc.php');
$productId = getMapping('productId');
if ($productId != null) {
    try {
        $productId = (int)$productId;
        $sql = "select name,price,number,content,big_image from product where product_id = '$productId'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);
        echo json_encode($row);
        exit;
    } catch (Exception $e) {
        echo json_encode("error");
        exit;
    }
} else {
    echo json_encode("error");
    exit;
}