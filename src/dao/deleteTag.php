<?php
require_once('../static/utils/allFunc.php');

try {
    $id = postMapping('id');
    $sql = "delete from product_classify where product_classify_id = '$id'";
    $result = mysqli_query($link, $sql);

    $sql = "update product set product_classify_id = null  where product_classify_id = '$id'";
    $result = mysqli_query($link, $sql);
    echo 'success';
    exit;
} catch (Exception $e) {
    echo 'error';
    exit;
}