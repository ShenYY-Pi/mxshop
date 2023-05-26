<?php
require_once('../static/utils/allFunc.php');
$account = postMapping('account');
$password = postMapping('password');


$cap = postMapping('captcha');
$captcha_code = $_SESSION['CAPTCHA'];
if ($cap != $captcha_code) {
    echo json_encode("captcha_error");
    exit;
}

if ($account && $password && $account != '' && $password != '') {
    try {
        $sql = "select * from user where account = '$account'";
        $result = mysqli_query($link,$sql);
        if(mysqli_fetch_row($result)>0){
            echo json_encode('user_undefined');
            exit;
        }
        $now = date("Y-m-d H:i:s", strtotime('now'));
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "insert into user (account,password,created_time,role,status) values('$account','$password','$now',1,1)";
        $result = mysqli_query($link, $sql);
        if($result){
            echo json_encode("success");
            exit;
        }else{
            echo json_encode("error");
        }
    } catch (Exception $e) {
        echo json_encode("error");
        exit;
    }
} else {
    echo json_encode('error');
}