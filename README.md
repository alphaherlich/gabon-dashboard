# 🇬🇦 Gabon Data Intelligence Dashboard

🚀 Plateforme d’analyse socio-économique en temps réel pour le Gabon.

---

## 📌 Description

**Gabon Data Intelligence Dashboard** est une application web complète permettant de :

* 📊 Visualiser des indicateurs socio-économiques
* 📈 Analyser les tendances (économie, santé, éducation)
* 🔄 Recevoir des mises à jour en temps réel (WebSocket)
* 📄 Exporter les données (Excel / PDF)
* 🔐 Gérer les rôles utilisateurs (Admin / Lecture seule)

---

## 🏗️ Architecture du projet

```
gabon-dashboard/
│
├── frontend/          # Interface utilisateur (PHP, HTML, CSS, JS)
├── backend/           # API REST (Spring Boot)
├── database/          # Script SQL (MySQL)
│   └── gabon_dashboard.sql
└── README.md
```

---

## 🧰 Technologies utilisées

### 🎨 Frontend

* HTML / CSS / JavaScript
* PHP (session & auth)
* Chart.js (visualisation)
* jsPDF (export PDF)

### ⚙️ Backend

* Java 17
* Spring Boot
* Spring Data JPA
* WebSocket (STOMP)

### 🗄️ Base de données

* MySQL (XAMPP)

---

## ⚙️ Installation

### 1️⃣ Cloner le projet

```bash
git clone https://github.com/TON-USERNAME/gabon-dashboard.git
cd gabon-dashboard
```

---

### 2️⃣ Base de données (MySQL)

1. Ouvrir phpMyAdmin
2. Créer une base :

```
gabon_dashboard
```

3. Importer le fichier :

```
database/gabon_dashboard.sql
```

---

### 3️⃣ Backend (Spring Boot)

Dans IntelliJ ou terminal :

```bash
cd backend
```

Configurer `application.properties` :

```properties
spring.datasource.url=jdbc:mysql://localhost:3306/gabon_dashboard
spring.datasource.username=root
spring.datasource.password=
spring.jpa.hibernate.ddl-auto=update
```

Lancer le serveur :

```bash
mvn spring-boot:run
```

👉 API disponible sur :

```
http://localhost:8080
```

---

### 4️⃣ Frontend (XAMPP)

1. Copier le dossier frontend dans :

```
C:\xampp\htdocs\
```

2. Démarrer Apache (XAMPP)

3. Ouvrir dans navigateur :

```
http://localhost/gabon-dashboard-frontend/dashboard.php
```

---

## 🔐 Authentification

Deux rôles :

* 👤 **User** → lecture seule
* 👑 **Admin** → CRUD + analytics

---

## 📊 Fonctionnalités

* ✅ Dashboard dynamique
* ✅ Graphiques interactifs
* ✅ Filtres par catégorie et région
* ✅ KPI (Total, Moyenne, Région dominante)
* ✅ Export Excel / PDF
* ✅ WebSocket temps réel
* ✅ Gestion Admin (ajout / suppression)

---

## 🔄 WebSocket

Connexion en temps réel via :

```
/ws
```

Utilisation :

* SockJS
* STOMP

---

## 📦 Déploiement (à venir)

* 🔄 Backend → Render / Railway
* 🌐 Frontend → VPS / Apache
* 🗄️ DB → Cloud MySQL

---

## 🧠 Améliorations futures

* 🔍 Recherche avancée
* 📱 Responsive mobile complet
* 🔐 Auth JWT
* ☁️ Déploiement cloud
* 📊 Machine Learning insights

---

## 👨‍💻 Auteur

Projet développé par **Ndoumbou-Daoud Herlich (pseudo : Alpha Herlich)**

---

## 📄 Licence

Ce projet est open-source.

---

## ⭐ Contribution

Les contributions sont les bienvenues !

1. Fork
2. Create branch
3. Commit
4. Push
5. Pull Request

---

## 🚀 Statut du projet

Niveau : Projet personnel

🟢 En développement actif
🔥 Niveau : Projet professionnel

---
