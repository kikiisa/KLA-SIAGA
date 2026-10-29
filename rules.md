# Rules Pengembangan SIAGA KLA

Dokumen ini berisi aturan wajib yang harus diikuti saat mengembangkan aplikasi SIAGA KLA. Aturan di dokumen ini melengkapi panduan di `AGENTS.md`.

## 1. Stack dan Konvensi Dasar

- Backend: Laravel 13, PHP 8.3.
- View: Blade, bukan React/Vue.
- Styling: Tailwind CSS v4 melalui Vite.
- Format kode PHP: Laravel Pint.
- Gunakan Bahasa Indonesia untuk teks antarmuka dan pesan validasi.
- Gunakan Bahasa Inggris untuk nama class, method, variable, dan file kode.
- Semua perintah Artisan wajib menggunakan `--no-interaction`.

## 2. Struktur Blade

### 2.1 Layout

Semua halaman wajib menggunakan layout. Dilarang menduplikasi `<html>`, `<head>`, `@vite`, atau `@fonts` di halaman.

- `resources/views/layouts/app.blade.php` untuk halaman yang membutuhkan autentikasi dan shell aplikasi (sidebar, header, footer).
- `resources/views/layouts/auth.blade.php` untuk halaman tamu seperti login.
- Layout hanya boleh berisi kerangka halaman, bukan data bisnis.

Contoh halaman:

```blade
@extends('layouts.app')

@section('title', 'Dashboard — '.config('app.name'))

@section('content')
    {{-- konten halaman --}}
@endsection
```

### 2.2 Partial

- Simpan potongan tampilan yang dipakai ulang di `resources/views/partials/`.
- Nama file menggunakan `kebab-case`, contoh: `auth-story.blade.php`, `sidebar.blade.php`.
- Partial tidak boleh melakukan query database atau logika bisnis.
- Gunakan `@include('partials.nama')` dan kirim data eksplisit bila diperlukan.

Contoh:

```blade
@include('partials.sidebar')
@include('partials.alerts')
```

### 2.3 Komponen

- Komponen UI reusable disimpan di `resources/views/components/`.
- Nama komponen menggunakan `kebab-case`, dipanggil dengan `<x-nama-komponen />`.
- Gunakan `@props` untuk mendefinisikan input komponen.
- Komponen tidak boleh bergantung pada request atau session.

Contoh:

```blade
<x-icon name="check" :size="16" />
<x-logo dark />
```

### 2.4 Aturan View

- View harus "dumb": hanya menampilkan data.
- Data disiapkan oleh controller, service, atau view composer, bukan query di Blade.
- Gunakan `@class([...])` untuk class Tailwind kondisional.
- Gunakan `route()` untuk URL, bukan hardcode path.
- Gunakan `@csrf` pada semua form POST/PUT/PATCH/DELETE.
- Gunakan `old()` untuk mempertahankan input dan `$errors` untuk menampilkan validasi.
- Inline style hanya boleh untuk nilai dinamis yang tidak bisa dibuat sebagai class Tailwind, misalnya lebar progress bar atau `conic-gradient`.

## 3. Arsitektur Controller-Service-Repository

Alur wajib:

```text
Route -> Controller -> Service -> Repository -> Model/Database
```

### 3.1 Controller

- Controller hanya menangani HTTP: validasi request, memanggil service, dan mengembalikan response.
- Controller tidak boleh berisi query Eloquent kompleks atau logika bisnis.
- Validasi wajib menggunakan FormRequest.
- Gunakan constructor property promotion dan `readonly`.

Contoh:

```php
public function __construct(private readonly AuthService $authService) {}

public function store(LoginRequest $request): RedirectResponse
{
    $authenticated = $this->authService->attempt(
        email: $request->string('email')->toString(),
        password: $request->string('password')->toString(),
        remember: $request->boolean('remember'),
    );

    if (! $authenticated) {
        throw ValidationException::withMessages([
            'email' => 'Alamat email atau kata sandi tidak sesuai.',
        ]);
    }

    $request->session()->regenerate();

    return redirect()->intended(route('dashboard'));
}
```

### 3.2 Service

