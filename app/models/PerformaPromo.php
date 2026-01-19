<?php

class PerformaPromo
{
    private string $kode_promo;
    private string $desc_promo;
    private int $jumlah_pemakaian;
    private float $total_pendapatan;

    public function getKodePromo(): string
    {
        return $this->kode_promo;
    }

    public function setKodePromo(string $kode_promo): void
    {
        $this->kode_promo = $kode_promo;
    }

    public function getDescPromo(): string
    {
        return $this->desc_promo;
    }

    public function setDescPromo(string $desc_promo): void
    {
        $this->desc_promo = $desc_promo;
    }

    public function getJumlahPemakaian(): int
    {
        return $this->jumlah_pemakaian;
    }

    public function setJumlahPemakaian(int $jumlah_pemakaian): void
    {
        $this->jumlah_pemakaian = $jumlah_pemakaian;
    }

    public function getTotalPendapatan(): float
    {
        return $this->total_pendapatan;
    }

    public function setTotalPendapatan(float $total_pendapatan): void
    {
        $this->total_pendapatan = $total_pendapatan;
    }
}