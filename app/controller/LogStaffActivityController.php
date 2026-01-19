<?php
include_once __DIR__ . "/../config/LogStaffActivityServices.php";

class LogStaffActivityController {
    private LogStaffActivityServices $logStaffActivityServices;

    public function __construct() {
        $this->logStaffActivityServices = new LogStaffActivityServices();
    }

    public function index() : void
    {
        $logs = $this->logStaffActivityServices->getAllLogStaffActivity();
        include __DIR__ . "/../view/log_staff_activity/index.php";
        exit;
    }
}
