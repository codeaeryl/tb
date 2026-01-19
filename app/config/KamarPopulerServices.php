<?php
include_once __DIR__ . '/PDOUtil.php';
include_once __DIR__ . '/../models/KamarPopuler.php';

class KamarPopulerServices
{
    public function getAllKamarPopuler(): array
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT periode_pemesanan, tipe_kamar, jumlah_pemesanan FROM ViewTipeKamarPopulerBulanan";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, KamarPopuler::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }
}