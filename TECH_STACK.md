# Tech Stack SIAGA KLA

Dokumen ini mencatat seluruh teknologi dan dependency yang digunakan pada project ini.

Terakhir diperbarui: 29 Oktober 2026.

## Ringkasan

| Kategori | Teknologi | Versi |
| --- | --- | --- |
| Bahasa backend | PHP | 8.3.6 |
| Framework backend | Laravel | 13.39.0 |
| Dependency manager PHP | Composer | 2.7.1 |
| Template engine | Blade | Bawaan Laravel |
| Permission | spatie/laravel-permission | 8.3.2 |
| DataTables backend | yajra/laravel-datatables-oracle | 13.3.1 |
| Styling | Tailwind CSS | 4.3.3 |
| Komponen UI | Flowbite | 4.0.2 |
| Build tool | Vite | 8.3.5 |
| Plugin Vite Laravel | laravel-vite-plugin | 3.2.0 |
| DataTables frontend | datatables.net | 3.1.4 |
| Tema DataTables | datatables.net-dt | 3.1.4 |
| Notifikasi | SweetAlert2 | 11.26.25 |
| Grafik | Chart.js | 4.6.1 |
| Runtime JavaScript | Node.js | 24.16.0 |
| Package manager JavaScript | npm | 11.13.0 |
| Database utama | MySQL | Mengikuti server |
| Database testing | SQLite `:memory:` | Konfigurasi PHPUnit |
| Test runner | PHPUnit | 12.5.42 |
| Formatter | Laravel Pint | 1.32.1 |

## Backend

- Laravel 13 dengan struktur Controller → Service → Repository.
- PHP 8.3 dengan constructor property promotion, type hint, dan PHPDoc.
- `spatie/laravel-permission` untuk role dan permission (`admin`, `pic`, `operator_opd`).
- `yajra/laravel-datatables-oracle` untuk server-side DataTables.
- Eloquent ORM dan soft delete untuk data yang menyimpan riwayat.
- FormRequest untuk seluruh validasi input.
- Laravel Tinker untuk eksplorasi data saat pengembangan.
- Laravel Boost sebagai panduan dan tooling pengembangan berbasis AI.
- Laravel Pail untuk membaca log aplikasi secara real-time.
- Laravel Pao untuk kebutuhan pengembangan terkait UI.
- Laravel Pint untuk format kode PHP.
- PHPUnit, Faker, dan Mockery untuk pengujian.

## Frontend

- Blade sebagai template engine.
- Tailwind CSS 4 melalui Vite.
- Flowbite sebagai komponen UI dan form.
- Chart.js untuk grafik dashboard.
- SweetAlert2 untuk notifikasi hasil POST dan konfirmasi aksi.
- DataTables.net 3 dengan `datatables.net-dt` untuk tabel client-side yang terhubung ke endpoint Yajra.
- Instrument Sans melalui Bunny Fonts dan integrasi font dari `laravel-vite-plugin`.
- JavaScript modular melalui `resources/js/app.js`.

## Dependency yang Ditambahkan

### Composer

```bash
composer require yajra/laravel-datatables-oracle:^13
```

| Package | Fungsi |
| --- | --- |
| `yajra/laravel-datatables-oracle` | Membuat response server-side DataTables dari query Eloquent. |

### NPM

```bash
npm install datatables.net datatables.net-dt
npm install sweetalert2
```

| Package | Fungsi |
| --- | --- |
| `datatables.net` | Library DataTables untuk tabel interaktif. |
| `datatables.net-dt` | Tema dan integrasi CSS DataTables. |
| `sweetalert2` | Notifikasi dan dialog konfirmasi. |

## Integrasi DataTables

- Class DataTables berada di `app/DataTables`.
- `UserDataTable` menangani data pengguna beserta role dan OPD.
- `OpdDataTable` menangani data OPD beserta jumlah pengguna.
- Endpoint server-side:
  - `GET /admin/users/data` (`admin.users.data`)
  - `GET /admin/opds/data` (`admin.opds.data`)
- Repository menyediakan query builder melalui `queryWithRoles()` dan `queryWithUserCount()`.
- Tabel Blade ditandai dengan `data-datatable` dan `data-url`.
- Inisialisasi DataTables dilakukan terpusat di `resources/js/app.js`.
- Konfigurasi kolom dibaca dari atribut `data-column` pada setiap `<th>`.

## Perintah Pengembangan

```bash
composer install
npm install
npm run build
php artisan migrate
php artisan test
```

Untuk pengembangan lokal dengan hot reload:

```bash
npm run dev
```

Format kode PHP:

```bash
vendor/bin/pint --dirty --format agent
```
