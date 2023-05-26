<?php
require_once('../static/utils/allFunc.php');
$name = postMapping('name');
$id = getUserId();
if ($name != null && $name != '' && $id != null && $id != '') {
    try {
        $sql = "update user set name = '$name' where user_id = '$id'";
        $result = mysqli_query($link, $sql);
        if ($result) {
            setUserName($name);
            echo json_encode("success");
            exit;
        } else {
            echo json_encode('error');
            exit;
        }
    } catch (Exception $e) {
        echo json_encode("error");
        exit;
    }
} else {
    echo json_encode("error");
    exit;
}