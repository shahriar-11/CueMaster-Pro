# CueMaster Pro
### Smart Snooker Club, Tournament & Billing Management System

---

## 📦 Milestone 1 — Foundation, Auth & Dashboard

### ✅ What's included in this update
- Full project folder structure (config, includes, assets, auth, dashboard, api, database)
- MySQL database `cuemaster_pro` with 4 tables: `users`, `settings`, `tables`, `sessions`
- Realistic seed data (6 tables, 2 live sessions, 15 completed sessions across the last 7 days)
- Dark / Neon Green / Glassmorphism theme system (`assets/css/style.css`) — reusable across every future module
- Secure Admin login (PHP sessions + `password_hash` / `password_verify`)
- Fully responsive sidebar + topbar shell with the complete app roadmap visible (future modules marked with milestone badges)
- Live dashboard pulling **real data from MySQL**:
  - Total Tables / Occupied Now / Today's Revenue / Active Sessions
  - Chart.js revenue trend (last 7 days, via AJAX endpoint)
  - Live table floor view with **real-time counting timers** on occupied tables

---

## 🛠 Setup Instructions (XAMPP)

1. **Copy the project folder** into your XAMPP `htdocs` directory:
   ```
   C:\xampp\htdocs\cuemaster-pro\      (Windows)
   /Applications/XAMPP/htdocs/cuemaster-pro/   (Mac)
   ```

2. **Start Apache and MySQL** from the XAMPP Control Panel.

3. **Import the database:**
   - Open `http://localhost/phpmyadmin`
   - Click **Import** → choose `database/cuemaster_pro.sql` → Go
   - (This file creates the `cuemaster_pro` database automatically — no need to create it manually.)

4. **Open the app:**
   ```
 http://localhost/cue_masterpro
milestone 2-----
http://localhost/cue_masterpro_m2/auth/login.php
   ```

5. **Login with:**
   - Username: `admin`
   - Password: `admin123`

> Default DB credentials in `config/db.php` are XAMPP's defaults (`root` / no password). If your MySQL setup differs, edit that file only.

---

## 🗂 Folder Structure

```
cuemaster-pro/
├── config/
│   ├── db.php              # PDO connection
│   └── constants.php       # BASE_URL, APP_NAME
├── includes/
│   ├── header.php
│   ├── sidebar.php
│   ├── footer.php
│   └── auth_check.php
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── img/
├── auth/
│   ├── login.php
│   └── logout.php
├── dashboard/
│   └── index.php
├── api/
│   └── revenue_chart.php   # AJAX JSON endpoint for the chart
├── database/
│   └── cuemaster_pro.sql
└── index.php                # entry redirect
```

---

## 🗺 Full Project Roadmap

| Milestone | Focus |
|---|---|
| **M1 (this update)** | Foundation, Auth, Dashboard shell |
| **M2** | Tables CRUD, Live Sessions, Billing Engine, Members, Invoices |
| **M3** | Tournaments, Brackets, Staff Management, Role-based Access |
| **M4** | Expenses, Payments, Reports & Analytics, Final Polish |

---

## 🎨 Design Tokens (for reference)

- Background: `#08090b` (base) / `#101215` (elevated)
- Neon accent: `#00ff9c`
- Glass border: `rgba(0,255,156,0.14)`
- Display font: Space Grotesk · Body font: Inter · Data/mono font: JetBrains Mono
