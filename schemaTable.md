# Database Schema SIAGA KLA

Berdasarkan PRD SIAGA KLA versi 1.1 yang kamu berikan, desain database perlu mendukung alur Periode → Cluster → OPD Sasaran → Indikator → Subdokumen → File Bukti Dukung → Catatan, Revisi, dan Verifikasi.&#x20;

Pasted markdown.md



Saya menyarankan desain relasional menggunakan MySQL, dengan struktur yang cocok untuk Laravel 12, Eloquent ORM, Vue 3, TypeScript, dan Inertia.js.

Desain ini memisahkan data konfigurasi evaluasi dari data pengumpulan dokumen masing-masing OPD, sehingga dokumen, progres, dan riwayat revisi tidak tercampur meskipun beberapa OPD mengerjakan indikator dalam cluster yang sama.

## 1. Daftar tabel utama

| No. | Nama tabel              | Fungsi                                                  |
| --- | ----------------------- | ------------------------------------------------------- |
| 1   | `opds`                  | Master data Organisasi Perangkat Daerah                 |
| 2   | `users`                 | Akun Admin, PIC, dan Operator OPD                       |
| 3   | `evaluation_periods`    | Periode evaluasi KLA                                    |
| 4   | `clusters`              | Cluster dalam satu periode                              |
| 5   | `cluster_opds`          | Relasi cluster dengan OPD sasaran                       |
| 6   | `indicators`            | Indikator dalam cluster                                 |
| 7   | `indicator_assignments` | Penugasan khusus indikator kepada OPD                   |
| 8   | `subdocuments`          | Persyaratan bukti dukung indikator                      |
| 9   | `documents`             | Identitas pengajuan bukti dukung per OPD dan subdokumen |
| 10  | `document_versions`     | File dan riwayat versi dokumen                          |
| 11  | `document_comments`     | Catatan Admin dan tanggapan OPD                         |
| 12  | `verifications`         | Riwayat keputusan pemeriksaan dokumen                   |
| 13  | `notifications`         | Notifikasi internal                                     |
| 14  | `audit_logs`            | Riwayat aktivitas penting sistem                        |

Relasi tambahan yang direkomendasikan: `roles` dan `permissions` apabila menggunakan Spatie Laravel Permission. Tabel tersebut melengkapi kebutuhan autentikasi dan otorisasi yang dijelaskan dalam PRD.

## 2. Desain tabel dan kolom

Keterangan: `PK` = Primary Key, `FK` = Foreign Key, `UQ` = Unique, `NULL` = boleh kosong.

### Tabel 1 — `opds`

Menyimpan master data OPD yang digunakan dalam penugasan dan akun operator.

| Kolom        | Tipe data       | Key  | Keterangan                   |
| ------------ | --------------- | ---- | ---------------------------- |
| `id`         | BIGINT UNSIGNED | PK   | ID OPD                       |
| `kode_opd`   | VARCHAR(30)     | UQ   | Kode unik OPD                |
| `nama_opd`   | VARCHAR(255)    |      | Nama organisasi              |
| `singkatan`  | VARCHAR(50)     | NULL | Singkatan OPD                |
| `deskripsi`  | TEXT            | NULL | Deskripsi OPD                |
| `alamat`     | TEXT            | NULL | Alamat kantor                |
| `email`      | VARCHAR(255)    | NULL | Email resmi                  |
| `telepon`    | VARCHAR(30)     | NULL | Nomor telepon                |
| `is_active`  | BOOLEAN         |      | Status aktif, default `true` |
| `created_at` | TIMESTAMP       |      | Waktu pembuatan              |
| `updated_at` | TIMESTAMP       |      | Waktu perubahan              |
| `deleted_at` | TIMESTAMP       | NULL | Soft delete                  |

### Tabel 2 — `users`

Akun pengguna sistem. Admin dan PIC dapat mengelola evaluasi, sedangkan operator terhubung dengan OPD tertentu.

