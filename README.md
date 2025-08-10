
# CareSyncICU-Gemastik-BE

Backend aplikasi CareSyncICU untuk kompetisi Gemastik.

---

## Prasyarat

Pastikan sudah terinstall:

- PHP 8.2 atau lebih baru  
- Composer    

---

## Cara Menjalankan Proyek

1. **Clone Repositori**

   ```bash
   git clone https://github.com/Adhannnn/CareSyncICU-Gemastik-BE.git
   cd CareSyncICU-Gemastik-BE
   ```

2. **Install Dependensi PHP**

   ```bash
   composer install
   ```

3. **Buat File `.env` dan Sesuaikan**

   ```bash
   cp .env.example .env
   ```

   Edit file `.env` sesuai konfigurasi database dan environment kamu.

4. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```

5. **Setup Database**

   - Buat database baru di MySQL sesuai konfigurasi di `.env`  
   - Jalankan migration untuk membuat tabel:

     ```bash
     php artisan migrate
     ```

   - (Opsional) Jalankan seeder untuk data awal:

     ```bash
     php artisan db:seed
     ```

6. **Jalankan Server Laravel**

   ```bash
   php artisan serve
   ```

   Server akan berjalan di `http://localhost:8000`

7. **Frontend**

  Akses link berikut : /https://care-sync-icu-gemastik.vercel.app/login

---

## Catatan

- Pastikan ekstensi PHP yang diperlukan sudah aktif (pdo_mysql, gd, dll).  
- Sesuaikan konfigurasi database pada `.env`.  
- Jika menggunakan fitur queue, cache, atau service lain, pastikan sudah terkonfigurasi dengan benar.

---

Jika ada pertanyaan atau butuh bantuan, silakan buka issue di repo ini atau hubungi kami - Techspire.

Terima kasih sudah menggunakan CareSyncICU-Gemastik-BE.

Progress (> 50%)
