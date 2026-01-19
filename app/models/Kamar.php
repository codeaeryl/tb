<?php

class Kamar
{
    private string $nomor_kamar;
    private string $nama_tipe_kamar;
    private string $desc_tipe_kamar;
    private float $harga_dasar;
    private int $kapasitas_maksimum;
    private float $harga_per_orang;

    public function getNomorKamar(): string { return $this->nomor_kamar; }
    public function setNomorKamar(string $nomor_kamar): void { $this->nomor_kamar = $nomor_kamar; }

    public function getNamaTipeKamar(): string { return $this->nama_tipe_kamar; }
    public function setNamaTipeKamar(string $nama_tipe_kamar): void { $this->nama_tipe_kamar = $nama_tipe_kamar; }

    public function getDescTipeKamar(): string { return $this->desc_tipe_kamar; }
    public function setDescTipeKamar(string $desc_tipe_kamar): void { $this->desc_tipe_kamar = $desc_tipe_kamar; }

    public function getHargaDasar(): float { return $this->harga_dasar; }
    public function setHargaDasar(float $harga_dasar): void { $this->harga_dasar = $harga_dasar; }

    public function getKapasitasMaksimum(): int { return $this->kapasitas_maksimum; }
    public function setKapasitasMaksimum(int $kapasitas_maksimum): void { $this->kapasitas_maksimum = $kapasitas_maksimum; }

    public function getHargaPerOrang(): float { return $this->harga_per_orang; }
    public function setHargaPerOrang(float $harga_per_orang): void { $this->harga_per_orang = $harga_per_orang; }
}