NIM: 60324034
Nama: Zahra Azizatul Fauziyyah

Deskripsi:
Aplikasi yang mengelola data kategori buku perpustakaan menggunakan fitur CRUD

Cara Instalasi dan menjalankan aplikasi:
1. Clone repository https://github.com/ziyyaaa/uts-pemrograman-web-2-60324034.git
2. Pindahkan folder ke htdocs (XAMPP)
3. Import database
   - Buka phpMyAdmin
   - Buat database dengan nama uts_perpustakaan_60324034
   - Import file database_backup.sql
4. Konfigurasi database
   - Buka config/database.php
5. Struktur Folder:
uts_60324034/
├── config/
│   └── database.php
├── index.php
├── create.php
├── edit.php
├── delete.php
├── database_backup.sql
└── README.md
6. Jalankan aplikasi http://localhost/uts_60324034/index.php
