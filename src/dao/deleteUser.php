<?php
require_once('../static/utils/allFunc.php');

try {
    $id = postMapping('id');
    $sql = "delete from user where user_id = '$id'";
    $result = mysqli_query($link, $sql);

    echo 'success';
    exit;
} catch (Exception $e) {
    echo 'error';
    exit;
}