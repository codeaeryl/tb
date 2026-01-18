<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['username']) && isset($_SESSION['posisi'])) {
    if ($_SESSION['posisi'] == "Manager") {
        header("Location: index.php?menu=staff");
        exit;
    } else {
        header("Location: index.php?menu=reservasi");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Nadine Hotel Staff</title>
    <link rel="stylesheet" href="../../public/css/login.css">
</head>
<body>
<div class="container">
    <form action="index.php?menu=verify-login" method="POST" class="login-email">
        <h1>Login</h1>
        <?php 
        if (isset($_GET['message'])) {
            echo "<div class='err-msg'>";
            echo '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg><span>';
            echo htmlspecialchars($_GET['message']);
            echo "</span></div>";
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