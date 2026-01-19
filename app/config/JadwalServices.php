<?php
include_once __DIR__ . '/PDOUtil.php';
include_once __DIR__ . '/../models/Jadwal.php';

class JadwalServices
{
    public function getCheckinToday(): array
    {
        $link = PDOUtil::createMySQLConnection();
        $query = 'SELECT `ID Reservasi` as id_reservasi, `Nomor Kamar` as nomor_kamar, `Nama Tamu` as nama_tamu, `Tipe Kamar` as tipe_kamar FROM ViewJadwalCheckinHariIni';
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Jadwal::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }

    public function getCheckoutToday(): array
    {
        $link = PDOUtil::createMySQLConnection();
        $query = 'SELECT `ID Reservasi` as id_reservasi, `Nomor Kamar` as nomor_kamar, `Nama Tamu` as nama_tamu, `Tipe Kamar` as tipe_kamar FROM ViewJadwalCheckoutHariIni';
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Jadwal::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }
}