<?php
require_once('../static/utils/allFunc.php');

$id = postMapping('id');
$num = postMapping('num');

if ($id && $num) {
    $id=(int)$id;
    $num=(int)$num;
    if (setCartNum($id, $num)) {
        echo 'success';
        exit;
    } else {
        echo 'error';
        exit;
    }
}