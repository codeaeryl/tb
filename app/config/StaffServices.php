<?php
include __DIR__ . '/PDOUtil.php';
include __DIR__ . '/../models/Staff.php';
class StaffServices {
    public function getAllStaff() : array {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT staff_id, username, password_hash, email, nama_staff, posisi, status_akun FROM staff";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,Staff::class);
        $stmt->execute();
        $link=null;
        return $stmt->fetchAll();
    }
    public function getOneStaff(string $staff_id) : ?Staff
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT staff_id, username, password_hash, email, nama_staff, posisi, status_akun FROM staff WHERE staff_id = ?";
        $stmt = $link->prepare($query);
        $stmt -> bindParam(1,$staff_id);
        $stmt->execute();
        $link=null;
        return $stmt->fetchObject(Staff::class) ?: null;
    }

    public function getOneStaffByUsername(string $username) : ?Staff
    { 
        $link=null;
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT staff_id, username, password_hash, email, nama_staff, posisi, status_akun FROM staff WHERE username = ?";
        $stmt = $link->prepare($query);
        $stmt -> bindParam(1,$username);
        $stmt->execute();
        $link=null;
        return $stmt->fetchObject(Staff::class) ?: null;
    }

    public function addStaff(Staff $staff) : bool
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "CALL sp_staff_insert(?,?,?,?,?,?,?)";
        $stmt = $link->prepare($query);
        $stmt -> bindValue(1,$staff->getUsername());
        $stmt -> bindValue(2,$staff->getPasswordHash());
        $stmt -> bindValue(3,$staff->getEmail());
        $stmt -> bindValue(4,$staff->getNamaStaff());
        $stmt -> bindValue(5,$staff->getPosisi());
        $stmt -> bindValue(6,$staff->getStatusAkun());
        $stmt -> bindValue(7,$_SESSION['username']);
        $link->beginTransaction();
        try {
            if ($stmt->execute()) {
                $link->commit();
                $link=null;
                return true;
            } else {
                $link->rollBack();
                $link=null;
                return false;
            }
        } catch (PDOException $e) {
            if ($link->inTransaction()) {
                $link->rollBack();
            }
            $link = null;
            return false;
        }
    }

    public function updateStaff(Staff $staff) : bool
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "CALL sp_staff_update(?,?,?,?,?,?,?,?)";
        $stmt = $link->prepare($query);
        $stmt -> bindValue(1,$staff->getStaffId());
        $stmt -> bindValue(2,$staff->getUsername());
        $stmt -> bindValue(3,$staff->getPasswordHash());
        $stmt -> bindValue(4,$staff->getEmail());
        $stmt -> bindValue(5,$staff->getNamaStaff());
        $stmt -> bindValue(6,$staff->getPosisi());
        $stmt -> bindValue(7,$staff->getStatusAkun());
        $stmt -> bindValue(8,$_SESSION['username']);
        $link -> beginTransaction();
        try {
            if ($stmt->execute()) {
                $link->commit();
                $link=null;
                return true;
            } else {
                $link->rollBack();
                $link=null;
                return false;
            }
        } catch (PDOException $e) {
            if ($link->inTransaction()) {
                $link->rollBack();
            }
            $link = null;
            return false;
        }
    }

    public function deleteStaff(string $staff_id) : bool
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "CALL sp_staff_delete(?,?)";
        $stmt = $link->prepare($query);
        $stmt -> bindValue(1,$staff_id);
        $stmt -> bindValue(2,$_SESSION['username']);
        $link -> beginTransaction();
        try {
            if ($stmt->execute()) {
                $link->commit();
                $link=null;
                return true;
            } else {
                $link->rollBack();
                $link=null;
                return false;
            }
        } catch (PDOException $e) {
            if ($link->inTransaction()) {
                $link->rollBack();
            }
            $link = null;
            return false;
        }
    }
}