- Service berisi logika bisnis, orkestrasi, dan transaksi.
- Service tidak boleh menerima `Request` atau mengembalikan `Response`.
- Service boleh bergantung pada satu atau beberapa repository.
- Beri nama dengan suffix `Service`, contoh: `AuthService`, `PeriodService`.
- Gunakan return type yang jelas (`bool`, `void`, model, collection, atau DTO).

### 3.3 Repository

- Repository hanya mengakses data. Tidak boleh berisi aturan bisnis.
- Interface disimpan di `app/Repositories/Contracts/`.
- Implementasi Eloquent disimpan di `app/Repositories/`.
- Beri nama interface dengan suffix `RepositoryInterface` dan implementasi dengan suffix `Repository`.
- Binding interface ke implementasi dilakukan di `AppServiceProvider`.

Contoh binding:

```php
$this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
```

Contoh interface:

```php
interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function updatePassword(User $user, string $passwordHash): void;
}
```

### 3.4 FormRequest

- Semua validasi input wajib menggunakan FormRequest.
- Pesan validasi menggunakan Bahasa Indonesia.
- `authorize()` mengembalikan `true` kecuali ada aturan otorisasi khusus.

## 4. Penamaan

- Controller: `AuthController`, `DashboardController`.
- Service: `AuthService`.
- Repository: `EloquentUserRepository`.
- Interface: `UserRepositoryInterface`.
- FormRequest: `LoginRequest`.
- Method harus deskriptif: `attempt`, `findByEmail`, `updatePassword`.
- Variable harus deskriptif, bukan singkatan yang tidak jelas.

## 5. Tipe dan Gaya PHP

- Wajib menggunakan type hint parameter dan return type.
- Wajib menggunakan kurung kurawal pada semua control structure.
- Gunakan constructor property promotion.
- Gunakan PHPDoc untuk array shape atau tipe kompleks.
- Jangan menambahkan komentar inline kecuali logika benar-benar kompleks.
- Jalankan formatter sebelum menyelesaikan pekerjaan:

```bash
vendor/bin/pint app/Http/Controllers/AuthController.php routes/web.php --format agent
```

## 6. Autentikasi dan Keamanan

- Login tidak memilih role. Role, jika nanti ditambahkan, ditentukan oleh akun pengguna dan bukan input form.
- Jangan menambahkan switch Admin/OPD atau pemilihan OPD pada halaman login.
- Gunakan middleware `guest` untuk halaman login dan `auth` untuk dashboard.
- Gunakan rate limit pada endpoint login.
- Jangan pernah menyimpan atau menampilkan password plain text.
- Jangan commit secret, token, atau kredensial ke repository.
- Selalu gunakan `@csrf` pada form.
- Gunakan `Hash::check()` untuk verifikasi password dan `Hash::make()` untuk hash baru.

## 7. Tailwind CSS dan Flowbite

- Gunakan utility class Tailwind. Hindari CSS kustom kecuali benar-benar diperlukan.
- Kelompokkan class dengan urutan: layout, spacing, ukuran, warna, tipografi, state.
- Gunakan `@class` untuk kondisi.
- Gunakan Flowbite untuk komponen form: input, select, checkbox, radio, textarea, dan button.
- Class form Flowbite yang digunakan antara lain:
  - Input/select: `block w-full rounded-base border border-default-medium bg-neutral-primary-soft text-heading shadow-xs focus:border-brand focus:ring-2 focus:ring-brand-soft`.
  - Checkbox/radio: `rounded-xs border border-default-medium bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft`.
  - Button: `rounded-base bg-brand text-white hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium`.
- Gunakan data attribute Flowbite untuk komponen interaktif, bukan JavaScript manual jika komponen sudah tersedia.
- Flowbite diimpor melalui npm dan Vite, bukan CDN. Instalasi: `npm install flowbite`.
- Konfigurasi Flowbite berada di `resources/css/app.css`:

```css
@import 'tailwindcss';
@import 'flowbite/src/themes/default';

@plugin 'flowbite/plugin';
@source '../../node_modules/flowbite';
```

- Import JavaScript Flowbite di `resources/js/app.js`:

```js
import 'flowbite';
```

