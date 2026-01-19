<?php
class Pendapatan
{
    private string $periode;
    private float $total_pendapatan;
    private int $jumlah_transaksi;

    public function getPeriode(): string
    {
        return $this->periode;
    }

    public function setPeriode(string $periode): void
    {
        $this->periode = $periode;
    }

    public function getTotalPendapatan(): float
    {
        return $this->total_pendapatan;
    }

    public function setTotalPendapatan(float $total_pendapatan): void
    {
        $this->total_pendapatan = $total_pendapatan;
    }

    public function getJumlahTransaksi(): int
    {
        return $this->jumlah_transaksi;
    }

    public function setJumlahTransaksi(int $jumlah_transaksi): void
    {
        $this->jumlah_transaksi = $jumlah_transaksi;
    }
}