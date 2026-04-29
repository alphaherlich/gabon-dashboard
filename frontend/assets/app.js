const API_URL = "http://localhost:8080/api/stats";
const API_EVOLUTION = "http://localhost:8080/api/stats/evolution";
let barChart = null;
let lineChart = null;
let stompClient = null;

// 🔥 LOADER
function showLoader() {
    const el = document.getElementById("loader");
    if (el) el.style.display = "block";
}

function hideLoader() {
    const el = document.getElementById("loader");
    if (el) el.style.display = "none";
}

// =========================
// 🔥 ADD DATA
// =========================
window.addData = function () {

    const data = {
        category: document.getElementById("categoryInput").value.trim(),
        title: document.getElementById("titleInput").value.trim(),
        value: parseFloat(document.getElementById("valueInput").value),
        year: parseInt(document.getElementById("yearInput").value),
        region: document.getElementById("regionInput").value.trim()
    };

    if (!data.category || !data.title || isNaN(data.value) || isNaN(data.year) || !data.region) {
        alert("⚠️ Remplis correctement tous les champs !");
        return;
    }

    fetch(API_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(() => {
        alert("✔ Donnée ajoutée");
        loadDashboard();

        ["categoryInput","titleInput","valueInput","yearInput","regionInput"]
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = "";
        });
    })
    .catch(err => {
        console.error(err);
        alert("Erreur backend");
    });
};

// =========================
// 🚀 INIT
// =========================
document.addEventListener("DOMContentLoaded", () => {
    loadDashboard();
    connectWebSocket();
});

// =========================
// 📊 DASHBOARD
// =========================
function loadDashboard() {
    loadStats();
    loadEvolution();
}

// =========================
// 📊 STATS (UPDATED)
// =========================
function loadStats() {

    showLoader(); // 🔥 START LOADER

    fetch(API_URL)
        .then(res => res.json())
        .then(data => {

          if (!Array.isArray(data)) {
    hideLoader();
    return;
}

            // 🔥 NOUVELLES FEATURES
            loadMultiChart(data);
            updateTopRegions(data);
            loadHeatmap(data);

            const economy = data.filter(d =>
                (d.category || "").toUpperCase() === "ECONOMY"
            );

            const total = data.reduce((sum, d) => sum + (d.value || 0), 0);
            const avg = data.length ? (total / data.length).toFixed(2) : 0;

            const totalEl = document.getElementById("total");
            const avgEl = document.getElementById("average");
            const insightEl = document.getElementById("insight");
            const regionEl = document.getElementById("regionTop");

            if (totalEl) totalEl.innerText = total;
            if (avgEl) avgEl.innerText = avg;

            if (data.length > 0 && insightEl) {
                const max = data.reduce((a, b) => a.value > b.value ? a : b);
                insightEl.innerText = `📈 ${max.title} domine (${max.value})`;
            }

            const regions = {};
            data.forEach(d => {
                regions[d.region] = (regions[d.region] || 0) + (d.value || 0);
            });

            const topRegion = Object.keys(regions).reduce((a, b) =>
                regions[a] > regions[b] ? a : b, "N/A"
            );

            if (regionEl) regionEl.innerText = topRegion;

            // =========================
            // 📊 TABLE
            // =========================
            const table = document.getElementById("dataTable");

            if (table) {
                table.innerHTML = "";

                data.forEach(d => {
                    table.innerHTML += `
                        <tr>
                            <td>${d.id}</td>
                            <td>${d.title}</td>
                            <td>${d.value}</td>
                            <td>
                                <button onclick="deleteData(${d.id})">❌</button>
                                <button onclick="updateData(${d.id})">✏️</button>
                            </td>
                        </tr>
                    `;
                });
            }

            // =========================
// 📊 CHART
// =========================
const ctx = document.getElementById("chart");

if (!ctx) {
    hideLoader(); // 🔥 évite loader bloqué
    return;
}

            if (barChart) barChart.destroy();

            barChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: economy.map(d => d.title),
                    datasets: [{
                        label: "Économie",
                        data: economy.map(d => d.value),
                        backgroundColor: "#3b82f6"
                    }]
                }
            });

            barChart._ids = economy.map(d => d.id);

        })
        .catch(err => console.error("STATS ERROR:", err))
        .finally(() => hideLoader()); // 🔥 STOP LOADER
}
// =========================
// 📈 EVOLUTION
// =========================
function loadEvolution() {

    fetch(API_EVOLUTION)
        .then(res => res.json())
        .then(data => {

            const ctx = document.getElementById("evolutionChart");

            if (!ctx) return;

            if (lineChart) lineChart.destroy();

            lineChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        label: "Évolution",
                        data: Object.values(data),
                        borderColor: "#10b981",
                        tension: 0.4
                    }]
                }
            });
        });
}

