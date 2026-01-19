<?php
include_once __DIR__ . '/PDOUtil.php';
include_once __DIR__ . '/../models/RoomOccupancy.php';

class RoomOccupancyServices
{
    public function getAllRoomOccupancy(): array
    {
        $link = PDOUtil::createMySQLConnection();
        // Aliasing columns to match the private properties in the Model
        $query = "SELECT Periode as periode, Bed_Occupancy_Rate as bed_occupancy_rate FROM ViewRoomOccupancyRateBulanan";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, RoomOccupancy::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }
}