| Kolom               | Tipe data       | Key      | Keterangan                             |
| ------------------- | --------------- | -------- | -------------------------------------- |
| `id`                | BIGINT UNSIGNED | PK       | ID pengguna                            |
| `opd_id`            | BIGINT UNSIGNED | FK, NULL | OPD pengguna; kosong untuk admin pusat |
| `name`              | VARCHAR(255)    |          | Nama lengkap                           |
| `email`             | VARCHAR(255)    | UQ       | Email untuk login                      |
| `password`          | VARCHAR(255)    |          | Password hash                          |
| `is_active`         | BOOLEAN         |          | Status akun                            |
| `email_verified_at` | TIMESTAMP       | NULL     | Waktu verifikasi email                 |
| `remember_token`    | VARCHAR(100)    | NULL     | Token sesi Laravel                     |
| `created_at`        | TIMESTAMP       |          | Waktu pembuatan                        |
| `updated_at`        | TIMESTAMP       |          | Waktu perubahan                        |
| `deleted_at`        | TIMESTAMP       | NULL     | Soft delete                            |

Catatan: Untuk beberapa operator pada satu OPD, buat beberapa akun dengan `opd_id` yang sama. Gunakan Spatie Laravel Permission untuk peran `admin`, `pic`, dan `operator_opd`.

### Tabel 3 — `evaluation_periods`

Periode merupakan entitas utama. Cluster hanya boleh dibuat apabila periode sudah tersedia.

| Kolom         | Tipe data         | Key  | Keterangan                              |
| ------------- | ----------------- | ---- | --------------------------------------- |
| `id`          | BIGINT UNSIGNED   | PK   | ID periode                              |
| `code`        | VARCHAR(50)       | UQ   | Contoh `KLA-2026`                       |
| `name`        | VARCHAR(255)      |      | Nama periode evaluasi                   |
| `year`        | SMALLINT UNSIGNED |      | Tahun evaluasi                          |
| `description` | TEXT              | NULL | Deskripsi periode                       |
| `start_date`  | DATE              |      | Tanggal mulai                           |
| `end_date`    | DATE              |      | Batas pengumpulan                       |
| `status`      | ENUM              |      | `draft`, `active`, `closed`, `archived` |
| `created_by`  | BIGINT UNSIGNED   | FK   | Admin pembuat                           |
| `created_at`  | TIMESTAMP         |      | Waktu pembuatan                         |
| `updated_at`  | TIMESTAMP         |      | Waktu perubahan                         |
| `deleted_at`  | TIMESTAMP         | NULL | Soft delete                             |

### Tabel 4 — `clusters`

Menyimpan cluster evaluasi di dalam periode tertentu.

| Kolom         | Tipe data       | Key  | Keterangan                    |
| ------------- | --------------- | ---- | ----------------------------- |
| `id`          | BIGINT UNSIGNED | PK   | ID cluster                    |
| `period_id`   | BIGINT UNSIGNED | FK   | Periode evaluasi              |
| `code`        | VARCHAR(50)     |      | Kode cluster                  |
| `name`        | VARCHAR(255)    |      | Nama cluster                  |
| `description` | TEXT            | NULL | Deskripsi cluster             |
| `sort_order`  | INT UNSIGNED    |      | Urutan tampilan               |
| `status`      | ENUM            |      | `draft`, `active`, `inactive` |
| `created_by`  | BIGINT UNSIGNED | FK   | Admin pembuat                 |
| `created_at`  | TIMESTAMP       |      | Waktu pembuatan               |
| `updated_at`  | TIMESTAMP       |      | Waktu perubahan               |
| `deleted_at`  | TIMESTAMP       | NULL | Soft delete                   |

Constraint yang disarankan: `UNIQUE(period_id, code)` agar kode cluster tidak duplikat dalam periode yang sama.

### Tabel 5 — `cluster_opds`

Tabel penghubung many-to-many antara cluster dan OPD. Satu cluster dapat ditujukan kepada banyak OPD dan satu OPD dapat menerima penugasan beberapa cluster.

