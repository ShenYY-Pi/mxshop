<?php
require_once('../static/utils/allFunc.php');

$id = postMapping('id');

if ($id) {
    $id=(int)$id;
    if (removeCart($id)) {
        echo 'success';
        exit;
    } else {
        echo 'error';
        exit;
    }
}