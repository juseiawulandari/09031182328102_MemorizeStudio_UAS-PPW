# 09031182328102_MemorizeStudio_UAS-PPW

# 📸 MemorizeStudio

*MemorizeStudio* adalah sebuah website layanan reservasi foto studio yang dikembangkan menggunakan PHP, MySQL, HTML, CSS, Bootstrap, dan JavaScript. Website ini dibuat sebagai proyek praktikum mata kuliah *Pemrograman Web II* di Universitas Sriwijaya.

Website ini menyediakan fitur reservasi daring, portofolio hasil karya studio, sistem login dan registrasi, serta panel admin untuk pengelolaan data pengguna dan reservasi.

---

## 📌 Fitur-Fitur Utama

### 👥 Sistem Autentikasi
- Registrasi pengguna
- Login & logout
- Hak akses berdasarkan role (guest, user, admin)

### 🗓 Reservasi Online
- Pemesanan sesi foto berdasarkan tanggal dan waktu
- Edit & hapus reservasi
- Konfirmasi reservasi oleh admin

### 🖼 Portofolio Studio
- Galeri karya foto yang bisa diunggah oleh admin
- Ditampilkan secara responsif

### 💬 Testimoni
- Pengguna dapat memberikan ulasan dan rating
- Ditampilkan sebagai bentuk social proof

### 🛠 Admin Panel
- Manajemen user
- Kelola reservasi
- Kelola portofolio
- Dashboard statistik sistem

---

## 🛠 Teknologi yang Digunakan

- *PHP* (Server-side scripting)
- *MySQL* (Database)
- *HTML5 & CSS3*
- *Bootstrap 5.3* (Framework CSS responsif)
- *JavaScript* (Validasi dan interaksi klien)

---

## 📁 Struktur Folder

📂 MemorizeStudio/
├── 📂 admin/                 # Halaman dan proses admin
│   ├── dashboard.php
│   ├── users.php
│   ├── reservasi.php
│   └── 📂 portofolio/
├── 📂 assets/                # Aset visual (gambar, CSS)
│   ├── 📂 css/
│   └── 📂 images/
├── 📂 config/                # Koneksi ke database
│   └── database.php
├── 📂 includes/              # Komponen umum & user
│   ├── navbar.php
│   ├── footer.php
│   ├── reservasi.php
│   ├── portofolio.php
│   └── testimoni.php
├── index.php                # Landing page
├── login.php                # Login user
├── register.php             # Register user
├── logout.php               # Logout user
└── tentang-dev.php          # Profil pengembang


---

## 🧾 Struktur Database (MySQL)

### Tabel user
- id_user (PK)
- nama
- email
- password
- role (user, admin)
- tanggal_daftar

### Tabel reservasi
- id_reservasi (PK)
- id_user (FK)
- layanan
- tanggal
- jam
- catatan
- status (pending, confirmed, canceled)
- created_at

### Tabel portofolio
- id_portofolio (PK)
- judul
- deskripsi
- gambar
- tanggal_upload

---

## 👤 Developer

*Nama*: Juseia Wulandari  
*NIM*: 09031182328102  
*Kelas*: SIREG 4C  
*Universitas*: Universitas Sriwijaya  
*Proyek*: Praktikum Pemrograman Web II – 2025

---

## 🚀 Rencana Pengembangan Selanjutnya

- ✅ Sistem reservasi dasar
- ✅ Panel admin
- 🔜 Integrasi sistem pembayaran (midtrans/Xendit)
- 🔜 Penggunaan hashing password (bcrypt)
- 🔜 Filter & kategori portofolio

---

## 📄 Lisensi

Proyek ini dikembangkan untuk tujuan pendidikan dan praktikum. Bebas digunakan dan dimodifikasi dengan mencantumkan kredit pada pengembang.

---
