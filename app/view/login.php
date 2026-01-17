<?php

if(!isset($_SESSION)){
    session_start();
}
if (isset($_SESSION['username']) && isset($_SESSION['posisi'])) {
    if ($_SESSION['posisi'] == "Manager") {
        header("Location: index.php?menu=staff");
    } else {
        header("Location: index.php?menu=reservasi");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Nadine Hotel Staff</title>
    <link rel="stylesheet" href="../../public/scripts/staff/login.css">
</head>
<body>
<div class="container">
    <form action="index.php?menu=verify-login" method="POST" class="login-email">
        <h1>Login</h1>
        <?php 
        if (isset($_GET['message'])) {
            echo "<div class='err-msg'>";
            echo $_GET['message'];
            echo '</div>';
        }?>
        <div class="input-group">
            <input type="text" placeholder="Username" name="username" id="username" required>
        </div>
        <div class="input-group">
            <input type="password" placeholder="Password" name="password" id="password" required>
        </div>
        <div class="input-group">
            <button name="submit" class="submit-form-btn">Login</button>
        </div>
    </form>
</div>
</body>
</html>