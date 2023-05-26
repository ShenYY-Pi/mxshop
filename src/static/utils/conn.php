<?php

$link=@mysqli_connect('localhost','root','123456','mxshop',3306) or die ("无法连接数据库".mysqli_error());

mysqli_select_db($link,'mxshop');
mysqli_query($link,"set names utf8");
?>
