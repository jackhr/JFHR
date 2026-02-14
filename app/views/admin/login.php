<?php
$pageTitle = 'Admin Login';
$bodyClass = 'login-body';
include_once __DIR__ . "/../partials/header.php";
?>

<form id="login-form">
    <div>
        <img src="/assets/images/logo.png" alt="Main Logo">
    </div>

    <div class="input-container">
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
    </div>
    <div class="input-container">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>

    <button type="submit" class="continue-btn">Login</button>

</form>

</body>

</html>
