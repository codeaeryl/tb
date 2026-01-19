<?php
include_once __DIR__ . '/PDOUtil.php';
include_once __DIR__ . '/../models/PerformaPromo.php';

class PerformaPromoServices
{
    public function getAllPerformaPromo(): array
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT kode_promo, desc_promo, jumlah_pemakaian, total_pendapatan FROM ViewPerformaPromo ORDER BY total_pendapatan DESC";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, PerformaPromo::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }
}