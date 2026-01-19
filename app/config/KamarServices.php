<?php
include_once __DIR__ . '/PDOUtil.php';
include_once __DIR__ . '/../models/Kamar.php';

class KamarServices
{
    public function getAllKamar(): array
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT * FROM ViewKamarList ORDER BY nomor_kamar ASC";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Kamar::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }
}