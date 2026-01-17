<?php

include_once __DIR__ . "/../controller/StaffController.php";

$menu = filter_input(INPUT_GET, 'menu');
switch ($menu) {
    case 'login':
        $controller = new StaffController();
        $controller->login();
        break;
    case 'verify-login':
        $controller = new StaffController();
        $controller->verifyLogin();
        break;
    case 'logout':
        $controller = new StaffController();
        $controller->logout();
    case 'staff':
        $controller = new StaffController();
        $controller->index();
        break;
    default:
        $controller = new StaffController();
        $controller->login();
        break;
}
