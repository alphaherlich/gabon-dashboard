<?php
session_start();

// 🔗 connexion DB
$conn = new mysqli("localhost", "root", "", "gabon_dashboard", 3306);

if ($conn->connect_error) {
    die("Erreur DB");
}

if ($_POST) {

    // 🔒 sécurité basique anti injection
    $username = trim($_POST['user']);
    $password = trim($_POST['pass']);

    // ⚠️ requête sécurisée (important)
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        // 🟢 ROLE SYSTEM (IMPORTANT)
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        // 🧠 LOG DEBUG
        error_log("LOGIN SUCCESS: " . $user['username'] . " ROLE: " . $user['role']);

        header("Location: dashboard.php");
        exit();

    } else {
        echo "<div class='error'>❌ Mauvais identifiants</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Login</title>

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<style>
/* 🔥 TON CSS INCHANGÉ (aucune modification) */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: url('https://images.unsplash.com/photo-1501785888041-af3ef285b470') no-repeat;
    background-size: cover;
    background-position: center;
}

.wrapper {
    width: 400px;
    padding: 40px;
    border-radius: 15px;
    background: rgba(255,255,255,0.1);
    border: 2px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(25px);
    box-shadow: 0 0 30px rgba(0,0,0,0.4);
    color: #fff;
}

.wrapper h2 {
    text-align: center;
    margin-bottom: 25px;
}

.input-box {
    position: relative;
    margin: 20px 0;
}

.input-box input {
    width: 100%;
    height: 50px;
    padding: 0 45px 0 15px;
    border-radius: 40px;
    border: 2px solid rgba(255,255,255,0.2);
    background: transparent;
    color: #fff;
    outline: none;
}

.input-box input::placeholder {
    color: #eee;
}

.input-box i {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #fff;
}

button {
    width: 100%;
    height: 45px;
    border-radius: 40px;
    border: none;
    background: #fff;
    color: #333;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
}

.error {
    position: absolute;
    top: 20px;
    color: red;
    font-weight: bold;
}
</style>

</head>

<body>

<div class="wrapper">

<form method="POST">
    <h2>Connexion</h2>

    <div class="input-box">
        <input type="text" name="user" placeholder="Username">
        <i class='bx bxs-user'></i>
    </div>

    <div class="input-box">
        <input type="password" name="pass" placeholder="Password">
        <i class='bx bxs-lock-alt'></i>
    </div>

    <button type="submit">Login</button>
</form>

</div>

</body>
</html>