// =========================
// ❌ DELETE DATA (FIXED)
// =========================
window.deleteData = function (id) {

    if (!confirm("Supprimer cette donnée ?")) return;

    fetch(`${API_URL}/${id}`, {
        method: "DELETE"
    })
    .then(() => {


        alert("❌ Supprimé");
        loadDashboard();
    })
    .catch(err => console.error(err));
};

// =========================
// ✏️ UPDATE DATA
// =========================
window.updateData = function (id) {

    const value = prompt("Nouvelle valeur ?");

    if (!value) return;

    fetch(`${API_URL}/${id}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ value: parseFloat(value) })
    })
    .then(() => {
        alert("✏️ Modifié");
        loadDashboard();
    });
};

// =========================
// 🎯 DELETE MODE (FIX FINAL)
// =========================
function deleteMode() {

    alert("❌ Clique sur une barre");

    const ctx = document.getElementById("chart");

    if (!ctx || !barChart) return;

    ctx.onclick = function (evt) {

        const points = barChart.getElementsAtEventForMode(
            evt,
            'nearest',
            { intersect: true },
            true
        );

        if (!points.length) return;

        const index = points[0].index;

        // 🔥 FIX ICI
        const id = barChart._ids[index];

        console.log("DELETE ID:", id);

        deleteData(id);
    };
}

// =========================
// 🔥 WEBSOCKET
// =========================
function connectWebSocket() {

    const socket = new SockJS("http://localhost:8080/ws");
    stompClient = Stomp.over(socket);

    stompClient.connect({}, function () {

        console.log("🔥 WS CONNECTED");

       // ✅ AJOUT TEMPS RÉEL
stompClient.subscribe("/topic/dashboard", function (message) {

    const data = JSON.parse(message.body);

    console.log("🚀 LIVE ADD:", data);

    updateCharts(data); // 🔥 animation + update
});

// ✅ DELETE TEMPS RÉEL
stompClient.subscribe("/topic/dashboard-delete", function (message) {

    const id = JSON.parse(message.body);

    console.log("❌ LIVE DELETE:", id);

    removeFromChart(id); // 🔥 suppression fluide
});

    }); // ✅ IMPORTANT

} // ✅ IMPORTANT

// =========================
// 👤 ROLE MENU
// =========================
function toggleRoleMenu() {

    const menu = document.getElementById("roleMenu");

    if (!menu) return;

    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

// =========================
// 🛠 UI ADMIN
// =========================
function showAddForm() {

    const form = document.querySelector(".form-card");

    if (!form) return;

    form.scrollIntoView({ behavior: "smooth" });
    form.style.border = "2px solid #3b82f6";
}

// =========================
// 🎨 ULTRA PRO UI UPGRADE
// =========================

// 🔥 Animation KPI (effet pulse)
function animateKPI(element) {
    if (!element) return;

    element.style.transition = "transform 0.3s ease";
    element.style.transform = "scale(1.2)";

    setTimeout(() => {
        element.style.transform = "scale(1)";
    }, 300);
}


// =========================
// 📊 UPDATE CHART AVEC ANIMATION
// =========================
function smoothChartUpdate() {

    if (!barChart) return;

    barChart.options.animation = {
        duration: 800,
        easing: 'easeOutQuart'
    };

    barChart.update();
}

// =========================
// 🚀 SAFE DEFAULT FUNCTIONS (ANTI ERROR)
// =========================
if (typeof updateCharts !== "function") {
    function updateCharts(newData) {
        console.log("⚠️ fallback updateCharts", newData);
        loadDashboard(); // fallback propre
    }
}

if (typeof removeFromChart !== "function") {
    function removeFromChart(id) {
        console.log("⚠️ fallback removeFromChart", id);
        loadDashboard(); // fallback propre
    }
}
// =========================
// ✨ HIGHLIGHT NOUVELLE DONNÉE
// =========================
function highlightNewBar(index) {

    if (!barChart) return;

    const meta = barChart.getDatasetMeta(0);

    if (!meta.data[index]) return;

    const bar = meta.data[index];

    bar.options.backgroundColor = "#22c55e"; // vert highlight

    setTimeout(() => {
        bar.options.backgroundColor = "#3b82f6";
        barChart.update();
    }, 1500);
}


// =========================
// 🎯 AMÉLIORATION TOOLTIP
// =========================
function enhanceChartUI() {

    if (!barChart) return;

    barChart.options.plugins = {
        tooltip: {
            enabled: true,
            backgroundColor: "#111",
            titleColor: "#fff",
            bodyColor: "#00d4ff",
            borderColor: "#00d4ff",
            borderWidth: 1
        },
        legend: {
            labels: {
                color: "#fff"
            }
        }
    };

    barChart.update();
}

// =========================
// 🚀 HOOK DANS updateCharts (SAFE FINAL)
// =========================
const oldUpdateCharts = typeof updateCharts === "function" ? updateCharts : null;

updateCharts = function (newData) {

    if (oldUpdateCharts) {
        oldUpdateCharts(newData);
    } else {
        console.log("⚠️ fallback direct");
        loadDashboard();
    }

    // 🔥 Animation KPI
    animateKPI(document.getElementById("total"));

    // 🔥 Animation graphique
    smoothChartUpdate();

    // 🔥 Highlight dernière barre
    if (barChart && barChart.data.labels.length > 0) {
        highlightNewBar(barChart.data.labels.length - 1);
    }

    // 🔥 UI améliorée
    enhanceChartUI();
};


// =========================
// 🔥 HOOK DELETE ANIMÉ (SAFE FINAL)
// =========================
const oldRemove = typeof removeFromChart === "function" ? removeFromChart : null;

removeFromChart = function (id) {

    if (oldRemove) {
        oldRemove(id);
    } else {
        console.log("⚠️ fallback delete");
        loadDashboard();
    }

    smoothChartUpdate();
};



// =========================
// 🎯 HOVER INTERACTIF (CURSOR POINTER)
// =========================
document.addEventListener("mousemove", function (e) {

    const canvas = document.getElementById("chart");

    if (!canvas || !barChart) return;

    const points = barChart.getElementsAtEventForMode(
        e,
        'nearest',
        { intersect: true },
        false
    );

    canvas.style.cursor = points.length ? "pointer" : "default";
});


// =========================
// 🌟 EFFET SMOOTH AU LOAD
// =========================
window.addEventListener("load", () => {

    const cards = document.querySelectorAll(".card");

    cards.forEach((card, i) => {
        card.style.opacity = 0;
        card.style.transform = "translateY(20px)";

        setTimeout(() => {
            card.style.transition = "all 0.6s ease";
            card.style.opacity = 1;
            card.style.transform = "translateY(0)";
        }, i * 100);
    });
});
document.addEventListener("DOMContentLoaded", () => {

    const cat = document.getElementById("categoryFilter");
    const region = document.getElementById("regionFilter");

    if (cat) cat.addEventListener("change", applyFiltersLive);
    if (region) region.addEventListener("change", applyFiltersLive);
});

function applyFiltersLive() {

    fetch(API_URL)
        .then(res => res.json())
        .then(data => {

            const cat = document.getElementById("categoryFilter")?.value;
            const region = document.getElementById("regionFilter")?.value;

            let filtered = data;

            if (cat !== "ALL") {
                filtered = filtered.filter(d => d.category === cat);
            }

            if (region !== "ALL") {
                filtered = filtered.filter(d => d.region === region);
            }

            updateFilteredChart(filtered);
        });
}

function updateFilteredChart(data) {

    if (!barChart) return;

    barChart.data.labels = data.map(d => d.title);
    barChart.data.datasets[0].data = data.map(d => d.value);
    barChart._ids = data.map(d => d.id);

    smoothChartUpdate();
}

let multiChart = null;

function loadMultiChart(data) {

    const ctx = document.getElementById("multiChart");
    if (!ctx) return;

    const categories = ["ECONOMY", "HEALTH", "EDUCATION"];

    // 🔥 labels uniques (corrige ton bug)
    const labels = [...new Set(data.map(d => d.title))];

    const datasets = categories.map(cat => ({
        label: cat,
        data: labels.map(label => {
            const found = data.find(d => d.title === label && d.category === cat);
            return found ? found.value : 0;
        }),
        fill: false
    }));

    // 🔥 évite les bugs visuels
    if (multiChart) multiChart.destroy();

    multiChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: datasets
        }
    });
}

function updateTopRegions(data) {

    const container = document.getElementById("topRegions");
    if (!container) return;

    const regions = {};

    data.forEach(d => {
        regions[d.region] = (regions[d.region] || 0) + d.value;
    });

    const sorted = Object.entries(regions)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 5);

    container.innerHTML = sorted
        .map(r => `<li>${r[0]} - ${r[1]}</li>`)
        .join("");
}
let heatmapChart = null;

function loadHeatmap(data) {

    const ctx = document.getElementById("heatmapChart");
    if (!ctx) return;

    const regions = {};

    data.forEach(d => {
        regions[d.region] = (regions[d.region] || 0) + d.value;
    });

    // 🔥 IMPORTANT : éviter les bugs Chart.js
    if (heatmapChart) heatmapChart.destroy();

    heatmapChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: Object.keys(regions),
            datasets: [{
                label: "Heatmap régions",
                data: Object.values(regions)
            }]
        }
    });
}
function exportJSON() {

    fetch(API_URL)
        .then(res => res.json())
        .then(data => {

            const blob = new Blob(
                [JSON.stringify(data, null, 2)],
                { type: "application/json" }
            );

            const a = document.createElement("a");
            a.href = URL.createObjectURL(blob);
            a.download = "data.json";
            a.click();
        });
}

function exportExcel() {

    fetch(API_URL)
        .then(res => res.json())
        .then(data => {

            let csv = "ID,Category,Title,Value,Year,Region\n";

            data.forEach(d => {
                csv += `${d.id},${d.category},${d.title},${d.value},${d.year},${d.region}\n`;
            });

            const blob = new Blob([csv], { type: "text/csv" });

            const a = document.createElement("a");
            a.href = URL.createObjectURL(blob);
            a.download = "data.csv";
            a.click();
        });
}

async function exportPDF() {

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const res = await fetch(API_URL);
    const data = await res.json();

    // 🔥 TITRE
    doc.setFontSize(18);
    doc.text("📊 Gabon Data Intelligence", 14, 20);

    doc.setFontSize(12);
    doc.text("Rapport des données", 14, 30);

    // 🔥 TABLEAU
    let y = 40;

    doc.setFontSize(10);
    doc.text("ID", 10, y);
    doc.text("Catégorie", 30, y);
    doc.text("Titre", 70, y);
    doc.text("Valeur", 110, y);
    doc.text("Région", 140, y);

    y += 10;

    data.forEach(d => {
        doc.text(String(d.id), 10, y);
        doc.text(String(d.category), 30, y);
        doc.text(String(d.title), 70, y);
        doc.text(String(d.value), 110, y);
        doc.text(String(d.region), 140, y);

        y += 10;

        // 🔥 nouvelle page si trop long
        if (y > 280) {
            doc.addPage();
            y = 20;
        }
    });

    // 🔥 DOWNLOAD
    doc.save("dashboard.pdf");
}



