<?php

class LogStaffActivity {
    private $log_id;
    private $user_staff;
    private $activity_desc;
    private $activity_timestamp;

    // Getters
    public function getLogId() { return $this->log_id; }
    public function getUserStaff() { return $this->user_staff; }
    public function getActivityDesc() { return $this->activity_desc; }
    public function getActivityTimestamp() { return $this->activity_timestamp; }

    // Setters
    public function setLogId($log_id) { $this->log_id = $log_id; }
    public function setUserStaff($user_staff) { $this->user_staff = $user_staff; }
    public function setActivityDesc($activity_desc) { $this->activity_desc = $activity_desc; }
    public function setActivityTimestamp($activity_timestamp) { $this->activity_timestamp = $activity_timestamp; }
}
