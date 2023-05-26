<?php
require_once('../static/utils/allFunc.php');

try {
    $tagId = postMapping('id');
    $tagId = (int)$tagId;
    $tagName = postMapping('name');
    if (!$tagName) {
        echo 'error';
        exit;
    }
    $sql = "update product_classify set name = '$tagName' where product_classify_id = '$tagId'";
    $result = mysqli_query($link, $sql);

    echo 'success';
    exit;
} catch (Exception $e) {
    echo 'error';
    exit;
}