<?php
require_once('../static/utils/allFunc.php');
if(!checkLogin()){
    echo json_encode('not_login');
    exit;
}
try {
    $id = postMapping('id');
    $num = postMapping('num');
    $name = postMappIng('name');
    $price = postMapping('price');
    $id = (int)$id;
    $num = (int)$num;
    addCart($id,$name,$price, $num);
    echo json_encode('success');
    exit;
}catch (Exception $e){
    echo json_encode('error');
    exit;
}