<?php

require_once('../static/utils/allFunc.php');
$tagId = getMapping('tagId');
if ($tagId != null) {
    try {
        $tagId = (int)$tagId;
        $sql = "select * from product where product_classify_id = '$tagId' and status = 1";
        if ($tagId === -1) {
            $sql = "select * from product where product_classify_id is null and status = 1";
        }
        $result = mysqli_query($link, $sql);
        $arr = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($arr, $row);
        }
        echo json_encode($arr);
        exit;
    } catch (Exception $e) {
        echo json_encode("error");
        exit;
    }
} else {
    echo json_encode("error");
    exit;
}