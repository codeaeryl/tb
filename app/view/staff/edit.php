<?php
if (!isset($_SESSION['username']) || $_SESSION['posisi']!="Manager") {
    header("Location: index.php?menu=login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>template</title>
    <!-- Import jquery -->
    <script type="text/javascript" src="../../../public/js/jquery-3.7.1.min.js"></script>
    <!-- import Javascript DataTables-->
    <script type="text/javascript" src="../../../public/js/datatables.min.js"></script>
    <link rel="stylesheet" href="../../../public/css/datatables.min.css">
    <!-- Import front-end stuff -->
    <link rel="stylesheet" href="../../../public/css/form.css" type="text/css">
    <script type="text/javascript" src="../../../public/js/index.js" defer></script>
</head>
<body>
<!-- sidebar -->
<nav id="sidebar">
    <ul>
        <!-- name + logo -->
        <li>
            <span class="logo">
                <span class="img-div">
                    <img src="../../../public/img/nadine_logo.png" alt="NDNE">
                </span>
                Nadine Hotel
            </span>
            <!-- close sidebar btn -->
            <button onclick=toggleSidebar() id="toggle-btn">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M440-240 200-480l240-240 56 56-183 184 183 184-56 56Zm264 0L464-480l240-240 56 56-183 184 183 184-56 56Z"/></svg>
            </button>
        </li>
        <!-- Dropdown Tables -->
        <li>
            <button onclick=toggleSubMenu(this) class="dropdown-btn" id="crud">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm240-240H200v160h240v-160Zm80 0v160h240v-160H520Zm-80-80v-160H200v160h240Zm80 0h240v-160H520v160ZM200-680h560v-80H200v80Z"/></svg>
                <span>TABLES</span>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-344 240-584l56-56 184 184 184-184 56 56-240 240Z"/></svg>
            </button>
            <ul class="sub-menu">
                <div>
                    <li><a href="index.php?menu=staff" class="table-link">Staff</a></li>
                    <li><a href="index.php?menu=log_staff_activity" class="table-link">Log Staff Activity</a></li>
                </div>
            </ul>
        </li>
        <li>
            <a href="index.php?menu=laporan-bor" class="table-link">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M80-200v-240q0-27 11-49t29-39v-112q0-50 35-85t85-35h160q23 0 43 8.5t37 23.5q17-15 37-23.5t43-8.5h160q50 0 85 35t35 85v112q18 17 29 39t11 49v240h-80v-80H160v80H80Zm440-360h240v-80q0-17-11.5-28.5T720-680H560q-17 0-28.5 11.5T520-640v80Zm-320 0h240v-80q0-17-11.5-28.5T400-680H240q-17 0-28.5 11.5T200-640v80Zm-40 200h640v-80q0-17-11.5-28.5T760-480H200q-17 0-28.5 11.5T160-440v80Zm640 0H160h640Z"/></svg>
                <span>Laporan BOR</span>
            </a>
        </li>
        <li>
            <a href="index.php?menu=laporan-pendapatan" class="table-link">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M441-120v-86q-53-12-91.5-46T293-348l74-30q15 48 44.5 73t77.5 25q41 0 69.5-18.5T587-356q0-35-22-55.5T463-458q-86-27-118-64.5T313-614q0-65 42-101t86-41v-84h80v84q50 8 82.5 36.5T651-650l-74 32q-12-32-34-48t-60-16q-44 0-67 19.5T393-614q0 33 30 52t104 40q69 20 104.5 63.5T667-358q0 71-42 108t-104 46v84h-80Z"/></svg>
                <span>Laporan Pendapatan</span>
            </a>
        </li>
        <li>
            <a href="index.php?menu=performa-promo" class="table-link">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-80q-24 0-46-9t-39-26q-29-29-50-38t-63-9q-50 0-85-35t-35-85q0-42-9-63t-38-50q-17-17-26-39t-9-46q0-24 9-46t26-39q29-29 38-50t9-63q0-50 35-85t85-35q42 0 63-9t50-38q17-17 39-26t46-9q24 0 46 9t39 26q29 29 50 38t63 9q50 0 85 35t35 85q0 42 9 63t38 50q17 17 26 39t9 46q0 24-9 46t-26 39q-29 29-38 50t-9 63q0 50-35 85t-85 35q-42 0-63 9t-50 38q-17 17-39 26t-46 9Zm0-80q8 0 15.5-3.5T508-172q41-41 77-55.5t93-14.5q17 0 28.5-11.5T718-282q0-58 14.5-93.5T788-452q12-12 12-28t-12-28q-41-41-55.5-77T718-678q0-17-11.5-28.5T678-718q-58 0-93.5-14.5T508-788q-5-5-12.5-8.5T480-800q-8 0-15.5 3.5T452-788q-41 41-77 55.5T282-718q-17 0-28.5 11.5T242-678q0 58-14.5 93.5T172-508q-12 12-12 28t12 28q41 41 55.5 77t14.5 93q0 17 11.5 28.5T282-242q58 0 93.5 14.5T452-172q5 5 12.5 8.5T480-160Zm100-160q25 0 42.5-17.5T640-380q0-25-17.5-42.5T580-440q-25 0-42.5 17.5T520-380q0 25 17.5 42.5T580-320Zm-202-2 260-260-56-56-260 260 56 56Zm2-198q25 0 42.5-17.5T440-580q0-25-17.5-42.5T380-640q-25 0-42.5 17.5T320-580q0 25 17.5 42.5T380-520Zm100 40Z"/></svg>
                <span>Laporan Performa Promo</span>
            </a>
        </li>
        <li>
        <li>
            <a href="index.php?menu=kamar-populer" class="table-link">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M120-120v-80h80v-640h400v40h160v240h-80v-160h-80v240h-80v-280H280v560h200v80H120Zm560 40-12-60q-12-5-22.5-11T625-165l-58 20-40-69 45-40q-2-15-2-25.5t2-25.5l-45-40 40-69 58 20q10-8 20.5-14.5T668-420l12-60h80l12 60q12 5 22.5 11t20.5 14l58-20 40 69-45 40q2 15 2 25.5t-2 25.5l45 40-40 69-58-19q-10 8-20.5 14T772-140l-12 60h-80Zm40-120q33 0 56.5-23.5T800-280q0-33-23.5-56.5T720-360q-33 0-56.5 23.5T640-280q0 33 23.5 56.5T720-200ZM440-440q-17 0-28.5-11.5T400-480q0-17 11.5-28.5T440-520q17 0 28.5 11.5T480-480q0 17-11.5 28.5T440-440ZM280-200v-560 560Z"/></svg>
                <span>Laporan TK Populer</span>
            </a>
        </li>
        </li>
        <!-- Logout -->
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
        <h1>Nadine Hotel Manager</h1>
        <p>Account: <?php echo $_SESSION['username']?></p>
    </header>
    <div class="container">
        <h1>Edit Staff <?=$staff->getStaffId()?></h1>
        <form action="index.php?menu=staff-update" method="POST">
            <?php
            if (isset($_GET['message'])) {
                echo "<div class='err-msg'>";
                echo '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg><span>';
                echo htmlspecialchars($_GET['message']);
                echo "</span></div>";
            }?>
            <input type="hidden" id="staff_id" name="staff_id" value="<?=$staff->getStaffId()?>">
            <label for="nama_staff">Nama Staff:</label>
            <input type="text" id="nama_staff" name="nama_staff" placeholder="Name" maxlength="100" value="<?=$staff->getNamaStaff();?>" required>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username" maxlength="32" value="<?=$staff->getUsername();?>" required>
            <input type="hidden" id="old_password" name="old_password" value="<?=$staff->getPasswordHash();?>">
            <label for="password">New Password:</label>
            <input type="text" id="new_password" name="password" placeholder="Password" maxlength="24">
            <label for="password_confirm">Konfirmasi Password:</label>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirm Password" maxlength="24">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email" maxlength="50" value="<?=$staff->getEmail();?>" required>
            <label for="posisi">Posisi:</label>
            <input type="text" id="posisi" name="posisi" value="<?=$staff->getPosisi();?>" readonly>
            <label for="status_akun">Status Akun:</label>
            <select id="status_akun" name="status_akun" required>
            <?php if($staff->getStatusAkun() == "Active") {?>
                <option value='Active' selected>Active</option>
                <option value="Not Active">Tidak Aktif</option>
            <?php } else { ?>
                <option value="Active">>Aktif</option>
                <option value="Not Active" selected>Tidak Aktif</option>
            <?php }?>
            </select>
            <button type="submit" class="submit-form-btn">Submit</button>
        <form>
    </div>
    </main>
</body>
</html>