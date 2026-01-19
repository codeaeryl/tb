<?php

class Maintenance
{
    private string $id_maintenance;
    private string $nomor_kamar;
    private string $tanggal_mulai;
    private string $tanggal_selesai;
    private string $deskripsi_maintenance;
    private string $status_maintenance;

    public function getIdMaintenance(): string { return $this->id_maintenance; }
    public function setIdMaintenance(string $id_maintenance): void { $this->id_maintenance = $id_maintenance; }

    public function getNomorKamar(): string { return $this->nomor_kamar; }
    public function setNomorKamar(string $nomor_kamar): void { $this->nomor_kamar = $nomor_kamar; }

    public function getTanggalMulai(): string { return $this->tanggal_mulai; }
    public function setTanggalMulai(string $tanggal_mulai): void { $this->tanggal_mulai = $tanggal_mulai; }

    public function getTanggalSelesai(): string { return $this->tanggal_selesai; }
    public function setTanggalSelesai(string $tanggal_selesai): void { $this->tanggal_selesai = $tanggal_selesai; }

    public function getDeskripsiMaintenance(): string { return $this->deskripsi_maintenance; }
    public function setDeskripsiMaintenance(string $deskripsi_maintenance): void { $this->deskripsi_maintenance = $deskripsi_maintenance; }

    public function getStatusMaintenance(): string { return $this->status_maintenance; }
    public function setStatusMaintenance(string $status_maintenance): void { $this->status_maintenance = $status_maintenance; }
}