<?php
function checkGet($key)
{
    if (isset($_GET[$key]) && $_GET[$key]) {
        return true;
    } else {
        return false;
    }
}

function checkPost($key)
{
    if (isset($_POST[$key]) && $_POST[$key]) {
        return true;
    } else {
        return false;
    }
}

function getMapping($key)
{
    if (checkGet($key)) {
        return $_GET[$key];
    }else{
        return null;
    }
}

function postMapping($key)
{
    if (checkPost($key)) {
        return $_POST[$key];
    }else{
        return null;
    }
}