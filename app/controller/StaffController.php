<?php
include_once __DIR__ . "/../service/StaffServices.php";
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
        include __DIR__ . "/../view/manager/index.php";
    }

    public function login() : void
    {
        include __DIR__ . "/../view/login.php";
    }

    public function verifyLogin() : void
    {
        $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
        $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
        if (empty($username) || empty($password)) {
            $errMessage = "Please fill all required fields";
            header("location: index.php?menu=login&message=" . $errMessage);
        } else {
            $staff = $this->staffServices->getOneStaffByUsername($username);
            if ($staff && $staff->getUsername() != null) {
                if ($staff->getResignDate() == null && password_verify($password, $staff->getPasswordHash())) {
                    $successMessage = "Login successfully";
                    $_SESSION['username'] = $staff->getUsername();
                    $_SESSION['posisi'] = $staff->getPosisi();
                    if ($staff->getPosisi() == "Manager") {
                        header("location: index.php?menu=staff?message=" . $successMessage);
                    } else {
                        header("location: index.php?menu=reservasi?message=" . $successMessage);
                    }
                } else {
                    $errMessage = "Incorrect password";
                    header("location: index.php?menu=login&message=" . $errMessage);
                }
            } else {
                $errMessage = "Incorrect username";
                header("location: index.php?menu=login&message=" . $errMessage . "&username=" . $username . "&password=" . $password);
            }
        }
    }
    
    public function logout() : void
    {
        session_destroy();
        header("location: index.php?menu=login");
    }
}
