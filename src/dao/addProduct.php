<?php
require_once('../static/utils/allFunc.php');

try {
    $productClassifyId = postMapping('product_classify_id');
    $name = postMapping('name');
    $price = postMapping('price');
    $price = (double)$price;
    $number = postMapping('number');
    $number = (int)$number;
    $content = postMapping('content');
    $image = postMapping('image');
    $big_image = postMapping('big_image');


    $sql = "insert into product (product_classify_id,name,price,number,status,content,image,big_image) values('$productClassifyId','$name','$price','$number',1,'$content','$image','$big_image')";
    $result = mysqli_query($link, $sql);

    echo 'success';
    exit;
} catch (Exception $e) {
    echo 'error';
    exit;
}