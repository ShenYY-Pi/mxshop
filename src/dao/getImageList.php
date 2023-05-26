<?php

require_once('../static/utils/allFunc.php');
$productId = getMapping('productId');
if ($productId != null) {
    try {
        $productId = (int)$productId;
        $sql = "select image.image_id,image_url from image,product_img where image.image_id = product_img.image_id and product_id = '$productId'";
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