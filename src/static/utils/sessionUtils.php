<?php
function checkSession($key)
{
    if (isset($_SESSION[$key]) && $_SESSION[$key] && $_SESSION[$key] != '') {
        return true;
    } else {
        return false;
    }
}

function getSession($key)
{
    if (checkSession($key)) {
        return $_SESSION[$key];
    } else {
        return false;
    }
}

function setSession($key, $val)
{
    $_SESSION[$key] = $val;
}

function getUserName()
{
    return getSession('USER_NAME');
}

function setUserName($val)
{
    setSession('USER_NAME', $val);
}

function getUserId()
{
    return getSession('USER_ID');
}

function getUserRole()
{
    return getSession('USER_ROLE');
}

function cleanCart()
{
    $_SESSION['CART'] = array();
}

function getCart()
{
    $arr = array();
    if (is_array($_SESSION['CART'])) {
        foreach ($_SESSION['CART'] as $val) {
            array_push($arr, $val);
        }
    }
    return $arr;
}

function setCartNum($id, $num)
{
    $arr = getCart();
    if (is_array($arr)) {
        foreach ($arr as $key => $row) {
            if ($row['id'] === $id) {
                $arr[$key]['num'] = $num;
                setSession('CART', $arr);
                return true;
            }
        }
    }
    return false;

}

function addCart($id, $name, $price, $num)
{
    $new = array('id' => $id, 'name' => $name, 'price' => $price, 'num' => $num);
    $arr = getCart();
    if (is_array($arr)) {
        $find = false;
        foreach ($arr as $key => $row) {
            if ($row['id'] === $id) {
                $arr[$key]['num'] += $num;
                $find = true;
                break;
            }
        }
        if (!$find) {
            array_push($arr, $new);
        }
    } else {
        $arr = array();
        array_push($arr, $new);
    }
    setSession('CART', $arr);
}

function removeCart($id)
{
    $arr = getCart();
    if (is_array($arr)) {
        $ids = null;
        foreach ($arr as $key => $row) {
            if ($row['id'] === $id) {
                unset($arr[$key]);
                setSession('CART', $arr);
                return true;
            }
        }
    }
    return false;
}

function checkLogin()
{
    if (checkSession('USER_ID') && checkSession('USER_ROLE')) {
        return true;
    } else {
        return false;
    }
}

function setLogin($userId, $role, $userName)
{
    $cart = array();
    setSession('CART', $cart);
    setSession('USER_ID', $userId);
    setSession('USER_ROLE', $role);
    setSession('USER_NAME', $userName);
}

function checkLoginCnt()
{
    if (checkSession('USER_LOGIN_CNT')) {
        $cnt = getSession('USER_LOGIN_CNT');
        if ($cnt === false) {
            $cnt = 3;
        }
    } else {
        $cnt = 0;
    }
    if ($cnt + 1 > 3) {
        return false;
    } else {
        return true;
    }
}

function loginError()
{
    if (checkSession('USER_LOGIN_CNT')) {
        $cnt = getSession('USER_LOGIN_CNT');
        if ($cnt === false) {
            $cnt = 0;
        }
    } else {
        $cnt = 0;
    }
    $cnt = ($cnt > 3 ? $cnt : $cnt + 1);
    setSession('USER_LOGIN_CNT', $cnt);
}

?>