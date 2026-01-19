<?php
include_once __DIR__ . '/PDOUtil.php';
include_once __DIR__ . '/../models/Reservasi.php';

class ReservasiServices
{
    public function getAllReservasi(): array
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT * FROM ViewReservasiList";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Reservasi::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }

    public function getOneReservasi(string $id): ?Reservasi
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT * FROM reservasi WHERE id_reservasi = ?";
        $stmt = $link->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Reservasi::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetch() ?: null;
    }

    public function checkinReservasi(string $id): bool
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "CALL sp_reservasi_checkin(?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $_SESSION['username']);
        $link->beginTransaction();
        try {
            if ($stmt->execute()) {
                $link->commit();
                return true;
            } else {
                $link->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            if ($link->inTransaction()) {
                $link->rollBack();
            }
            return false;
        }
    }

    public function checkoutReservasi(string $id): bool
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "CALL sp_reservasi_checkout(?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $_SESSION['username']);
        $link->beginTransaction();
        try {
            if ($stmt->execute()) {
                $link->commit();
                return true;
            } else {
                $link->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            if ($link->inTransaction()) {
                $link->rollBack();
            }
            return false;
        }
    }

    public function cancelReservasi(string $id): bool
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "CALL sp_reservasi_cancel(?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $_SESSION['username']);
        $link->beginTransaction();
        try {
            if ($stmt->execute()) {
                $link->commit();
                return true;
            } else {
                $link->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            if ($link->inTransaction()) {
                $link->rollBack();
            }
            return false;
        }
    }
}