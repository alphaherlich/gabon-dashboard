<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = ($_SESSION['role'] === "admin");
?>

<!DOCTYPE html>
<html>
<head>
    <title>🇬🇦 Gabon Data Intelligence</title>

    <link rel="stylesheet" href="assets/style.css">

   <!-- 📊 Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- 🔥 PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- 🔥 WEBSOCKET -->
<script src="https://cdn.jsdelivr.net/npm/sockjs-client@1/dist/sockjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/stompjs@2.3.3/lib/stomp.min.js"></script>
    <style>
        .role-box {
            color: white;
            padding: 10px;
            font-weight: bold;
        }

        .role-click {
            cursor: pointer;
            color: #00d4ff;
        }

        #roleMenu {
            display: none;
            background: #111;
            color: white;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
        }

        #roleMenu a, #roleMenu p {
            display: block;
            margin: 5px 0;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

        #roleMenu a:hover {
            color: #00d4ff;
        }

        /* 🔥 TABLE ADMIN */
        .admin-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th, .admin-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
        }

        .btn-delete {
            background: red;
            color: white;
            border: none;
            padding: 5px;
            cursor: pointer;
        }

        .btn-edit {
            background: orange;
            color: white;
            border: none;
            padding: 5px;
            cursor: pointer;
        }
    </style>

</head>

<body>

<div class="sidebar">
    <h2>🇬🇦 Gabon AI</h2>

    <a href="#">🏠 Dashboard</a>
    <a href="#">📊 Économie</a>
    <a href="#">🏥 Santé</a>
    <a href="#">🏫 Éducation</a>

    <hr>

    <!-- ROLE -->
    <div class="role-box role-click" onclick="toggleRoleMenu()">
        👤 Role: <?= $_SESSION['role'] ?> ⬇
    </div>
    <div id="roleMenu">

        <?php if ($isAdmin): ?>

            <!-- 🔥 MENU ADMIN COMPLET -->
            <a href="#" onclick="showAddForm()">➕ Ajouter données</a>
            <a href="#" onclick="deleteMode()">❌ Mode suppression</a>
            <a href="analytics.php">📊 Analytics avancées</a>

        <?php else: ?>
            <p>🔒 Mode lecture seule</p>
        <?php endif; ?>

    </div>

    <hr>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">

    <h1>📊 Gabon Data Intelligence Dashboard</h1>
    <p class="subtitle">Analyse socio-économique en temps réel 🇬🇦</p>

    <div class="card">
    <h3>🏆 Top régions</h3>
    <ul id="topRegions"></ul>
</div>
    
 <div class="top-bar">

    <!-- 🎯 FILTRES -->
    <div class="filters-pro">
        <select id="categoryFilter">
            <option value="ALL">📊 Toutes</option>
            <option value="ECONOMY">💰 Économie</option>
            <option value="HEALTH">🏥 Santé</option>
            <option value="EDUCATION">🎓 Éducation</option>
        </select>

        <select id="regionFilter">
            <option value="ALL">🌍 Régions</option>
            <option value="Libreville">Libreville</option>
            <option value="Port-Gentil">Port-Gentil</option>
        </select>
    <div class="top-bar">

    <div class="filters-pro">
        ...
    </div>

    <div class="actions">
        <button onclick="exportExcel()">📊 Excel</button>
        <button onclick="exportPDF()">📄 PDF</button>
    </div>

</div>

<!-- 🔥 DASHBOARD GRID -->
<div class="dashboard-grid">

    <div class="card">
        <h3>📊 Multi-Catégories</h3>
        <canvas id="multiChart"></canvas>
    </div>

    <div class="card">
        <h3>🔥 Heatmap Régions</h3>
        <canvas id="heatmapChart"></canvas>
    </div>

</div>

<!-- KPI -->
<div class="cards">

    <div class="card highlight">
        <h3>💰 Total</h3>
        <p id="total">...</p>
    </div>

    <div class="card">
        <h3>📊 Moyenne</h3>
        <p id="average">...</p>
    </div>

    <div class="card">
        <h3>📍 Région dominante</h3>
        <p id="regionTop">...</p>
    </div>

</div>

<!-- LOADER -->
<div id="loader" class="loader"></div>


    <!-- FORM -->
    <?php if ($isAdmin): ?>
    <div class="card form-card">
        <h2>➕ Ajouter</h2>

        <input id="categoryInput" placeholder="Catégorie">
        <input id="titleInput" placeholder="Titre">
        <input id="valueInput" type="number" placeholder="Valeur">
        <input id="yearInput" type="number" placeholder="Année">
        <input id="regionInput" placeholder="Région">

        <button onclick="addData()">Ajouter</button>
    </div>
    <?php endif; ?>

    <!-- 🔥 TABLE ADMIN (CONNECTÉ AU JS) -->
    <?php if ($isAdmin): ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Valeur</th>
                <th>Action</th>
            </tr>
        </thead>

        <!-- 🔥 IMPORTANT -->
        <tbody id="dataTable"></tbody>

    </table>
    <?php endif; ?>

    <!-- CHART -->
    <div class="grid">

        <div class="chart-box">
            <h3>📊 Économie</h3>
            <canvas id="chart"></canvas>
        </div>

        <div class="chart-box">
            <h3>📈 Évolution</h3>
            <canvas id="evolutionChart"></canvas>
        </div>

    </div>



<!-- 🔥 MENU FIX -->
<script>
function toggleRoleMenu() {
    const menu = document.getElementById("roleMenu");
    if (!menu) return;

    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

document.addEventListener("click", function(e) {
    const menu = document.getElementById("roleMenu");
    const role = document.querySelector(".role-click");

    if (!menu || !role) return;

    if (!menu.contains(e.target) && !role.contains(e.target)) {
        menu.style.display = "none";
    }
});
</script>

<!-- 🔥 TON JS PRINCIPAL (OBLIGATOIRE) -->
<script src="assets/app.js"></script>

</body>
</html>
