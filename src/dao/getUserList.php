<?php

require_once('../static/utils/allFunc.php');
try {
    $sql = "select user_id,account,name,created_time,role from user where status = 1";
    $result = mysqli_query($link, $sql);

    $arr=array();
    while($row = mysqli_fetch_assoc($result)){
        array_push($arr,$row);
    }

    echo json_encode($arr);
    exit;
} catch (Exception $e) {
    echo json_encode("error");
    exit;
}