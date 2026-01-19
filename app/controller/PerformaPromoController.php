<?php
include_once __DIR__ . "/../config/PerformaPromoServices.php";

class PerformaPromoController
{
    private PerformaPromoServices $performaPromoServices;

    public function __construct()
    {
        $this->performaPromoServices = new PerformaPromoServices();
    }

    public function index(): void
    {
        $promos = $this->performaPromoServices->getAllPerformaPromo();
        include __DIR__ . "/../view/laporan/performa_promo.php";
        exit;
    }
}