| Kolom         | Tipe data       | Key  | Keterangan                 |
| ------------- | --------------- | ---- | -------------------------- |
| `id`          | BIGINT UNSIGNED | PK   | ID penugasan               |
| `cluster_id`  | BIGINT UNSIGNED | FK   | Cluster yang ditugaskan    |
| `opd_id`      | BIGINT UNSIGNED | FK   | OPD sasaran                |
| `assigned_by` | BIGINT UNSIGNED | FK   | Admin pemberi penugasan    |
| `assigned_at` | TIMESTAMP       |      | Waktu penugasan            |
| `revoked_at`  | TIMESTAMP       | NULL | Waktu pencabutan penugasan |
| `created_at`  | TIMESTAMP       |      | Waktu pembuatan            |
| `updated_at`  | TIMESTAMP       |      | Waktu perubahan            |

Constraint: `UNIQUE(cluster_id, opd_id)` jika satu pasangan cluster–OPD hanya boleh memiliki satu baris penugasan. Perubahan penugasan dicatat melalui `revoked_at`, bukan menghapus barisnya.

### Tabel 6 — `indicators`

Setiap indikator dimiliki oleh satu cluster. Periode indikator diturunkan melalui relasi cluster.

| Kolom                      | Tipe data       | Key  | Keterangan           |
| -------------------------- | --------------- | ---- | -------------------- |
| `id`                       | BIGINT UNSIGNED | PK   | ID indikator         |
| `cluster_id`               | BIGINT UNSIGNED | FK   | Cluster induk        |
| `code`                     | VARCHAR(50)     |      | Kode indikator       |
| `name`                     | VARCHAR(255)    |      | Nama indikator       |
| `description`              | TEXT            | NULL | Deskripsi            |
| `operational_definition`   | TEXT            | NULL | Definisi operasional |
| `fulfillment_requirements` | TEXT            | NULL | Syarat pemenuhan     |
| `deadline`                 | DATETIME        | NULL | Tenggat indikator    |
| `weight`                   | DECIMAL(8,2)    | NULL | Bobot penilaian      |
| `sort_order`               | INT UNSIGNED    |      | Urutan indikator     |
| `is_active`                | BOOLEAN         |      | Status aktif         |
| `created_by`               | BIGINT UNSIGNED | FK   | Admin pembuat        |
| `created_at`               | TIMESTAMP       |      | Waktu pembuatan      |
| `updated_at`               | TIMESTAMP       |      | Waktu perubahan      |
| `deleted_at`               | TIMESTAMP       | NULL | Soft delete          |

Constraint: `UNIQUE(cluster_id, code)`.

### Tabel 7 — `indicator_assignments`

Mengatur penugasan indikator pada tingkat OPD ketika penugasan indikator berbeda dari penugasan default cluster.

| Kolom             | Tipe data       | Key | Keterangan           |
| ----------------- | --------------- | --- | -------------------- |
| `id`              | BIGINT UNSIGNED | PK  | ID penugasan         |
| `indicator_id`    | BIGINT UNSIGNED | FK  | Indikator            |
| `opd_id`          | BIGINT UNSIGNED | FK  | OPD yang ditugaskan  |
| `assignment_type` | ENUM            |     | `include`, `exclude` |
| `assigned_by`     | BIGINT UNSIGNED | FK  | Admin yang mengatur  |
| `created_at`      | TIMESTAMP       |     | Waktu pembuatan      |
| `updated_at`      | TIMESTAMP       |     | Waktu perubahan      |

Aturan: jika tidak ada pengecualian, indikator mengikuti `cluster_opds`. Jika ada pengaturan khusus, sistem menggunakan penugasan indikator untuk menentukan OPD yang berhak mengisi.

### Tabel 8 — `subdocuments`

Berisi daftar persyaratan dokumen untuk setiap indikator. Satu indikator dapat memiliki beberapa subdokumen.

