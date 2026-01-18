<?php
include_once __DIR__ . "/../config/StaffServices.php";
class StaffController
{
    private StaffServices $staffServices;

    public function __construct()
    {
        $this->staffServices = new staffServices();
    }

    public function index() : void
    {
        $staffs = $this->staffServices->getAllStaff();
        include __DIR__ . "/../view/staff/index.php";
        exit;
    }

    public function login() : void
    {
        include __DIR__ . "/../view/login.php";
        exit;
    }

    public function verifyLogin() : void
    {
        $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
        $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
        if (empty($username) || empty($password)) {
            $errMessage = "Please fill all required fields";
            header("Location: index.php?menu=login&message=" . $errMessage);
            exit;
        } else {
            $staff = $this->staffServices->getOneStaffByUsername($username);
            if ($staff && $staff->getUsername() != null) {
                if ($staff->getStatusAkun() == "Active" && password_verify($password, $staff->getPasswordHash())) {
                    $successMessage = "Login successfully";
                    $_SESSION['username'] = $staff->getUsername();
                    $_SESSION['posisi'] = $staff->getPosisi();
                    $successMessage = "Login successfully! Welcome Back, " . $staff->getNamaStaff() . "!";
                    if ($staff->getPosisi() == "Manager") {
                        header("Location: index.php?menu=staff&success=" . $successMessage);
                        exit;
                    } else {
                        header("Location: index.php?menu=reservasi&success=" . $successMessage);
                        exit;
                    }
                } else {
                    $errMessage = "Incorrect username and password";
                    header("Location: index.php?menu=login&message=" . $errMessage);
                    exit;
                }
            } else {
                $errMessage = "Incorrect username and password";
                header("Location: index.php?menu=login&message=" . $errMessage . "&username=" . $username . "&password=" . $password);
                exit;
            }
        }
    }

    public function logout() : void
    {
        session_destroy();
        header("Location: index.php?menu=login");
        exit;
    }

    public function create() : void 
    {
        include __DIR__ . "/../view/staff/create.php";
        exit;
    }
    public function store() : void
    {
        $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
        $password = $_POST['password'];
        $confirm_password = $_POST['password_confirm'];
        $nama_staff = trim(filter_input(INPUT_POST, 'nama_staff', FILTER_SANITIZE_STRING));
        $email = $_POST['email'];
        $posisi = trim(filter_input(INPUT_POST, 'posisi', FILTER_SANITIZE_STRING));
        $status_akun = $_POST['status_akun'];
        $staff = $this->staffServices->getOneStaffByUsername($username);
        $errMessage = "";
        // 2. Comprehensive Validation
        if (empty($username) || empty($password) || empty($confirm_password) || empty($posisi) || empty($email) || empty($nama_staff) || empty($status_akun)) {
            $errMessage = "Please fill all required fields with valid data.";
        } 
        elseif ($staff && $staff->getUsername() == $username) {
            $errMessage = "Username is already in use.";
        }
        elseif (strlen($password) < 8) {
            $errMessage = "Password must be at least 8 characters long.";
        }
        elseif ($password !== $confirm_password) {
            $errMessage = "Passwords do not match.";
        }
        if (!empty($errMessage)) {
            header("Location: index.php?menu=staff-add&message=" . $errMessage);
            exit;
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $staff = new Staff();
            $staff->setUsername($username);
            $staff->setPasswordHash($hashedPassword);
            $staff->setEmail($email);
            $staff->setNamaStaff($nama_staff);
            $staff->setPosisi($posisi);
            $staff->setStatusAkun($status_akun);
            if ($this->staffServices->addStaff($staff)) {
                $successMessage = "Staff created successfully.";
                header("Location: index.php?menu=staff&success=" . $successMessage);
                exit;
            } else {
                $errMessage = "Failed to create staff.";
                header("Location: index.php?menu=staff-add&message=" . $errMessage);
                exit;
            }
        }
    }

    public function edit() : void 
    {
        $staff_id = filter_input(INPUT_GET, 'staff_id', FILTER_SANITIZE_STRING);
        if (!empty($staff_id)) {
            $staff = $this->staffServices->getOneStaff($staff_id);
            if ($staff && $staff->getNamaStaff() != null) {
                include_once __DIR__ . "/../view/staff/edit.php";
                exit;
            }
        } else {
            $errMessage = "Staff not found.";
            header("location: index.php?menu=staff&message=" . $errMessage);
            exit;
        }
    }

    public function update() : void
    {
        $staff_id = filter_input(INPUT_POST, 'staff_id', FILTER_SANITIZE_STRING);
        $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['password_confirm'];
        $nama_staff = trim(filter_input(INPUT_POST, 'nama_staff', FILTER_SANITIZE_STRING));
        $email = $_POST['email'];
        $posisi = trim(filter_input(INPUT_POST, 'posisi', FILTER_SANITIZE_STRING));
        $status_akun = $_POST['status_akun'];
        $staff = $this->staffServices->getOneStaffByUsername($username);
        $errMessage = "";
        // 2. Comprehensive Validation
        if (empty($staff_id) || empty($username) || empty($old_password) || empty($posisi) || empty($email) || empty($nama_staff) || empty($status_akun)) {
            $errMessage = "Please fill all required fields with valid data.";
        } 
        elseif ($staff && $staff->getStaffId()!= $staff_id && $staff->getUsername() == $username) {
            $errMessage = "Username is already in use.";
        }
        $hashedPassword = $old_password;
        if (!empty($new_password)) {
            if(empty($confirm_password)) {
                $errMessage = "Please confirm your new password.";
            } 
            elseif (strlen($new_password) < 8) {
                $errMessage = "Password must be at least 8 characters long.";
            }
            elseif ($new_password !== $confirm_password) {
                $errMessage = "Passwords do not match.";
            } else {
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            }
        }
        if (!empty($errMessage)) {
            header("Location: index.php?menu=staff-edit&staff_id=" . $staff_id . "&message=" . $errMessage);
            exit;
        } else {
            $currentStaff = $this->staffServices->getOneStaff($staff_id);
            if ($currentStaff && $currentStaff->getUsername() === $username && $currentStaff->getEmail() === $email && $currentStaff->getNamaStaff() === $nama_staff && $currentStaff->getPosisi() === $posisi && $currentStaff->getStatusAkun() === $status_akun && $currentStaff->getPasswordHash() === $hashedPassword) {
                $successMessage = "No changes made.";
                header("Location: index.php?menu=staff&success=" . $successMessage);
                exit;
            }

            $staff = new Staff();
            $staff->setStaffId($staff_id);
            $staff->setUsername($username);
            $staff->setPasswordHash($hashedPassword);
            $staff->setEmail($email);
            $staff->setNamaStaff($nama_staff);
            $staff->setPosisi($posisi);
            $staff->setStatusAkun($status_akun);
            if ($this->staffServices->updateStaff($staff)) {
                $successMessage = "Staff edited successfully.";
                header("Location: index.php?menu=staff&success=" . $successMessage);
                exit;
            } else {
                $errMessage = "Failed to edit staff.";
                header("Location: index.php?menu=staff-edit&staff_id=" . $staff_id . "&message=" . $errMessage);
                exit;
            }
        }
    }
}