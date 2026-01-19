<?php
include_once __DIR__ . "/../config/JadwalServices.php";
include_once __DIR__ . "/../config/ReservasiServices.php";

class JadwalController
{
    private JadwalServices $jadwalServices;
    private ReservasiServices $reservasiServices;

    public function __construct()
    {
        $this->jadwalServices = new JadwalServices();
        $this->reservasiServices = new ReservasiServices();
    }

    public function checkin(): void
    {
        $checkins = $this->jadwalServices->getCheckinToday();
        include __DIR__ . "/../view/reservasi/checkin_today.php";
        exit;
    }

    public function checkout(): void
    {
        $checkouts = $this->jadwalServices->getCheckoutToday();
        include __DIR__ . "/../view/reservasi/checkout_today.php";
        exit;
    }

    public function processCheckin(): void
    {
        $id = filter_input(INPUT_POST, 'id_reservasi', FILTER_SANITIZE_STRING);
        if ($id && $this->reservasiServices->checkinReservasi($id)) {
            header("Location: index.php?menu=check-in-today&success=Check-in successful");
        } else {
            header("Location: index.php?menu=check-in-today&message=Check-in failed");
        }
        exit;
    }

    public function processCheckout(): void
    {
        $id = filter_input(INPUT_POST, 'id_reservasi', FILTER_SANITIZE_STRING);
        if ($id && $this->reservasiServices->checkoutReservasi($id)) {
            header("Location: index.php?menu=check-out-today&success=Check-out successful");
        } else {
            header("Location: index.php?menu=check-out-today&message=Check-out failed");
        }
        exit;
    }

    public function processCancel(): void
    {
        $id = filter_input(INPUT_POST, 'id_reservasi', FILTER_SANITIZE_STRING);
        if ($id && $this->reservasiServices->cancelReservasi($id)) {
            header("Location: index.php?menu=check-in-today&success=Reservation cancelled");
        } else {
            header("Location: index.php?menu=check-in-today&message=Cancellation failed");
        }
        exit;
    }
}