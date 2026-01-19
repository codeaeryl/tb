<?php
include_once __DIR__ . '/PDOUtil.php';
include_once __DIR__ . '/../models/LogStaffActivity.php';

class LogStaffActivityServices {
    public function getAllLogStaffActivity(): array
    {
        $link = PDOUtil::createMySQLConnection();
        $query = "SELECT log_id, user_staff, activity_desc, activity_timestamp FROM log_staff_activity ORDER BY activity_timestamp DESC";
        $stmt = $link->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, LogStaffActivity::class);
        $stmt->execute();
        $link = null;
        return $stmt->fetchAll();
    }
}