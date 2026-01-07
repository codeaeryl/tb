<?php
require_once __DIR__ . "/enums/TipePengguna.php";

class Pengguna
{
    // Attributes
    private string $username;
    private string $passwordHash;
    private string $email;
    private string $noTelp;
    private string $namaLengkap;
    private TipePengguna $tipePengguna;

    // Constructor
    public function __construct(string $username, string $passwordHash, string $email, string $noTelp, string $namaLengkap, string $tipePengguna)
    {
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->email = $email;
        $this->noTelp = $noTelp;
        $this->namaLengkap = $namaLengkap;
        if ($tipePengguna == TipePengguna::Tamu->name) {
            $this->tipePengguna = TipePengguna::Tamu;
        } else if ($tipePengguna == TipePengguna::Staff->name) {
            $this->tipePengguna = TipePengguna::Staff;
        } else {
            $this->tipePengguna = TipePengguna::Manager;
        }
    }

    // Getters & Setters
    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getNamaLengkap(): string
    {
        return $this->namaLengkap;
    }

    public function setNamaLengkap(string $namaLengkap): void
    {
        $this->namaLengkap = $namaLengkap;
    }

    public function getNoTelp(): string
    {
        return $this->noTelp;
    }

    public function setNoTelp(string $noTelp): void
    {
        $this->noTelp = $noTelp;
    }

    public function getTipePengguna(): TipePengguna
    {
        return $this->tipePengguna;
    }

    public function setTipePengguna(TipePengguna $tipePengguna): void
    {
        $this->tipePengguna = $tipePengguna;
    }
}
