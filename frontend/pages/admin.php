<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel 🇬🇦</title>

    <link rel="stylesheet" href="../assets/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sockjs-client@1/dist/sockjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/stompjs@2.3.3/lib/stomp.min.js"></script>
</head>

<body>

<div class="main">
    <h1>🛠️ Admin Panel</h1>

    <!-- ➕ AJOUT -->
    <div class="card form-card">
        <h2>Ajouter une donnée</h2>

        <input id="categoryInput" placeholder="Catégorie">
        <input id="titleInput" placeholder="Titre">
        <input id="valueInput" type="number" placeholder="Valeur">
        <input id="yearInput" type="number" placeholder="Année">
        <input id="regionInput" placeholder="Région">

        <button onclick="addData()">Ajouter</button>
    </div>

    <!-- 📋 LISTE -->
    <div class="card">
        <h2>📋 Données enregistrées</h2>
        <ul id="dataList"></ul>
    </div>

    <a href="../dashboard.php">⬅ Retour Dashboard</a>
</div>

<script>
const API_URL = "http://localhost:8080/api/stats";

// 🔥 LOAD DATA
function loadData() {
    fetch(API_URL)
    .then(res => res.json())
    .then(data => {

        const list = document.getElementById("dataList");
        list.innerHTML = "";

        data.forEach(d => {
            const li = document.createElement("li");
            li.innerText = `${d.title} (${d.value}) - ${d.region}`;
            list.appendChild(li);
        });
    });
}

// 🔥 ADD
function addData() {

    const data = {
        category: document.getElementById("categoryInput").value,
        title: document.getElementById("titleInput").value,
        value: parseFloat(document.getElementById("valueInput").value),
        year: parseInt(document.getElementById("yearInput").value),
        region: document.getElementById("regionInput").value
    };

    fetch(API_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(() => {
        alert("✔ Ajout réussi");
        loadData();
    });
}

// INIT
loadData();
</script>

</body>
</html>