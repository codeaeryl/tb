<?php

class Jadwal
{
    private string $id_reservasi;
    private string $nomor_kamar;
    private string $nama_tamu;
    private string $tipe_kamar;

    public function getIdReservasi(): string { return $this->id_reservasi; }
    public function setIdReservasi(string $id_reservasi): void { $this->id_reservasi = $id_reservasi; }

    public function getNomorKamar(): string { return $this->nomor_kamar; }
    public function setNomorKamar(string $nomor_kamar): void { $this->nomor_kamar = $nomor_kamar; }

    public function getNamaTamu(): string { return $this->nama_tamu; }
    public function setNamaTamu(string $nama_tamu): void { $this->nama_tamu = $nama_tamu; }

    public function getTipeKamar(): string { return $this->tipe_kamar; }
    public function setTipeKamar(string $tipe_kamar): void { $this->tipe_kamar = $tipe_kamar; }
}