| Kolom                | Tipe data         | Key  | Keterangan                                 |
| -------------------- | ----------------- | ---- | ------------------------------------------ |
| `id`                 | BIGINT UNSIGNED   | PK   | ID subdokumen                              |
| `indicator_id`       | BIGINT UNSIGNED   | FK   | Indikator induk                            |
| `code`               | VARCHAR(50)       |      | Kode subdokumen                            |
| `name`               | VARCHAR(255)      |      | Nama persyaratan                           |
| `description`        | TEXT              | NULL | Deskripsi persyaratan                      |
| `instructions`       | TEXT              | NULL | Instruksi pengisian                        |
| `is_required`        | BOOLEAN           |      | Wajib atau opsional                        |
| `allowed_extensions` | JSON              |      | Contoh `["pdf","docx","xlsx","jpg","png"]` |
| `max_file_size_mb`   | SMALLINT UNSIGNED |      | Batas ukuran file                          |
| `deadline`           | DATETIME          | NULL | Tenggat khusus subdokumen                  |
| `sort_order`         | INT UNSIGNED      |      | Urutan tampilan                            |
| `is_active`          | BOOLEAN           |      | Status aktif                               |
| `created_at`         | TIMESTAMP         |      | Waktu pembuatan                            |
| `updated_at`         | TIMESTAMP         |      | Waktu perubahan                            |
| `deleted_at`         | TIMESTAMP         | NULL | Soft delete                                |

Constraint: `UNIQUE(indicator_id, code)`.

### Tabel 9 — `documents`

Tabel ini penting untuk memisahkan pengumpulan bukti dukung setiap OPD. Satu subdokumen dapat memiliki pengajuan berbeda untuk OPD A dan OPD B.

| Kolom                | Tipe data       | Key      | Keterangan                |
| -------------------- | --------------- | -------- | ------------------------- |
| `id`                 | BIGINT UNSIGNED | PK       | ID pengajuan dokumen      |
| `subdocument_id`     | BIGINT UNSIGNED | FK       | Persyaratan yang dipenuhi |
| `opd_id`             | BIGINT UNSIGNED | FK       | OPD pemilik pengajuan     |
| `status`             | ENUM            |          | Status verifikasi dokumen |
| `current_version_id` | BIGINT UNSIGNED | FK, NULL | Versi dokumen terkini     |
| `submitted_by`       | BIGINT UNSIGNED | FK, NULL | Pengguna yang mengajukan  |
| `submitted_at`       | DATETIME        | NULL     | Waktu pengajuan           |
| `verified_at`        | DATETIME        | NULL     | Waktu verifikasi terakhir |
| `created_at`         | TIMESTAMP       |          | Waktu pembuatan           |
| `updated_at`         | TIMESTAMP       |          | Waktu perubahan           |
| `deleted_at`         | TIMESTAMP       | NULL     | Soft delete               |

Status yang disarankan:

- `not_uploaded` — Belum Diunggah
- `pending_verification` — Menunggu Verifikasi
- `needs_revision` — Perlu Revisi
- `resubmitted` — Diajukan Kembali
- `verified` — Terverifikasi
- `rejected` — Ditolak

Constraint: `UNIQUE(subdocument_id, opd_id)` untuk satu pengajuan aktif per kombinasi subdokumen dan OPD. Jika diperlukan beberapa siklus pengajuan terpisah, riwayatnya tetap tersimpan dalam versi dan verifikasi.

### Tabel 10 — `document_versions`

Menyimpan setiap file yang diunggah. File lama tidak ditimpa ketika OPD melakukan revisi.

