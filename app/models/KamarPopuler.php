<?php

class KamarPopuler
{
    private string $periode_pemesanan;
    private string $tipe_kamar;
    private int $jumlah_pemesanan;

    public function getPeriodePemesanan(): string
    {
        return $this->periode_pemesanan;
    }

    public function setPeriodePemesanan(string $periode_pemesanan): void
    {
        $this->periode_pemesanan = $periode_pemesanan;
    }

    public function getTipeKamar(): string
    {
        return $this->tipe_kamar;
    }

    public function setTipeKamar(string $tipe_kamar): void
    {
        $this->tipe_kamar = $tipe_kamar;
    }

    public function getJumlahPemesanan(): int
    {
        return $this->jumlah_pemesanan;
    }

    public function setJumlahPemesanan(int $jumlah_pemesanan): void
    {
        $this->jumlah_pemesanan = $jumlah_pemesanan;
    }
}