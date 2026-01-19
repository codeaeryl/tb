<?php
include_once __DIR__ . "/../config/ReservasiServices.php";

class ReservasiController
{
    private ReservasiServices $reservasiServices;

    public function __construct()
    {
        $this->reservasiServices = new ReservasiServices();
    }

    public function index(): void
    {
        $reservations = $this->reservasiServices->getAllReservasi();
        include __DIR__ . "/../view/reservasi/index.php";
        exit;
    }
}