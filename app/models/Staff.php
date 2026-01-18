<?php
class Staff
{
    // Attributes
    private string $staff_id;
    private string $username;
    private string $password_hash;
    private string $email;
    private string $nama_staff;
    private string $posisi;
    private	string $status_akun;

    public function getStaffId(): string
    {
        return $this->staff_id;
    }

    public function setStaffId(string $staff_id): void
    {
        $this->staff_id = $staff_id;
    }

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
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getNamaStaff(): string
    {
        return $this->nama_staff;
    }

    public function setNamaStaff(string $nama_staff): void
    {
        $this->nama_staff = $nama_staff;
    }

    public function getPosisi(): string
    {
        return $this->posisi;
    }

    public function setPosisi(string $posisi): void
    {
        $this->posisi = $posisi;
    }

    public function getStatusAkun(): string
    {
        return $this->status_akun;
    }
    
    public function setStatusAkun(string $status_akun): void
    {
        $this->status_akun = $status_akun;
    }
}
