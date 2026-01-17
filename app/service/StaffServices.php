<?php
include __DIR__ . '/../config/PDOUtil.php';
include __DIR__ . '/../models/Staff.php';
class StaffServices {
    public function getAllStaff() : array {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT staff_id, username, password_hash, email, nama_staff, posisi, hire_date, resign_date FROM staff";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,Staff::class);
        $stmt->execute();
        $link=null;
        return $stmt->fetchAll();
    }
    public function getOneStaff(string $username) : ?Staff
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT staff_id, username, password_hash, email, nama_staff, posisi, hire_date, resign_date FROM staff WHERE staff_id = ?";
        $stmt = $link->prepare($query);
        $stmt -> bindParam(1,$staff_id);
        $stmt->execute();
        $link=null;
        return $stmt->fetchObject(Staff::class);
    }

    public function getOneStaffByUsername(string $username) : ?Staff
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT staff_id, username, password_hash, email, nama_staff, posisi, hire_date, resign_date FROM staff WHERE username = ?";
        $stmt = $link->prepare($query);
        $stmt -> bindParam(1,$username);
        $stmt->execute();
        $link=null;
        return $stmt->fetchObject(Staff::class);
    }
}