<?php
if (!isset($_SESSION['username']) || $_SESSION['posisi'] != 'Staff') {
    header("Location: index.php?menu=login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi List</title>
    <script type="text/javascript" src="../../../public/js/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="../../../public/js/datatables.min.js"></script>
    <link rel="stylesheet" href="../../../public/css/datatables.min.css">
    <link rel="stylesheet" href="../../../public/css/style.css" type="text/css">
    <script type="text/javascript" src="../../../public/js/index.js" defer></script>
</head>
<body>
<nav id="sidebar">
    <ul>
        <li>
            <span class="logo">
                <span class="img-div">
                    <img src="../../../public/img/nadine_logo.png" alt="NDNE">
                </span>
                Nadine Hotel
            </span>
            <button onclick=toggleSidebar() id="toggle-btn">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"/></svg>
            </button>
        </li>
        <li>
            <a href="index.php?menu=reservasi" class="table-link">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M580-240q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z"/></svg>
                <span>Reservasi</span>
            </a>
        </li>
        <li>
            <a href="index.php?menu=kamar" class="table-link">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M80-200v-240q0-27 11-49t29-39v-112q0-50 35-85t85-35h160q23 0 43 8.5t37 23.5q17-15 37-23.5t43-8.5h160q50 0 85 35t35 85v112q18 17 29 39t11 49v240h-80v-80H160v80H80Zm440-360h240v-80q0-17-11.5-28.5T720-680H560q-17 0-28.5 11.5T520-640v80Zm-320 0h240v-80q0-17-11.5-28.5T400-680H240q-17 0-28.5 11.5T200-640v80Zm-40 200h640v-80q0-17-11.5-28.5T760-480H200q-17 0-28.5 11.5T160-440v80Zm640 0H160h640Z"/></svg>
                <span>Kamar</span>
            </a>
        </li>
        <li>
            <a href="index.php?menu=maintenance" class="table-link">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M280-720v-40q0-33 23.5-56.5T360-840h240q33 0 56.5 23.5T680-760v40h28q24 0 43.5 13.5T780-672l94 216q3 8 4.5 16t1.5 16v184q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-184q0-8 1.5-16t4.5-16l94-216q9-21 28.5-34.5T252-720h28Zm80 0h240v-40H360v40Zm-80 240v-40h80v40h240v-40h80v40h96l-68-160H252l-68 160h96Zm0 80H160v160h640v-160H680v40h-80v-40H360v40h-80v-40Zm200-40Zm0-40Zm0 80Z"/></svg>
                <span>Maintenance</span>
            </a>
        </li>
        <li>
            <a href="index.php?menu=check-in-today" class="table-link">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v255l-80 80v-175H200v400h248l80 80H200Zm0-560h560v-80H200v80Zm0 0v-80 80ZM662-60 520-202l56-56 85 85 170-170 56 57L662-60Z"/></svg>
                <span>Check In Today</span>
            </a>
        </li>
        <li>
            <a href="index.php?menu=check-out-today" class="table-link">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M200-120q-33 0-56.5-23.5T120-200v-160h80v160h560v-560H200v160h-80v-160q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm220-160-56-58 102-102H120v-80h346L364-622l56-58 200 200-200 200Z"/></svg>
                <span>Check Out Today</span>
            </a>
        </li>
        <li>
            <a href="index.php?menu=logout" class="table-link">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</nav>
<main style="position: relative;">
    <header>
        <h1>Nadine Hotel Staff</h1>
        <p>Account: <?php echo $_SESSION['username']?></p>
    </header>
    <div class="container">
        <h1>Daftar Kamar</h1>
        <table id="main-table" class="stripe">
            <thead>
            <tr>
                <th>Nomor Kamar</th>
                <th>Tipe Kamar</th>
                <th>Deskripsi</th>
                <th>Harga Dasar</th>
                <th>Kapasitas</th>
                <th>Harga / Malam</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($kamars as $kamar): ?>
            <tr>
                <td><?= $kamar->getNomorKamar() ?></td>
                <td><?= $kamar->getNamaTipeKamar() ?></td>
                <td><?= $kamar->getDescTipeKamar() ?></td>
                <td>Rp <?= number_format($kamar->getHargaDasar(), 2) ?></td>
                <td><?= $kamar->getKapasitasMaksimum() ?></td>
                <td>Rp <?= number_format($kamar->getHargaPerOrang(), 2) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <script>
            new DataTable('#main-table');
        </script>
    </div>
</main>
</body>
</html>
