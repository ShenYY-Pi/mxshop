<?php
require_once('../static/utils/allFunc.php');

try {
    $userId = (int)getUserId();

    $sql = "select * from orders where user_id = '$userId' order by order_time desc";
    $result = mysqli_query($link, $sql);
    $arr = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $orderId=(int)$row['order_id'];
        $sql2 = "select product.product_id,name,price,order_product.number from order_product,product where order_product.product_id = product.product_id and order_id = '$orderId'";
        $result2 = mysqli_query($link,$sql2);
        $products = array();
        while($item = mysqli_fetch_assoc($result2)){
            array_push($products,$item);
        }
        $row['products']=$products;
        array_push($arr, $row);
    }
    if($result){
        echo json_encode($arr);
        exit;
    }else{
        echo json_encode("error");
        exit;
    }
} catch (Exception $e) {
    echo json_encode("error");
    exit;
}

