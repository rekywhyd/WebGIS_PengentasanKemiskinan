# WebGIS Pengentasan Kemiskinan

WebGIS Pengentasan Kemiskinan adalah sebuah sistem informasi berbasis web yang dirancang untuk membantu pengelolaan, pemetaan, dan distribusi bantuan sosial secara tepat sasaran. Sistem ini memanfaatkan teknologi Sistem Informasi Geografis (GIS) untuk memetakan lokasi penerima bantuan, serta mengintegrasikan sistem kupon digital (QR Code) untuk memastikan transparansi dan akuntabilitas dalam proses penyaluran bantuan.

## Fitur Utama

- **Manajemen Data Penerima Bantuan:** Pendaftaran, pembaruan, dan pengelolaan data warga yang berhak menerima bantuan, lengkap dengan dokumen pendukung dan foto bukti.
- **Pemetaan Spasial (WebGIS):** Visualisasi lokasi penerima bantuan dan titik penyalur (Tempat Ibadah) pada peta interaktif. Sistem juga mengestimasi jarak antara penerima dan penyalur.
- **Persetujuan (Approval) Berjenjang:** Alur verifikasi data penerima bantuan oleh Admin (Disetujui, Ditolak, atau Menunggu Persetujuan) beserta alasan penolakan.
- **Sistem Kupon Digital (QR Code):** Pembuatan dan pencetakan kupon bantuan berbasis QR Code yang dienkripsi (Signed URL) untuk mencegah pemalsuan.
- **Scanner Kupon:** Fitur pemindaian (scan) QR Code kupon pada saat penyaluran bantuan untuk memverifikasi keaslian dan mencatat riwayat pengambilan secara otomatis.
- **Manajemen Penyalur (Tempat Ibadah):** Pengelolaan entitas penyalur bantuan yang bertugas membagikan bantuan kepada warga di sekitarnya.
- **Pelaporan & Riwayat Distribusi:** Pencatatan otomatis setiap bantuan yang telah disalurkan beserta laporan bulanan/berkala.

## Hak Akses (Role)

Sistem ini mendukung pengelolaan hak akses berbasis *role*:
1. **Admin:** Memiliki akses penuh ke seluruh fitur, termasuk menyetujui/menolak pengajuan penerima bantuan, mengelola data penyalur, serta memantau peta dan riwayat distribusi keseluruhan.
2. **User (Penyalur/Tempat Ibadah):** Mengelola data penerima bantuan di wilayahnya, mencetak kupon untuk warga yang telah disetujui, dan melakukan *scan* kupon saat bantuan disalurkan.

## Teknologi yang Digunakan

- **Framework:** Laravel 11 (PHP)
- **Frontend:** Blade Templating, Tailwind CSS
- **Pemetaan:** Library GIS (seperti Leaflet.js atau sejenisnya)
- **QR Code:** Endroid QR Code
- **Database:** MySQL / MariaDB

## Prasyarat Lingkungan

- PHP >= 8.2
- Composer
- Node.js & NPM
- Database MySQL / MariaDB

## Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek secara lokal:

1. **Kloning Repositori:**
   ```bash
   git clone <url-repo-anda>
   cd webgis_pengentasankemiskinan
   ```

2. **Instalasi Dependensi PHP (Composer):**
   ```bash
   composer install
   ```

3. **Instalasi Dependensi Frontend (NPM):**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment:**
   Salin file `.env.example` menjadi `.env`.
   ```bash
   cp .env.example .env
   ```
   Sesuaikan konfigurasi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) pada file `.env`.

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Jalankan Migrasi & Seeder Database:**
   ```bash
   php artisan migrate --seed
   ```
   *Catatan: Anda dapat membuat user admin default melalui seeder.*

7. **Kompilasi Aset Frontend:**
   ```bash
   npm run build
   # atau untuk mode development: npm run dev
   ```

8. **Buat Symbolic Link untuk Storage:**
   (Dibutuhkan untuk menampilkan foto dan dokumen bukti yang diunggah)
   ```bash
   php artisan storage:link
   ```

9. **Jalankan Development Server:**
   ```bash
   php artisan serve
   ```
   Akses aplikasi melalui browser pada `http://localhost:8000`.

## Lisensi

Proyek ini adalah perangkat lunak tertutup atau berpemilik yang dikembangkan untuk tujuan spesifik (Pengentasan Kemiskinan). Hubungi pihak pengembang atau instansi terkait mengenai aturan penggunaan dan lisensi.
