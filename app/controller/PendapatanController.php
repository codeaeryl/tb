<?php
include_once __DIR__ . "/../config/PendapatanServices.php";

class PendapatanController
{
    private PendapatanServices $pendapatanServices;

    public function __construct()
    {
        $this->pendapatanServices = new PendapatanServices();
    }

    public function index(): void
    {
        $pendapatanList = $this->pendapatanServices->getAllPendapatan();
        include __DIR__ . "/../view/laporan/pendapatan.php";
        exit;
    }
}