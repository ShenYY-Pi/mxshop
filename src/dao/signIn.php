<?php
require_once('../static/utils/allFunc.php');
$account = postMapping('account');
$password = postMapping('password');
if (!checkLoginCnt()) {
    echo json_encode("login_cnt_error");
    exit;
}

$cap = postMapping('captcha');
$captcha_code = $_SESSION['CAPTCHA'];
if ($cap != $captcha_code) {
    echo json_encode("captcha_error");
    exit;
}

if ($account && $password && $account != '' && $password != '') {
    try {
        $sql = "select * from user where account = '$account'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result);
        if (mysqli_num_rows($result) != 1) {
            loginError();
            echo json_encode("login_error");
            exit;
        } else {
            if (password_verify($password, $row['password'])) {
                $name = '未命名';
                if ($row['name'] && $row['name'] != '') {
                    $name = $row['name'];
                }

                setLogin($row['user_id'], $row['role'], $name);
                echo json_encode('success');
                exit;
            } else {
                loginError();
                echo json_encode('login_error');
                exit;
            }
        }
    } catch (Exception $e) {
        loginError();
        echo json_encode("error");
        exit;
    }
} else {
    loginError();
    echo json_encode('error');
    exit;
}