| Kolom               | Tipe data       | Key  | Keterangan                 |
| ------------------- | --------------- | ---- | -------------------------- |
| `id`                | BIGINT UNSIGNED | PK   | ID versi                   |
| `document_id`       | BIGINT UNSIGNED | FK   | Pengajuan induk            |
| `version_number`    | INT UNSIGNED    |      | Nomor versi: 1, 2, 3, dst. |
| `original_filename` | VARCHAR(255)    |      | Nama asli file             |
| `storage_path`      | VARCHAR(1024)   |      | Lokasi file privat         |
| `mime_type`         | VARCHAR(150)    |      | MIME type file             |
| `file_size`         | BIGINT UNSIGNED |      | Ukuran dalam byte          |
| `checksum`          | VARCHAR(64)     | NULL | Hash SHA-256 file          |
| `description`       | TEXT            | NULL | Deskripsi file             |
| `supporting_notes`  | TEXT            | NULL | Keterangan pendukung       |
| `uploaded_by`       | BIGINT UNSIGNED | FK   | Pengunggah                 |
| `uploaded_at`       | DATETIME        |      | Waktu unggah               |
| `created_at`        | TIMESTAMP       |      | Waktu pembuatan            |
| `updated_at`        | TIMESTAMP       |      | Waktu perubahan            |

Constraint: `UNIQUE(document_id, version_number)`.

Contoh: Versi 1 diperiksa dan perlu revisi. Operator mengunggah versi 2. Versi 1 tetap tersedia untuk audit, sementara `documents.current_version_id` menunjuk ke versi terbaru.

### Tabel 11 — `document_comments`

Mencatat komunikasi Admin dan operator, termasuk catatan revisi dan tanggapan. Catatan lama tidak boleh dihapus ketika dokumen diperbarui.

| Kolom                 | Tipe data       | Key      | Keterangan                                |
| --------------------- | --------------- | -------- | ----------------------------------------- |
| `id`                  | BIGINT UNSIGNED | PK       | ID catatan                                |
| `document_id`         | BIGINT UNSIGNED | FK       | Pengajuan dokumen                         |
| `document_version_id` | BIGINT UNSIGNED | FK, NULL | Versi terkait                             |
| `user_id`             | BIGINT UNSIGNED | FK       | Penulis catatan                           |
| `parent_id`           | BIGINT UNSIGNED | FK, NULL | Catatan induk jika berupa balasan         |
| `comment_type`        | ENUM            |          | `comment`, `revision_request`, `response` |
| `message`             | TEXT            |          | Isi catatan                               |
| `created_at`          | TIMESTAMP       |          | Waktu pembuatan                           |
| `updated_at`          | TIMESTAMP       |          | Waktu perubahan                           |

Contoh penggunaan:

- Admin: `revision_request` — Dokumen belum ditandatangani.
- Operator: `response` — Dokumen telah diperbaiki dan diajukan kembali.
- Admin: `comment` — Dokumen sudah sesuai.

### Tabel 12 — `verifications`

Menyimpan riwayat keputusan verifikasi. Setiap pemeriksaan terkait dengan versi dokumen yang diperiksa agar keputusan lama tidak tertukar dengan versi terbaru.

| Kolom                 | Tipe data       | Key  | Keterangan                               |
| --------------------- | --------------- | ---- | ---------------------------------------- |
| `id`                  | BIGINT UNSIGNED | PK   | ID verifikasi                            |
| `document_id`         | BIGINT UNSIGNED | FK   | Pengajuan dokumen                        |
| `document_version_id` | BIGINT UNSIGNED | FK   | Versi yang diperiksa                     |
| `reviewer_id`         | BIGINT UNSIGNED | FK   | Admin pemeriksa                          |
| `status`              | ENUM            |      | `needs_revision`, `verified`, `rejected` |
| `notes`               | TEXT            | NULL | Catatan keputusan                        |
| `verified_at`         | DATETIME        |      | Waktu keputusan                          |
| `created_at`          | TIMESTAMP       |      | Waktu pencatatan                         |
| `updated_at`          | TIMESTAMP       |      | Waktu perubahan                          |

Riwayat verifikasi harus bersifat append-only secara logis: keputusan baru menambah baris baru, bukan menimpa keputusan sebelumnya.

### Tabel 13 — `notifications`

Menyimpan notifikasi internal untuk pengguna.

