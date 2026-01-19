<?php

class Reservasi
{
    private string $id_reservasi;
    private ?string $kode_promo;
    private string $nama_tamu;
    private string $no_hp;
    private string $email;
    private string $tanggal_reservasi;
    private string $tanggal_checkin;
    private string $tanggal_checkout;
    private float $total_harga;
    private string $status_reservasi;
    private ?string $metode_pembayaran;
    private ?string $nomor_kamar = null;
    private ?string $nama_tipe_kamar = null;

    public function getIdReservasi(): string { return $this->id_reservasi; }
    public function setIdReservasi(string $id_reservasi): void { $this->id_reservasi = $id_reservasi; }

    public function getKodePromo(): ?string { return $this->kode_promo; }
    public function setKodePromo(?string $kode_promo): void { $this->kode_promo = $kode_promo; }

    public function getNamaTamu(): string { return $this->nama_tamu; }
    public function setNamaTamu(string $nama_tamu): void { $this->nama_tamu = $nama_tamu; }

    public function getNoHp(): string { return $this->no_hp; }
    public function setNoHp(string $no_hp): void { $this->no_hp = $no_hp; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getTanggalReservasi(): string { return $this->tanggal_reservasi; }
    public function setTanggalReservasi(string $tanggal_reservasi): void { $this->tanggal_reservasi = $tanggal_reservasi; }

    public function getTanggalCheckin(): string { return $this->tanggal_checkin; }
    public function setTanggalCheckin(string $tanggal_checkin): void { $this->tanggal_checkin = $tanggal_checkin; }

    public function getTanggalCheckout(): string { return $this->tanggal_checkout; }
    public function setTanggalCheckout(string $tanggal_checkout): void { $this->tanggal_checkout = $tanggal_checkout; }

    public function getTotalHarga(): float { return $this->total_harga; }
    public function setTotalHarga(float $total_harga): void { $this->total_harga = $total_harga; }

    public function getStatusReservasi(): string { return $this->status_reservasi; }
    public function setStatusReservasi(string $status_reservasi): void { $this->status_reservasi = $status_reservasi; }

    public function getMetodePembayaran(): ?string { return $this->metode_pembayaran; }
    public function setMetodePembayaran(?string $metode_pembayaran): void { $this->metode_pembayaran = $metode_pembayaran; }

    public function getNomorKamar(): ?string { return $this->nomor_kamar; }
    public function setNomorKamar(?string $nomor_kamar): void { $this->nomor_kamar = $nomor_kamar; }

    public function getNamaTipeKamar(): ?string { return $this->nama_tipe_kamar; }
    public function setNamaTipeKamar(?string $nama_tipe_kamar): void { $this->nama_tipe_kamar = $nama_tipe_kamar; }
}