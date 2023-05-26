<?php
require_once('../static/utils/allFunc.php');

try {
    $productId = postMapping('product_id');
    $productId = (int)$productId;
    $productClassifyId = postMapping('product_classify_id');
    $name = postMapping('name');
    $price = postMapping('price');
    $price = (double)$price;
    $number = postMapping('number');
    $number = (int)$number;
    $content = postMapping('content');
    $image = postMapping('image');
    $big_image = postMapping('big_image');

    $sql = "update product set product_classify_id = '$productClassifyId' ,name='$name',price='$price',number='$number',content='$content',image='$image',big_image='$big_image' where product_id = '$productId'";
    $result = mysqli_query($link, $sql);
    if ($result) {
        echo 'success';
        exit;
    } else {
        echo 'error';
        exit;
    }
} catch (Exception $e) {
    echo 'error';
    exit;
}