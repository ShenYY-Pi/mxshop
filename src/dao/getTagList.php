<?php

require_once('../static/utils/allFunc.php');
try {
    $sql = "select product_classify_id id,name from product_classify";
    $result = mysqli_query($link, $sql);
    $arr = array();
    while($row = mysqli_fetch_assoc($result)){
        array_push($arr,$row);
    }
    echo json_encode($arr);
    exit;
} catch (Exception $e) {
    loginError();
    echo json_encode("error");
    exit;
}