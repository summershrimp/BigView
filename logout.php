<?php

if (!defined("IN_ACE") || !($aceman->is_login())) die("Forbidden.");

header("charset=utf-8");

$aceman->logout();

echo "<script>window.top.location.href='./';</script>";

?>