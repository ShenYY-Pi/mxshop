<?php
require_once('../static/utils/allFunc.php');

try{
    cleanCart();
    echo json_encode('success');
    exit;
}catch (Exception $e){
    echo json_encode('error');
    exit;
}