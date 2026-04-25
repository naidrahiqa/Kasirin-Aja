# Kasirin Aja 🛒

Kasirin Aja adalah aplikasi Point of Sales (POS) berbasis web yang dibangun menggunakan framework Laravel. Aplikasi ini dirancang untuk memudahkan manajemen produk, kategori, dan transaksi penjualan secara efisien.

## 🚀 Fitur Utama

Aplikasi ini memiliki dua level akses utama: **Admin** dan **Kasir**.

### 🛠️ Fitur Admin
*   **Dashboard Analitik**: Visualisasi data penjualan dan ringkasan transaksi.
*   **Manajemen Produk**: CRUD (Create, Read, Update, Delete) produk dengan dukungan barcode dan harga modal.
*   **Manajemen Kategori**: Pengelompokan produk berdasarkan kategori untuk memudahkan pencarian.
*   **Riwayat Transaksi**: Melihat semua riwayat transaksi yang terjadi di sistem.
*   **Ekspor Data**: Fitur untuk mengekspor laporan transaksi ke format CSV.

### 💰 Fitur Kasir
*   **Antarmuka POS**: Interface penjualan yang cepat dan responsif untuk memproses pesanan pelanggan.
*   **Checkout Cepat**: Proses pembayaran dengan kalkulasi kembalian otomatis.
*   **Cetak Struk**: Pembuatan struk belanja untuk setiap transaksi yang berhasil.

---

## 🏗️ Struktur Proyek

Proyek ini mengikuti standar struktur direktori Laravel 10 dengan beberapa penyesuaian:

```text
Kasirin-Aja/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Logika utama aplikasi (POS, Produk, Transaksi, dll)
│   │   └── Middleware/     # Filter keamanan (seperti IsAdmin)
│   └── Models/             # Definisi database (Product, Category, Transaction)
├── database/
│   ├── migrations/         # Struktur tabel database
│   └── seeders/            # Data awal untuk testing/development
├── public/                 # Asset yang dapat diakses publik
├── resources/
│   ├── views/              # Template antarmuka (Blade + Tailwind CSS)
│   │   ├── categories/     # UI Manajemen Kategori
│   │   ├── products/       # UI Manajemen Produk
│   │   ├── pos/            # UI Kasir
│   │   └── transactions/   # UI Riwayat Transaksi
│   └── js/ & css/          # File source frontend
├── routes/
│   ├── web.php             # Definisi rute aplikasi web
│   └── auth.php            # Rute autentikasi (Login/Logout)
├── docker/                 # Konfigurasi containerization
├── docker-compose.yml      # Definisi layanan Docker
└── vite.config.js          # Konfigurasi bundling asset frontend
```

---

## 🛠️ Tech Stack

*   **Backend**: PHP 8.1+ & [Laravel 10](https://laravel.com/)
*   **Frontend**: [Tailwind CSS](https://tailwindcss.com/) & [Vite](https://vitejs.dev/)
*   **Database**: MySQL / SQLite
*   **Authentication**: Laravel Breeze
*   **Deployment**: Docker Ready

---

## ⚙️ Instalasi

1.  **Clone Repositori**
    ```bash
    git clone https://github.com/naidrahiqa/Kasirin-Aja.git
    ```
2.  **Instal Dependensi**
    ```bash
    composer install
    npm install && npm run dev
    ```
3.  **Konfigurasi Environment**
    Salin `.env.example` menjadi `.env` dan sesuaikan pengaturan database Anda.
4.  **Migrasi Database**
    ```bash
    php artisan migrate --seed
    ```
5.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

---