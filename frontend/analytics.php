<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h1>📊 Analytics Page</h1>

<p>✔ Page active</p>

<canvas id="chart"></canvas>

<script>
const ctx = document.getElementById("chart");

new Chart(ctx, {
    type: "bar",
    data: {
        labels: ["A", "B", "C"],
        datasets: [{
            label: "Test",
            data: [10, 20, 30]
        }]
    }
});
</script>

</body>
</html>