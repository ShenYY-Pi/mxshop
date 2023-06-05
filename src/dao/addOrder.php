<?php
require_once('../static/utils/allFunc.php');
$cart = postMapping('cart');
$cart = json_decode($cart);
//添加订单表信息
try {
    $now = date("Y-m-d H:i:s", strtotime('now'));
    $userId = getUserId();
    $price = 0;
    //
    foreach ($cart as $val){
        $id = $val->id;
        $num = $val->num;

        $sql = "select number from product where product_id = '$id'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);
        $proNum = $row['number'];
        $proNum=(int)$proNum;
        $num=(int)$num;
        $proNum -= $num;
        if($proNum<0){
            echo json_encode('not_enough');
            exit;
        }
    }


    foreach($cart as $val){
        $price += $val->price * $val->num;
    }
    $sql = "insert into orders (user_id,order_time,origin_price,status) values('$userId','$now','$price',1)";
    $result = mysqli_query($link, $sql);
    //生成的订单id
    $getID=(int)$link->insert_id;

    //添加订单明细
    foreach ($cart as $val){
        $id = $val->id;
        $num = $val->num;

        $sql = "select number from product where product_id = '$id'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);
        $proNum = $row['number'];
        $proNum=(int)$proNum;
        $num=(int)$num;
        $proNum -= $num;
        if($proNum<=0){
            echo json_encode('not_enough');
            exit;
        }
        $sql = "update product set number = '$proNum' where product_id = '$id'";
        $result = mysqli_query($link, $sql);


        $sql = "insert into order_product (order_id,product_id,number) values('$getID','$id','$num')";
        $result = mysqli_query($link, $sql);
    }
    if($result){
        cleanCart();
        echo json_encode("success");
        exit;
    }else{
        echo json_encode("error");
        exit;
    }
} catch (Exception $e) {
    echo json_encode("error");
    exit;
}