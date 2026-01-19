<?php
include_once __DIR__ . "/../config/MaintenanceServices.php";

class MaintenanceController
{
    private MaintenanceServices $maintenanceServices;

    public function __construct()
    {
        $this->maintenanceServices = new MaintenanceServices();
    }

    public function index(): void
    {
        $maintenances = $this->maintenanceServices->getAllMaintenance();
        include __DIR__ . "/../view/maintenance/index.php";
        exit;
    }

    public function create(): void
    {
        include __DIR__ . "/../view/maintenance/create.php";
        exit;
    }

    public function store(): void
    {
        $nomor_kamar = trim($_POST['nomor_kamar']);
        $tanggal_mulai = $_POST['tanggal_mulai'];
        $tanggal_selesai = $_POST['tanggal_selesai'];
        $deskripsi = trim($_POST['deskripsi_maintenance']);
        $errMessage = "";
        if (empty($nomor_kamar) || empty($tanggal_mulai) || empty($tanggal_selesai) || empty($deskripsi)) {
            $errMessage = "Please fill all fields.";
        }
        if ($tanggal_selesai < $tanggal_mulai) {
            $errMessage = "End date cannot be before start date.";
        }
        if (!empty($errMessage)) {
            header("Location: index.php?menu=maintenance-add&message=" . $errMessage);
            exit;
        }
        $maintenance = new Maintenance();
        $maintenance->setNomorKamar($nomor_kamar);
        $maintenance->setTanggalMulai($tanggal_mulai);
        $maintenance->setTanggalSelesai($tanggal_selesai);
        $maintenance->setDeskripsiMaintenance($deskripsi);
        if ($this->maintenanceServices->addMaintenance($maintenance)) {
            $sucessMessage = "Maintenance scheduled.";
            header("Location: index.php?menu=maintenance&success=" . $sucessMessage);
        } else {
            $errMessage = "Something went wrong.";
            header("Location: index.php?menu=maintenance-add&message=" . $errMessage);
        }
        exit;
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? '';
        $maintenance = $this->maintenanceServices->getOneMaintenance($id);
        if ($maintenance) {
            include __DIR__ . "/../view/maintenance/edit.php";
        } else {
            $errMessage = "Maintenance not found.";
            header("Location: index.php?menu=maintenance&message=" . $errMessage);
        }
        exit;
    }

    public function update(): void
    {
        $id = $_POST['id_maintenance'];
        $tanggal_mulai = $_POST['tanggal_mulai'];
        $tanggal_selesai = $_POST['tanggal_selesai'];
        $deskripsi = trim($_POST['deskripsi_maintenance']);
        $status_maintenance = $_POST['status_maintenance'];
        $errMessage = "";
        if (empty($id) || empty($tanggal_mulai) || empty($tanggal_selesai) || empty($deskripsi) || empty($status_main)) {
            $errMessage = "Please fill all fields.";
        }
        if ($tanggal_selesai < $tanggal_mulai) {
            $errMessage = "";
        }
        if (!empty($errMessage)) {
            header("Location: index.php?menu=maintenance-edit&id=$id&message=" . $errMessage);
            exit;
        }
        $maintenance = new Maintenance();
        $maintenance->setIdMaintenance($id);
        $maintenance->setNomorKamar(trim($_POST['nomor_kamar']));
        $maintenance->setTanggalMulai($tanggal_mulai);
        $maintenance->setTanggalSelesai($tanggal_selesai);
        $maintenance->setDeskripsiMaintenance($deskripsi);
        $maintenance->setStatusMaintenance($status_maintenance);
        if ($this->maintenanceServices->updateMaintenance($maintenance)) {
            header("Location: index.php?menu=maintenance&success=Maintenance updated.");
        } else {
            header("Location: index.php?menu=maintenance-edit&id=$id&message=Something went wrong.");
        }
        exit;
    }

    public function delete(): void
    {
        $id = $_POST['id_maintenance'];
        if ($this->maintenanceServices->deleteMaintenance($id)) {
            header("Location: index.php?menu=maintenance&success=Maintenance deleted.");
        } else {
            header("Location: index.php?menu=maintenance&message=Something went wrong.");
        }
        exit;
    }
}