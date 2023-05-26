<?php
require_once('../static/utils/allFunc.php');

try {
    $tagName = postMapping('name');
    if(!$tagName){
        echo 'error';
        exit;
    }
    $sql = "insert into product_classify (name) values('$tagName')";
    $result = mysqli_query($link, $sql);

    echo 'success';
    exit;
} catch (Exception $e) {
    echo 'error';
    exit;
}