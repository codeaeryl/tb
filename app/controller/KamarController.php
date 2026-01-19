<?php
include_once __DIR__ . "/../config/KamarServices.php";

class KamarController
{
    private KamarServices $kamarServices;

    public function __construct()
    {
        $this->kamarServices = new KamarServices();
    }

    public function index(): void
    {
        $kamars = $this->kamarServices->getAllKamar();
        include __DIR__ . "/../view/kamar/index.php";
        exit;
    }
}