| Kolom        | Tipe data       | Key  | Keterangan                    |
| ------------ | --------------- | ---- | ----------------------------- |
| `id`         | BIGINT UNSIGNED | PK   | ID notifikasi                 |
| `user_id`    | BIGINT UNSIGNED | FK   | Penerima                      |
| `type`       | VARCHAR(100)    |      | Jenis notifikasi              |
| `title`      | VARCHAR(255)    |      | Judul notifikasi              |
| `message`    | TEXT            |      | Isi notifikasi                |
| `data`       | JSON            | NULL | Metadata, misalnya ID dokumen |
| `read_at`    | TIMESTAMP       | NULL | Waktu dibaca                  |
| `created_at` | TIMESTAMP       |      | Waktu dibuat                  |

Contoh `type`: `period_opened`, `deadline_approaching`, `document_submitted`, `revision_requested`, `document_verified`, `document_rejected`.

### Tabel 14 — `audit_logs`

Mencatat aktivitas penting untuk keperluan penelusuran dan audit.

| Kolom         | Tipe data       | Key      | Keterangan                  |
| ------------- | --------------- | -------- | --------------------------- |
| `id`          | BIGINT UNSIGNED | PK       | ID log                      |
| `user_id`     | BIGINT UNSIGNED | FK, NULL | Pelaku aktivitas            |
| `action`      | VARCHAR(100)    |          | Contoh `document.uploaded`  |
| `entity_type` | VARCHAR(100)    |          | Jenis entitas               |
| `entity_id`   | BIGINT UNSIGNED | NULL     | ID entitas                  |
| `old_values`  | JSON            | NULL     | Data sebelum perubahan      |
| `new_values`  | JSON            | NULL     | Data setelah perubahan      |
| `ip_address`  | VARCHAR(45)     | NULL     | IP pengguna                 |
| `user_agent`  | TEXT            | NULL     | Informasi perangkat/browser |
| `created_at`  | TIMESTAMP       |          | Waktu aktivitas             |

Audit log sebaiknya tidak menyediakan fitur edit atau hapus melalui antarmuka aplikasi biasa.

## 3. Relasi antartabel (ERD)

Diagram berikut menunjukkan hubungan inti database SIAGA KLA.