- Jika perubahan frontend tidak muncul, jalankan:

```bash
npm run build
```

- Untuk pengembangan lokal, jalankan `npm run dev` atau `composer run dev`.

### 7.1 Chart

- Gunakan Chart.js untuk semua grafik.
- Inisialisasi chart terpusat di `resources/js/app.js` berdasarkan atribut `data-chart` pada elemen `<canvas>`.
- Kirim data chart melalui atribut `data-labels`, `data-values`, dan `data-colors` dengan `@json(..., JSON_HEX_APOS)`.
- Jangan menggunakan `conic-gradient` atau progress bar CSS untuk grafik utama.
- Jalankan `npm run build` atau pastikan `npm run dev` aktif setelah mengubah chart.

## 8. Testing

- Setiap fitur baru wajib memiliki test.
- Gunakan feature test untuk HTTP/controller dan unit test untuk service/repository.
- Gunakan factory untuk membuat model.
- Untuk service yang bergantung pada repository, gunakan fake atau mock repository agar test tidak bergantung pada database.
- Jalankan test paling sempit yang relevan:

```bash
php artisan test --compact tests/Feature/AuthUiTest.php
```

- Test wajib lulus sebelum pekerjaan dianggap selesai.

## 9. Database dan Permission

- Sumber kebenaran skema database adalah `schemaTable.md`.
- Jangan mengubah migration yang sudah dijalankan di lingkungan bersama; buat migration baru.
- Nama tabel menggunakan plural `snake_case`; kolom menggunakan `snake_case`.
- Semua foreign key wajib memiliki constraint dan perilaku on delete yang eksplisit.
- Gunakan soft delete pada tabel yang menyimpan riwayat atau arsip: `opds`, `users`, `evaluation_periods`, `clusters`, `indicators`, `subdocuments`, dan `documents`.
- Tabel append-only `notifications` dan `audit_logs` hanya memiliki `created_at`.
- `documents.current_version_id` ditambahkan sebagai foreign key setelah tabel `document_versions` dibuat.
- Gunakan enum string untuk status, bukan angka.
- Tambahkan indeks sesuai `schemaTable.md` bagian 4.
- Progres dihitung dari data dokumen, bukan disimpan sebagai kolom statis.
- Gunakan transaksi database saat mengunggah versi baru dan memperbarui `current_version_id`.

### 9.1 Spatie Laravel Permission

- Gunakan `spatie/laravel-permission` untuk manajemen role dan permission.
- Model `User` wajib memakai trait `HasRoles` dan `SoftDeletes`.
- Role yang tersedia: `admin`, `pic`, dan `operator_opd`.
- Permission menggunakan format `resource.action`, contoh `document.verify`.
- Seeder role dan permission berada di `database/seeders/RolePermissionSeeder.php`.
- Middleware alias terdaftar di `bootstrap/app.php`: `role`, `permission`, dan `role_or_permission`.
- Jangan menentukan role dari input login; role melekat pada akun pengguna.
- Lindungi route dengan middleware role/permission dan policy.
- Setelah mengubah permission, bersihkan cache dengan `php artisan permission:cache-reset`.

## 10. Struktur Direktori Ringkas

```text
app/
  Http/
    Controllers/
    Requests/
  Repositories/
    Contracts/
  Services/
resources/
  css/app.css
  js/app.js
  views/
    auth/
    layouts/
    partials/
    components/
routes/
  web.php
```

## 11. Checklist Sebelum Selesai

- [ ] Halaman menggunakan layout yang sesuai.
- [ ] Tidak ada duplikasi `<html>`, `<head>`, `@vite`, atau `@fonts`.
- [ ] Controller hanya menangani HTTP.
- [ ] Logika bisnis berada di service.
- [ ] Akses data berada di repository.
- [ ] Interface repository terdaftar di `AppServiceProvider`.
- [ ] Validasi menggunakan FormRequest.
- [ ] Form memiliki `@csrf`.
- [ ] Migration dan seeder dijalankan tanpa error.
- [ ] Role/permission baru tercatat di seeder.
- [ ] Tailwind berhasil di-build.
- [ ] Pint dijalankan.
- [ ] Test relevan lulus.
