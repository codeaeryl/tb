<?php
include_once __DIR__ . "/../config/KamarPopulerServices.php";

class KamarPopulerController
{
    private KamarPopulerServices $kamarPopulerServices;

    public function __construct()
    {
        $this->kamarPopulerServices = new KamarPopulerServices();
    }

    public function index(): void
    {
        $kamarPopuler = $this->kamarPopulerServices->getAllKamarPopuler();
        include __DIR__ . "/../view/laporan/kamar_populer.php";
        exit;
    }
}