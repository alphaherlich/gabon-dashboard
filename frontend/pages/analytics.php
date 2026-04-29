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
    <title>Analytics 🇬🇦</title>

    <link rel="stylesheet" href="../assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- 🔥 WebSocket (IMPORTANT MULTI USER) -->
    <script src="https://cdn.jsdelivr.net/npm/sockjs-client@1/dist/sockjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/stompjs@2.3.3/lib/stomp.min.js"></script>
</head>

<body>

<div class="main">
    <h1>📊 Analytics Dashboard</h1>

    <!-- 📈 GLOBAL TREND -->
    <div id="trendBox" class="trend-box">
        📡 Chargement de la tendance globale...
    </div>

    <div class="grid">

        <!-- 📈 EVOLUTION -->
        <div class="chart-box">
            <h3>📈 Évolution par année</h3>
            <canvas id="evolutionChart"></canvas>
        </div>

        <!-- 📊 REGION GROWTH -->
        <div class="chart-box">
            <h3>📊 Croissance par région</h3>
            <canvas id="regionChart"></canvas>
        </div>

        <!-- 📊 YEAR COMPARISON -->
        <div class="chart-box">
            <h3>📊 Comparaison années</h3>
            <canvas id="yearChart"></canvas>
        </div>

    </div>

    <a href="../dashboard.php">⬅ Retour Dashboard</a>
</div>

<script>
const API_URL = "http://localhost:8080/api/stats";
const API_EVOLUTION = "http://localhost:8080/api/stats/evolution";

// =========================
// 📈 EVOLUTION
// =========================
fetch(API_EVOLUTION)
.then(res => res.json())
.then(data => {

    new Chart(document.getElementById("evolutionChart"), {
        type: "line",
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: "Évolution globale",
                data: Object.values(data),
                borderWidth: 2,
                tension: 0.4
            }]
        }
    });
});

// =========================
// 📊 REGION GROWTH
// =========================
fetch("http://localhost:8080/api/stats/analytics/region-growth")
.then(res => res.json())
.then(data => {

    new Chart(document.getElementById("regionChart"), {
        type: "bar",
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: "Croissance par région",
                data: Object.values(data)
            }]
        }
    });
});

// =========================
// 📊 YEAR COMPARISON
// =========================
fetch("http://localhost:8080/api/stats/analytics/year-comparison")
.then(res => res.json())
.then(data => {

    new Chart(document.getElementById("yearChart"), {
        type: "line",
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: "Comparaison années",
                data: Object.values(data),
                borderWidth: 2,
                tension: 0.3
            }]
        }
    });
});

// =========================
// 🏆 TOP 3 REGIONS (LOG ONLY)
// =========================
fetch("http://localhost:8080/api/stats/analytics/top-regions")
.then(res => res.json())
.then(data => {
    console.log("🏆 TOP 3 REGIONS:", data);
});

// =========================
// 📈 GLOBAL TREND
// =========================
fetch("http://localhost:8080/api/stats/analytics/trend")
.then(res => res.text())
.then(data => {

    document.getElementById("trendBox").innerText =
        "📊 Tendance globale : " + data;
});


// =========================
// 🔥 MULTI-USER WEBSOCKET LIVE
// =========================
function connectWebSocket() {

    const socket = new SockJS("http://localhost:8080/ws");
    const stompClient = Stomp.over(socket);

    stompClient.connect({}, function () {

        console.log("🔥 MULTI USER CONNECTED");

        // 📡 UPDATE GLOBAL DASHBOARD
        stompClient.subscribe("/topic/dashboard", function (message) {

            const data = JSON.parse(message.body);

            console.log("🚀 GLOBAL UPDATE:", data);

            // 🔁 refresh analytics auto
            location.reload();
        });

        // ❌ DELETE LIVE SYNC
        stompClient.subscribe("/topic/dashboard-delete", function (message) {

            const id = message.body;

            console.log("❌ DELETE LIVE:", id);

            // 🔁 refresh analytics auto
            location.reload();
        });
    });
}

// 🚀 INIT WEBSOCKET
connectWebSocket();

</script>

</body>
</html>