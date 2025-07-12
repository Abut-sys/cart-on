<p align="center">
  <a href="https://cart-on.smartinsistem.com/" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Cart-On Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/Abut-sys/cart-on/actions"><img src="https://github.com/Abut-sys/cart-on/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/abut-sys/cart-on"><img src="https://img.shields.io/packagist/dt/abut-sys/cart-on" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/abut-sys/cart-on"><img src="https://img.shields.io/packagist/v/abut-sys/cart-on" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/abut-sys/cart-on"><img src="https://img.shields.io/packagist/l/abut-sys/cart-on" alt="License"></a>
</p>

# Cart-On

**Cart-On** adalah aplikasi online shop berbasis Laravel yang menyediakan solusi e-commerce modern, mudah digunakan, dan dapat dikembangkan sesuai kebutuhan bisnis Anda.

🔗 [Demo Online](https://cart-on.smartinsistem.com/)  
🔗 [Repository GitHub](https://github.com/Abut-sys/cart-on)

## Fitur Utama

- Manajemen produk & kategori
- Keranjang belanja dinamis
- Checkout & integrasi pembayaran
- Riwayat pesanan & notifikasi email
- Dashboard admin & laporan penjualan
- Otentikasi dan otorisasi pengguna
- Responsive & mudah dikustomisasi

## Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/Abut-sys/cart-on.git
   cd cart-on
   ```

2. **Install dependency**
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Salin file environment & generate key**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Atur konfigurasi database** di file `.env`.

5. **Migrasi & seeding database**
   ```bash
   php artisan migrate --seed
   ```

6. **Jalankan server**
   ```bash
   php artisan serve
   ```

## Dokumentasi

- [Laravel Documentation](https://laravel.com/docs)

## Kontribusi

Kontribusi sangat terbuka! Silakan buat pull request atau buka issue untuk diskusi fitur/bug.

## Lisensi

Cart-On dirilis di bawah [MIT License](https://opensource.org/licenses/MIT).

---

<p align="center">Dibuat dengan ❤️ oleh Tim Cart-On</p>
