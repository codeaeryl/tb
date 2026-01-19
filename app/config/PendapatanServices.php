<?php
include_once __DIR__ . '/PDOUtil.php';
include_once __DIR__ . '/../models/Pendapatan.php';

class PendapatanServices
{
    public function getAllPendapatan(): array
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT Periode as periode, Total_Pendapatan as total_pendapatan, Jumlah_Transaksi as jumlah_transaksi FROM ViewPendapatanBulanan";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Pendapatan::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }
}