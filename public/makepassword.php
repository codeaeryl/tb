<?php
$password = "Staff#1234";
$hash = password_hash($password, PASSWORD_DEFAULT);
var_dump($hash);
var_dump(password_verify($password, $hash));