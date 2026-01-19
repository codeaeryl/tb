<?php


include_once __DIR__ . "/../controller/StaffController.php";
include_once __DIR__ . "/../controller/LogStaffActivityController.php";
include_once __DIR__ . "/../controller/RoomOccupancyController.php";
include_once __DIR__ . "/../controller/PendapatanController.php";
include_once __DIR__ . "/../controller/PerformaPromoController.php";
include_once __DIR__ . "/../controller/KamarPopulerController.php";
include_once __DIR__ . "/../controller/ReservasiController.php";
include_once __DIR__ . "/../controller/JadwalController.php";
include_once __DIR__ . "/../controller/KamarController.php";
include_once __DIR__ . "/../controller/MaintenanceController.php";

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
    case 'log_staff_activity':
        $controller = new LogStaffActivityController();
        $controller->index();
        break;
    case 'laporan-bor':
        $controller = new RoomOccupancyController();
        $controller->index();
        break;
    case 'laporan-pendapatan':
        $controller = new PendapatanController();
        $controller->index();
        break;
    case 'performa-promo':
        $controller = new PerformaPromoController();
        $controller->index();
        break;
    case 'kamar-populer':
        $controller = new KamarPopulerController();
        $controller->index();
        break;
    case 'reservasi':
        $controller = new ReservasiController();
        $controller->index();
        break;
    case 'reservasi-edit':
        $controller = new ReservasiController();
        $controller->edit();
        break;
    case 'reservasi-update':
        $controller = new ReservasiController();
        $controller->update();
        break;
    case 'check-in-today':
        $controller = new JadwalController();
        $controller->checkin();
        break;
    case 'check-out-today':
        $controller = new JadwalController();
        $controller->checkout();
        break;
    case 'process-checkin':
        $controller = new JadwalController();
        $controller->processCheckin();
        break;
    case 'process-checkout':
        $controller = new JadwalController();
        $controller->processCheckout();
        break;
    case 'process-cancel':
        $controller = new JadwalController();
        $controller->processCancel();
        break;
    case 'kamar':
        $controller = new KamarController();
        $controller->index();
        break;
    case 'maintenance':
        $controller = new MaintenanceController();
        $controller->index();
        break;
    case 'maintenance-add':
        $controller = new MaintenanceController();
        $controller->create();
        break;
    case 'maintenance-store':
        $controller = new MaintenanceController();
        $controller->store();
        break;
    case 'maintenance-edit':
        $controller = new MaintenanceController();
        $controller->edit();
        break;
    case 'maintenance-update':
        $controller = new MaintenanceController();
        $controller->update();
        break;
    case 'maintenance-delete':
        $controller = new MaintenanceController();
        $controller->delete();
        break;
    default:
        $controller = new StaffController();
        $controller->login();
        break;
}