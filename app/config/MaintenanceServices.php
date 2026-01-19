<?php
include_once __DIR__ . '/PDOUtil.php';
include_once __DIR__ . '/../models/Maintenance.php';

class MaintenanceServices
{
    public function getAllMaintenance(): array
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT * FROM maintenance ORDER BY tanggal_mulai DESC";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Maintenance::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }

    public function getOneMaintenance(string $id): ?Maintenance
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT * FROM maintenance WHERE id_maintenance = ?";
        $stmt = $link->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Maintenance::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetch() ?: null;
    }

    public function addMaintenance(Maintenance $m): bool
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "CALL sp_maintenance_insert(?, ?, ?, ?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $m->getNomorKamar());
        $stmt->bindValue(2, $m->getTanggalMulai());
        $stmt->bindValue(3, $m->getTanggalSelesai());
        $stmt->bindValue(4, $m->getDeskripsiMaintenance());
        $stmt->bindValue(5, $_SESSION['username']);
        $link->beginTransaction();
        try {
            if ($stmt->execute()) {
                $link->commit();
                $link = null;
                return true;
            } else {
                $link->rollBack();
                $link = null;
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
    public function updateMaintenance(Maintenance $m): bool
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "CALL sp_maintenance_update(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $m->getIdMaintenance());
        $stmt->bindValue(2, $m->getNomorKamar());
        $stmt->bindValue(3, $m->getTanggalMulai());
        $stmt->bindValue(4, $m->getTanggalSelesai());
        $stmt->bindValue(5, $m->getDeskripsiMaintenance());
        $stmt->bindValue(6, $m->getStatusMaintenance());
        $stmt->bindValue(7, $_SESSION['username']);
        $link->beginTransaction();
        try {
            if ($stmt->execute()) {
                $link->commit();
                $link = null;
                return true;
            } else {
                $link->rollBack();
                $link = null;
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

    public function deleteMaintenance(string $id): bool
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "CALL sp_maintenance_delete(?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $_SESSION['username']);
        $link->beginTransaction();
        try {
            if ($stmt->execute()) {
                $link->commit();
                $link = null;
                return true;
            } else {
                $link->rollBack();
                $link = null;
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