\#chatgpt-mermaid-\_r_ef\_{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:16px;fill:rgb(237, 237, 237);}@keyframes edge-animation-frame{from{stroke-dashoffset:0;}}@keyframes dash{to{stroke-dashoffset:0;}}#chatgpt-mermaid-\_r_ef\_ .edge-animation-slow{stroke-dasharray:9,5!important;stroke-dashoffset:900;animation:dash 50s linear infinite;stroke-linecap:round;}#chatgpt-mermaid-\_r_ef\_ .edge-animation-fast{stroke-dasharray:9,5!important;stroke-dashoffset:900;animation:dash 20s linear infinite;stroke-linecap:round;}#chatgpt-mermaid-\_r_ef\_ .error-icon{fill:rgb(48, 48, 48);}#chatgpt-mermaid-\_r_ef\_ .error-text{fill:rgb(237, 237, 237);stroke:rgb(237, 237, 237);}#chatgpt-mermaid-\_r_ef\_ .edge-thickness-normal{stroke-width:1px;}#chatgpt-mermaid-\_r_ef\_ .edge-thickness-thick{stroke-width:3.5px;}#chatgpt-mermaid-\_r_ef\_ .edge-pattern-solid{stroke-dasharray:0;}#chatgpt-mermaid-\_r_ef\_ .edge-thickness-invisible{stroke-width:0;fill:none;}#chatgpt-mermaid-\_r_ef\_ .edge-pattern-dashed{stroke-dasharray:3;}#chatgpt-mermaid-\_r_ef\_ .edge-pattern-dotted{stroke-dasharray:2;}#chatgpt-mermaid-\_r_ef\_ .marker{fill:rgb(175, 175, 175);stroke:rgb(175, 175, 175);}#chatgpt-mermaid-\_r_ef\_ .marker.cross{stroke:rgb(175, 175, 175);}#chatgpt-mermaid-\_r_ef\_ svg{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:16px;}#chatgpt-mermaid-\_r_ef\_ p{margin:0;}#chatgpt-mermaid-\_r_ef\_ .entityBox{fill:rgb(9, 23, 44);stroke:rgb(31, 78, 148);}#chatgpt-mermaid-\_r_ef\_ .relationshipLabelBox{fill:rgb(48, 48, 48);opacity:0.7;background-color:rgb(48, 48, 48);}#chatgpt-mermaid-\_r_ef\_ .relationshipLabelBox rect{opacity:0.5;}#chatgpt-mermaid-\_r_ef\_ .labelBkg{background-color:rgba(48, 48, 48, 0.5);}#chatgpt-mermaid-\_r_ef\_ .edgeLabel{background-color:rgb(0, 0, 0);}#chatgpt-mermaid-\_r_ef\_ .edgeLabel .label rect{fill:rgb(0, 0, 0);}#chatgpt-mermaid-\_r_ef\_ .edgeLabel .label text{fill:rgb(237, 237, 237);}#chatgpt-mermaid-\_r_ef\_ .edgeLabel .label{fill:rgb(31, 78, 148);font-size:14px;}#chatgpt-mermaid-\_r_ef\_ .label{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";color:rgb(237, 237, 237);}#chatgpt-mermaid-\_r_ef\_ .edge-pattern-dashed{stroke-dasharray:8,8;}#chatgpt-mermaid-\_r_ef\_ .node rect,#chatgpt-mermaid-\_r_ef\_ .node circle,#chatgpt-mermaid-\_r_ef\_ .node ellipse,#chatgpt-mermaid-\_r_ef\_ .node polygon{fill:rgb(9, 23, 44);stroke:rgb(31, 78, 148);stroke-width:1px;}#chatgpt-mermaid-\_r_ef\_ .relationshipLine{stroke:rgb(175, 175, 175);stroke-width:1px;fill:none;}#chatgpt-mermaid-\_r_ef\_ .marker{fill:none!important;stroke:rgb(175, 175, 175)!important;stroke-width:1;}#chatgpt-mermaid-\_r_ef\_ [data-look=neo].labelBkg{background-color:rgba(48, 48, 48, 0.5);}#chatgpt-mermaid-\_r_ef\_ .node .neo-node{stroke:rgb(31, 78, 148);}#chatgpt-mermaid-\_r_ef\_ [data-look="neo"].node rect,#chatgpt-mermaid-\_r_ef\_ [data-look="neo"].cluster rect,#chatgpt-mermaid-\_r_ef\_ [data-look="neo"].node polygon{stroke:url(#chatgpt-mermaid-\_r_ef\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_ef\_ [data-look="neo"].swimlane.cluster rect{filter:none;}#chatgpt-mermaid-\_r_ef\_ [data-look="neo"].node path{stroke:url(#chatgpt-mermaid-\_r_ef\_-gradient);stroke-width:1px;}#chatgpt-mermaid-\_r_ef\_ [data-look="neo"].node .outer-path{filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_ef\_ [data-look="neo"].node .neo-line path{stroke:rgb(31, 78, 148);filter:none;}#chatgpt-mermaid-\_r_ef\_ [data-look="neo"].node circle{stroke:url(#chatgpt-mermaid-\_r_ef\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_ef\_ [data-look="neo"].node circle .state-start{fill:#000000;}#chatgpt-mermaid-\_r_ef\_ [data-look="neo"].icon-shape .icon{fill:url(#chatgpt-mermaid-\_r_ef\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_ef\_ [data-look="neo"].icon-shape .icon-neo path{stroke:url(#chatgpt-mermaid-\_r_ef\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_ef\_ :root{--mermaid-font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";}OPDSUSERSEVALUATION_PERIODSCLUSTERSCLUSTER_OPDSINDICATORSINDICATOR_ASSIGNMENTSSUBDOCUMENTSDOCUMENTSDOCUMENT_VERSIONSDOCUMENT_COMMENTSVERIFICATIONSNOTIFICATIONSAUDIT_LOGSmemilikimembuatmemilikimenugaskanmenerimamemilikimemilikiditugaskanmemilikidipenuhimengumpulkanmemilikiversi_terkinimemilikiterkaitmenulisdiperiksadiperiksa_padamemeriksamenerimamelakukan

