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
        break;
    case 'staff':
        $controller = new StaffController();
        $controller->index();
        break;
    case 'staff-add':
        $controller = new StaffController();
        $controller->create();
        break;
    case 'staff-store':
        $controller = new StaffController();
        $controller->store();
        break;
    case 'staff-edit':
        $controller = new StaffController();
        $controller->edit();
        break;
    case 'staff-update':
        $controller = new StaffController();
        $controller->update();
        break;
    case 'staff-delete':
        $controller = new StaffController();
        $controller->delete();
        break;
    default:
        $controller = new StaffController();
        $controller->login();
        break;
}
