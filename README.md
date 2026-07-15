# Fiktech Enterprise Corporate Website (Versi PHP 8.3.32)

Sistem Profil Syarikat (Company Profile) bertema premium, futuristik, korporat dan moden untuk **FIKTECH ENTERPRISE** menggunakan **PHP 8.3.32** di bahagian backend. Borang maklum balas dihantar secara AJAX (Fetch API) dan disimpan secara selamat ke dalam fail harian JSON harian diasingkan mengikut:
- Mesej masuk (Incoming): `data/contacts/incoming/D-M-YYYY.json`
- E-mel balasan (Outgoing): `data/contacts/outgoing/D-M-YYYY.json`

Sistem ini mempunyai integrasi **Email Settings (SMTP Client)** asli untuk menyokong penghantaran balasan e-mel terus dari portal menggunakan Webmail / Roundcube SMTP.

---

## Ciri Utama & Spesifikasi

- **Backend**: PHP 8.3.32.
- **Email Reply System**: Menggunakan SMTP Socket Client asli PHP (`fsockopen`) untuk menyokong SSL/TLS & AUTH LOGIN.
- **Keselamatan**: Perlindungan CSRF, Session Cookie parametrisation (`HttpOnly`, `SameSite=Lax`), dan penulisan fail secara thread-safe menggunakan `flock()`.
- **Panel Admin**: Dashboard analitik statistik, jadual pengurusan contact (search, filter, status toggle), paparan detail perhubungan, dan halaman Email/SMTP settings.
- **Frontend**: Animasi smooth (floating nodes & grid background), sticky navigation, rekaan glassmorphism, portfolio filter, dan mobile-responsive.

---

## Prasyarat Sistem

Pastikan pelayan web (hosting) atau komputer lokal anda mempunyai pemasangan:
- **PHP 8.3.x** (atau lebih tinggi)
- Modul PHP **Sockets** dan **OpenSSL** (biasanya diaktifkan secara default).

---

## Langkah Pemasangan Lokal & Pelancaran

### 1. Sediakan Konfigurasi Environment

Salin fail `.env.example` kepada `.env`:
```bash
cp .env.example .env
```

### 2. Jana Hash Kata Laluan Admin Baru:

Dalam PHP, kata laluan admin disimpan di fail `.env` menggunakan format hash (demi keselamatan). Jana hash bagi kata laluan pilihan anda (contoh kata laluan: `MySecurePassword123`) dengan menjalankan perintah:

```bash
php -r "echo password_hash('MySecurePassword123', PASSWORD_BCRYPT) . PHP_EOL;"
```

Contoh output yang dijana:
`$2y$10$abcdefghijklmnopqrstu...`

**Kemaskini Fail `.env`**:
Buka fail `.env` dan masukkan hash tersebut berserta username admin pilihan anda:
```env
SECRET_KEY=masukkan-kunci-rawak-yang-panjang-dan-selamat
FLASK_ENV=development

ADMIN_USERNAME=admin
ADMIN_PASSWORD_HASH=masukkan-hash-yang-dijana-tadi
```

### 3. Jalankan Aplikasi Secara Lokal

Jalankan server pembangunan terbina dalam PHP di terminal:
```bash
php -S 127.0.0.1:5000
```

Layari pautan berikut di pelayar web anda:
- **Laman Utama Website**: [http://127.0.0.1:5000/index.php](http://127.0.0.1:5000/index.php)
- **Portal Admin**: [http://127.0.0.1:5000/admin/login.php](http://127.0.0.1:5000/admin/login.php)

---

## Urusan Backup & Penyimpanan Data

- **Lokasi Simpanan**:
  - `data/contacts/incoming/` (fail mesej pelanggan).
  - `data/contacts/outgoing/` (fail e-mel keluar balasan admin).
  - `data/settings.json` (tetapan SMTP).
- **Cara Melakukan Backup**:
  Sila salin folder `data/` secara berkala ke server sandaran anda:
  ```bash
  cp -r data/ /path/to/backup/folder/
  ```

---

## Menjalankan Unit Tests

Bagi menguji validator input dan penyimpanan JSON harian, jalankan ujian unit dengan:
```bash
php tests/TestApp.php
```

---

## Panduan Migrasi ke Live Hosting (cPanel / Shared Hosting)

1. Salin keseluruhan folder projek `PROJECT47` ke dalam direktori domain anda (cth: `public_html`).
2. Sediakan fail `.env` dengan tetapan yang betul (pastikan fail ini dilindungi di pelayan web).
3. Lawati halaman admin `/admin/settings.php` untuk memuatkan tetapan SMTP Roundcube anda bagi mengaktifkan fungsi Reply Email.
