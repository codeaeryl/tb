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
    private	string $hire_date;
    private ?string $resign_date;

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

    public function getHireDate(): string
    {
        return $this->hire_date;
    }

    public function setHireDate(string $hire_date): void
    {
        $this->hire_date = $hire_date;
    }

    public function getResignDate(): ?string
    {
        return $this->resign_date;
    }

    public function setResignDate(?string $resign_date): void
    {
        $this->resign_date = $resign_date;
    }
}
