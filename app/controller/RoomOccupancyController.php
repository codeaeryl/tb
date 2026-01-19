<?php
include_once __DIR__ . "/../config/RoomOccupancyServices.php";

class RoomOccupancyController
{
    private RoomOccupancyServices $roomOccupancyServices;

    public function __construct()
    {
        $this->roomOccupancyServices = new RoomOccupancyServices();
    }

    public function index(): void
    {
        $occupancies = $this->roomOccupancyServices->getAllRoomOccupancy();
        include __DIR__ . "/../view/laporan/bor.php";
        exit;
    }
}