## 4. Aturan relasi dan indeks database

Selain membuat kolom, ada beberapa aturan yang perlu diterapkan agar database konsisten.

| Aturan                                               | Implementasi                                                      |
| ---------------------------------------------------- | ----------------------------------------------------------------- |
| Periode wajib tersedia sebelum cluster dibuat        | Foreign key `clusters.period_id`                                  |
| Kode cluster unik per periode                        | `UNIQUE(period_id, code)`                                         |
| OPD hanya mengakses penugasan aktif                  | Filter `cluster_opds.revoked_at IS NULL` dan authorization policy |
| Dokumen terpisah per OPD                             | Kombinasi `subdocument_id` dan `opd_id`                           |
| Setiap unggahan ulang menjadi versi baru             | `UNIQUE(document_id, version_number)`                             |
| Status verifikasi tidak boleh ditentukan operator    | Laravel Policy/Gate dan validasi backend                          |
| Progres dihitung dari subdokumen wajib terverifikasi | Query berdasarkan subdokumen dan dokumen terbaru                  |
| Arsip lama tetap dipertahankan                       | Soft delete dan pembatasan perubahan                              |
| Riwayat keputusan tidak ditimpa                      | Tambahkan record baru di `verifications`                          |
| Perubahan penugasan tidak menghapus histori          | Simpan waktu pencabutan pada `cluster_opds`                       |

### Indeks tambahan yang disarankan

| Tabel                | Indeks                                 | Tujuan                     |
| -------------------- | -------------------------------------- | -------------------------- |
| `evaluation_periods` | `(status, year)`                       | Filter periode             |
| `clusters`           | `(period_id, status, sort_order)`      | Daftar cluster per periode |
| `cluster_opds`       | `(opd_id, revoked_at)`                 | Dashboard OPD              |
| `indicators`         | `(cluster_id, is_active, sort_order)`  | Daftar indikator           |
| `documents`          | `(opd_id, status)`                     | Monitoring dokumen per OPD |
| `document_versions`  | `(document_id, version_number)`        | Riwayat versi              |
| `verifications`      | `(document_id, verified_at)`           | Riwayat pemeriksaan        |
| `notifications`      | `(user_id, read_at, created_at)`       | Notifikasi belum dibaca    |
| `audit_logs`         | `(entity_type, entity_id, created_at)` | Penelusuran aktivitas      |

## 5. Catatan implementasi Laravel 12

Ada tiga keputusan desain yang penting sebelum membuat migration.

1\. Status dokumen dan status versi harus dibedakan. Tabel `documents` menyimpan status pengajuan terkini, sedangkan `document_versions` menyimpan file historis. Setiap keputusan verifikasi harus menunjuk versi yang benar.

2\. Gunakan transaksi database saat mengunggah revisi dan mengubah versi aktif. File disimpan di private storage, bukan direktori publik, dan akses unduh harus melalui otorisasi backend.

3\. Jangan menyimpan progres sebagai angka statis jika nilainya dapat dihitung dari dokumen. Hitung progres berdasarkan subdokumen wajib yang telah terverifikasi, dengan lingkup periode, cluster, dan OPD yang sesuai.

Satu detail tambahan: kolom `documents.current_version_id` mereferensikan `document_versions.id`. Foreign key ini sebaiknya ditambahkan setelah tabel `document_versions` dibuat untuk menghindari masalah urutan pembuatan tabel.

Rekomendasi akhir: gunakan 14 tabel inti di atas sebagai fondasi MVP SIAGA KLA. Tambahkan tabel `roles`, `permissions`, dan tabel pivot milik Spatie sesuai kebutuhan autentikasi. Struktur ini sudah mencakup alur konfigurasi periode, penugasan OPD, pengumpulan bukti dukung, revisi, verifikasi, monitoring, dan audit.