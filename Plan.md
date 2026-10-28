# PRODUCT REQUIREMENTS DOCUMENT (PRD)
# SIAGA KLA
**Sistem Informasi Aksi dan Monitoring Evaluasi Kabupaten/Kota Layak Anak**

Versi: 1.1  
Status: Draft Perencanaan Produk

## 1. Ringkasan Produk

SIAGA KLA adalah aplikasi berbasis web untuk pengelolaan indikator, pengumpulan bukti dukung, monitoring, evaluasi, dan verifikasi pemenuhan indikator Kabupaten/Kota Layak Anak (KLA).

Aplikasi menghubungkan Admin KLA dengan Organisasi Perangkat Daerah (OPD). Setiap OPD memiliki akun untuk mengelola bukti dukung sesuai periode evaluasi, cluster, dan indikator yang menjadi tanggung jawabnya.

Sistem menerapkan alur konfigurasi terstruktur: **Admin membuat periode → membuat cluster → menentukan OPD sasaran → membuat indikator → menentukan subdokumen → OPD mengunggah bukti dukung → Admin melakukan verifikasi.**

Setiap file dapat memiliki catatan, riwayat revisi, versi dokumen, dan status verifikasi.

Tagline: **“Satu Indikator, Satu Bukti, Satu Penanggung Jawab, Tepat Waktu.”**

## 2. Tujuan Produk

1. Mengintegrasikan pengelolaan indikator KLA dalam satu platform.
2. Memberikan akses khusus kepada setiap OPD.
3. Mengelola periode evaluasi secara terstruktur.
4. Memungkinkan Admin menentukan cluster untuk OPD tertentu.
5. Mengelola indikator dan persyaratan dokumen pada setiap cluster.
6. Menyimpan dokumen, catatan evaluasi, dan riwayat revisi.
7. Memantau progres berdasarkan periode, cluster, dan OPD.
8. Menghasilkan laporan evaluasi yang dapat ditelusuri.

## 3. Pengguna dan Hak Akses

### 3.1 Admin KLA Dan PIC

Admin mempunyai hak akses untuk:
- Mengelola akun OPD dan operator.
- Membuat, mengubah, membuka, menutup, serta mengarsipkan periode.
- Membuat cluster di dalam periode.
- Menentukan satu atau beberapa OPD sasaran untuk setiap cluster.
- Membuat indikator pada setiap cluster.
- Menentukan OPD penanggung jawab indikator jika diperlukan.
- Membuat subdokumen dan persyaratan bukti dukung.
- Menentukan tenggat waktu.
- Memeriksa, menyetujui, menolak, atau meminta revisi dokumen.
- Memberikan catatan pada setiap subdokumen atau file.
- Memantau progres seluruh OPD.
- Membuat laporan dan mengekspor data.
- Melihat audit log dan riwayat perubahan.

### 3.2 Operator OPD

Setiap operator terhubung dengan akun OPD tertentu.

Hak akses:
- Melihat periode yang tersedia bagi OPD.
- Melihat cluster yang ditujukan kepada OPD.
- Melihat indikator dalam cluster yang menjadi kewenangannya.
- Mengunggah satu atau beberapa file pada subdokumen.
- Memberikan deskripsi dan keterangan dokumen.
- Melihat catatan dan permintaan revisi dari Admin.
- Mengunggah versi dokumen terbaru.
- Melihat riwayat versi dan status verifikasi.
- Melihat progres serta tenggat waktu OPD.
- Mengunduh dokumen sesuai hak akses.

Operator tidak dapat membuat periode, mengubah cluster, menambah indikator global, mengubah status menjadi terverifikasi, atau melihat dokumen OPD lain tanpa kewenangan khusus.

## 4. Struktur Hierarki Data

Struktur utama aplikasi:

**Periode → Cluster → OPD Sasaran → Indikator → Subdokumen → File → Catatan dan Revisi**

Contoh struktur:

- Periode: Evaluasi KLA Tahun 2026
  - Cluster: Hak Sipil dan Kebebasan
    - OPD sasaran: OPD A, OPD B
    - Indikator 1: Pemenuhan hak identitas anak
      - Subdokumen: Dokumen kebijakan
        - File bukti dukung
        - Catatan Admin
        - Riwayat revisi
      - Subdokumen: Data pelayanan
        - File bukti dukung
        - Catatan Admin
        - Riwayat revisi
  - Cluster: Lingkungan Keluarga dan Pengasuhan Alternatif
    - OPD sasaran: OPD C, OPD D
    - Indikator sesuai cluster
      - Subdokumen dan bukti dukung

OPD hanya melihat cluster dan indikator yang menjadi kewenangannya.

Jika satu cluster ditujukan kepada beberapa OPD, masing-masing OPD mempunyai data pemenuhan, dokumen, catatan, dan progres tersendiri.

## 5. Modul Periode Evaluasi

Periode merupakan entitas utama dan wajib tersedia sebelum Admin membuat cluster.

### 5.1 Data Periode

Setiap periode mempunyai atribut:
- Kode periode.
- Nama periode.
- Tahun evaluasi.
- Deskripsi.
- Tanggal mulai.
- Tanggal akhir atau batas pengumpulan.
- Status periode.
- Tanggal pembuatan.
- Admin pembuat.

Contoh:
- Kode: KLA-2026
- Nama: Evaluasi Pemenuhan Indikator KLA Tahun 2026
- Tanggal mulai: 1 Januari 2026
- Batas pengumpulan: 30 November 2026
- Status: Aktif

Tanggal di atas hanya contoh dan dapat disesuaikan dengan jadwal evaluasi pemerintah daerah.

### 5.2 Status Periode

Status yang disediakan:
- **Draft:** konfigurasi periode masih disiapkan.
- **Aktif:** periode dibuka dan OPD dapat mengisi bukti dukung.
- **Ditutup:** pengumpulan dokumen dihentikan sesuai ketentuan periode.
- **Diarsipkan:** periode menjadi arsip dan hanya dapat diakses sesuai kewenangan.

### 5.3 Fitur Periode

Admin dapat:
- Membuat periode baru.
- Mengubah informasi periode.
- Menentukan tanggal mulai dan batas pengumpulan.
- Mengaktifkan periode.
- Menutup periode.
- Mengarsipkan periode.
- Melihat seluruh cluster dalam periode.
- Melihat progres seluruh OPD dalam periode.
- Membandingkan hasil antarperiode.

Sistem harus mempertahankan data periode lama. Periode baru tidak boleh menghapus dokumen, catatan, atau riwayat periode sebelumnya.

## 6. Modul Cluster dan OPD Sasaran

Cluster hanya dapat dibuat setelah periode tersedia.

### 6.1 Data Cluster

Atribut cluster:
- Kode cluster.
- Nama cluster.
- Deskripsi.
- Periode terkait.
- Urutan tampilan.
- OPD sasaran.
- Status cluster.
- Tanggal pembuatan.

### 6.2 Pembuatan Cluster

Alur Admin:
1. Membuka menu Periode.
2. Memilih periode yang telah dibuat.
3. Membuka menu Cluster.
4. Memilih Tambah Cluster.
5. Mengisi kode, nama, dan deskripsi.
6. Memilih satu atau beberapa OPD sasaran.
7. Menyimpan cluster.
8. Menambahkan indikator pada cluster tersebut.

Admin dapat membuat beberapa cluster dalam satu periode.

### 6.3 Pengaturan OPD Sasaran

Saat membuat atau mengubah cluster, Admin dapat memilih OPD yang berhak mengisi indikator pada cluster tersebut.

Fitur:
- Memilih satu OPD.
- Memilih beberapa OPD.
- Mengubah daftar OPD sasaran.
- Melihat daftar indikator yang berlaku untuk OPD.
- Melihat progres pengumpulan bukti dukung setiap OPD.

Perubahan OPD sasaran tidak boleh menghapus data yang sudah ada. Jika penugasan diubah setelah dokumen dikumpulkan, sistem harus mempertahankan riwayat penugasan dan dokumen sebelumnya.

### 6.4 Pembatasan Penugasan Indikator

Secara default, indikator mengikuti OPD sasaran cluster. Admin dapat memberikan pengecualian pada tingkat indikator jika hanya OPD tertentu yang wajib memenuhi indikator tersebut.

Sistem tidak boleh memberikan akses kepada OPD yang tidak termasuk dalam penugasan aktif.

## 7. Modul Indikator

Indikator hanya dapat dibuat di dalam cluster pada periode tertentu.

Atribut indikator:
- Kode indikator.
- Nama indikator.
- Deskripsi.
- Definisi operasional.
- Cluster terkait.
- Periode terkait melalui cluster.
- Persyaratan pemenuhan.
- OPD penanggung jawab atau pengecualian penugasan.
- Tenggat waktu.
- Bobot penilaian jika diperlukan.
- Status aktif.

Admin dapat:
- Menambah indikator.
- Mengubah indikator.
- Mengatur urutan indikator.
- Menambahkan beberapa indikator dalam satu cluster.
- Menentukan persyaratan pemenuhan.
- Menentukan subdokumen wajib dan opsional.
- Mengatur tenggat waktu indikator.

## 8. Modul Subdokumen dan File Bukti Dukung

Setiap indikator dapat memiliki beberapa subdokumen. Setiap subdokumen dapat mempunyai beberapa file.

### 8.1 Data Subdokumen

- Nama subdokumen.
- Deskripsi persyaratan.
- Status wajib atau opsional.
- Format file yang diizinkan.
- Batas ukuran file.
- Tenggat waktu.
- Instruksi pengisian.

### 8.2 Upload File

Operator dapat:
- Mengunggah file.
- Mengisi nama dan deskripsi file.
- Menambahkan keterangan pendukung.
- Melihat status verifikasi.
- Mengunggah revisi.
- Melihat riwayat versi.
- Mengunduh dokumen sesuai kewenangan.

Format awal yang didukung: PDF, DOCX, XLSX, JPG, dan PNG.

Sistem memvalidasi format, ukuran, serta keamanan file. File disimpan secara privat dan hanya dapat diakses oleh pengguna yang berwenang.

## 9. Modul Catatan dan Revisi

Setiap subdokumen mempunyai ruang catatan yang memungkinkan Admin dan OPD berkomunikasi.

Contoh:
- Dokumen belum ditandatangani.
- Periode data tidak sesuai.
- Mohon tambahkan data pendukung.
- Dokumen telah diperbaiki dan diajukan kembali.

Fitur:
- Admin memberikan catatan.
- Admin menandai catatan sebagai permintaan revisi.
- OPD memberikan tanggapan.
- Sistem mencatat pengguna dan waktu.
- Catatan lama tetap tersimpan.
- Riwayat revisi dapat dilihat berdasarkan versi dokumen.

Setiap unggahan ulang membuat versi baru, misalnya Versi 1, Versi 2, dan seterusnya. Versi lama tetap tersimpan.

## 10. Modul Verifikasi

Status dokumen:
- Belum Diunggah.
- Menunggu Verifikasi.
- Perlu Revisi.
- Diajukan Kembali.
- Terverifikasi.
- Ditolak.

Alur:
1. OPD mengunggah dokumen.
2. OPD mengajukan dokumen untuk diverifikasi.
3. Admin memeriksa dokumen.
4. Admin menyetujui atau memberikan catatan revisi.
5. Jika perlu revisi, OPD mengunggah versi baru.
6. Admin memeriksa ulang.
7. Jika memenuhi ketentuan, Admin mengubah status menjadi Terverifikasi.

Setiap keputusan verifikasi menyimpan identitas pemeriksa, waktu, catatan, dan versi yang diperiksa.

## 11. Dashboard dan Monitoring

### 11.1 Dashboard Admin

Menampilkan:
- Jumlah periode.
- Periode aktif.
- Jumlah cluster per periode.
- Jumlah indikator.
- Jumlah OPD sasaran.
- Dokumen menunggu verifikasi.
- Dokumen perlu revisi.
- Indikator lengkap dan belum lengkap.
- Progres per periode.
- Progres per cluster.
- Progres per OPD.
- Indikator melewati tenggat waktu.

Admin dapat memilih periode untuk melihat data yang relevan.

### 11.2 Dashboard OPD

Menampilkan:
- Periode aktif yang dapat diakses.
- Cluster yang ditugaskan.
- Jumlah indikator yang harus dipenuhi.
- Subdokumen yang belum diunggah.
- Dokumen menunggu verifikasi.
- Dokumen perlu revisi.
- Dokumen terverifikasi.
- Tenggat waktu.
- Persentase progres OPD.

Dashboard OPD tidak menampilkan dokumen privat atau progres internal OPD lain.

## 12. Notifikasi dan Audit Log

Notifikasi internal diberikan saat:
- Periode dibuka atau ditutup.
- Cluster atau indikator ditugaskan.
- Tenggat waktu mendekat.
- Dokumen diajukan.
- Catatan revisi diberikan.
- Dokumen disetujui atau ditolak.

Audit log mencatat:
- Pembuatan dan perubahan periode.
- Pembuatan cluster dan perubahan OPD sasaran.
- Perubahan indikator.
- Pengunggahan dan revisi file.
- Catatan dan keputusan verifikasi.
- Perubahan hak akses.
- Aktivitas penting pengguna.

## 13. Aturan Bisnis

1. Periode harus tersedia sebelum cluster dapat dibuat.
2. Cluster wajib terhubung dengan satu periode.
3. Cluster wajib memiliki setidaknya satu OPD sasaran sebelum dipublikasikan untuk pengisian.
4. Indikator harus terhubung dengan satu cluster.
5. OPD hanya dapat mengakses cluster dan indikator sesuai penugasan.
6. Indikator mengikuti daftar OPD sasaran cluster, kecuali terdapat pengaturan penugasan khusus.
7. Satu indikator dapat memiliki beberapa subdokumen.
8. Satu subdokumen dapat memiliki beberapa file dan versi.
9. Dokumen yang diunggah tidak otomatis dianggap terverifikasi.
10. Hanya Admin berwenang yang dapat menyetujui dokumen.
11. Catatan dan riwayat revisi tidak boleh hilang ketika dokumen diperbarui.
12. Periode yang ditutup tidak menerima pengajuan baru, kecuali Admin memberikan pengecualian yang tercatat.
13. Periode yang diarsipkan tidak dapat diubah oleh operator.
14. Pengarsipan tidak menghapus dokumen dan audit log.
15. Progres indikator dihitung berdasarkan subdokumen wajib yang telah terverifikasi.
16. Progres antarperiode harus dihitung dan ditampilkan secara terpisah.
17. Setiap perubahan penugasan harus mempertahankan riwayat dan tidak memberikan akses tidak sah terhadap dokumen lama.

## 14. Rancangan Halaman Aplikasi

### Halaman Admin
- Dashboard.
- Manajemen OPD.
- Manajemen akun pengguna.
- Periode evaluasi.
- Detail periode.
- Manajemen cluster.
- Pengaturan OPD sasaran.
- Manajemen indikator.
- Manajemen subdokumen.
- Verifikasi dokumen.
- Catatan dan revisi.
- Monitoring progres.
- Laporan dan ekspor.
- Audit log.
- Pengaturan sistem.

### Halaman Operator OPD
- Dashboard OPD.
- Daftar periode.
- Daftar cluster yang ditugaskan.
- Daftar indikator.
- Detail indikator.
- Subdokumen dan upload file.
- Catatan dan revisi.
- Riwayat versi.
- Notifikasi.
- Laporan progres.
- Profil akun.

## 15. Rancangan Entitas Database

| Entitas | Fungsi |
|---|---|
| users | Akun admin dan operator |
| opds | Data organisasi perangkat daerah |
| evaluation_periods | Periode evaluasi |
| clusters | Cluster dalam periode |
| cluster_opds | Relasi cluster dengan OPD sasaran |
| indicators | Indikator pada cluster |
| indicator_assignments | Penugasan indikator kepada OPD jika diperlukan |
| subdocuments | Kebutuhan bukti dukung |
| documents | Identitas dokumen |
| document_versions | Riwayat file dan versi |
| document_comments | Catatan dan tanggapan |
| verifications | Riwayat pemeriksaan |
| notifications | Notifikasi |
| audit_logs | Catatan aktivitas sistem |

Relasi utama:
- Satu periode memiliki banyak cluster.
- Satu cluster memiliki banyak indikator.
- Satu cluster dapat ditujukan kepada banyak OPD.
- Satu OPD dapat menjadi sasaran banyak cluster.
- Satu indikator memiliki banyak subdokumen.
- Satu subdokumen memiliki banyak dokumen atau file.
- Satu dokumen memiliki banyak versi.
- Setiap subdokumen dapat memiliki banyak catatan dan riwayat verifikasi.

Jika satu indikator ditugaskan kepada beberapa OPD, data pengumpulan bukti dukung harus dipisahkan berdasarkan OPD agar progres dan dokumen tidak bercampur.

## 16. Rekomendasi Teknologi

- Backend: Laravel 12.
- Frontend: Vue 3 + TypeScript + Inertia.js.
- UI: Tailwind CSS v4 + shadcn-vue + Lucide.
- Database: MySQL atau PostgreSQL.
- Autentikasi: Laravel Authentication.
- Otorisasi: Laravel Policies/Gates dan Spatie Laravel Permission jika diperlukan.
- Penyimpanan: Private local storage atau object storage.
- Ekspor: Excel dan PDF.
- Deployment: VPS Linux, Nginx, dan PHP-FPM.

## 17. Ruang Lingkup MVP

Versi pertama wajib menyediakan:
1. Login Admin dan Operator OPD.
2. Manajemen akun dan OPD.
3. CRUD periode dan status periode.
4. CRUD cluster dalam periode.
5. Pemilihan OPD sasaran cluster.
6. CRUD indikator dalam cluster.
7. Pengaturan penugasan indikator.
8. CRUD subdokumen.
9. Upload beberapa file bukti dukung.
10. Catatan, tanggapan, dan revisi.
11. Versioning file.
12. Verifikasi dokumen.
13. Dashboard progres berdasarkan periode, cluster, dan OPD.
14. Filter dan ekspor laporan.
15. Audit log dan kontrol akses dokumen.

## 18. Kriteria Penerimaan

MVP dianggap memenuhi kebutuhan jika:
- Admin harus membuat periode sebelum membuat cluster.
- Admin dapat membuat beberapa cluster pada satu periode.
- Admin dapat memilih beberapa OPD sebagai sasaran cluster.
- Indikator dapat ditambahkan pada setiap cluster.
- Operator hanya melihat cluster dan indikator yang menjadi kewenangannya.
- Operator dapat mengunggah banyak file untuk setiap subdokumen.
- Admin dapat memberikan catatan dan meminta revisi.
- Versi lama tetap tersedia setelah unggahan ulang.
- Status verifikasi hanya dapat diubah oleh pengguna yang berwenang.
- Dashboard menunjukkan progres sesuai periode yang dipilih.
- Periode yang berbeda memiliki data dan progres terpisah.
- Penutupan atau pengarsipan periode tidak menghilangkan riwayat.
- Laporan dapat difilter berdasarkan periode, cluster, dan OPD.

## 19. Tahapan Pengembangan

**Tahap 1 — Fondasi**
- Autentikasi dan hak akses.
- Data OPD.
- Periode evaluasi.

**Tahap 2 — Konfigurasi evaluasi**
- Cluster.
- OPD sasaran cluster.
- Indikator.
- Penugasan indikator.
- Subdokumen.

**Tahap 3 — Pengumpulan dokumen**
- Upload file.
- Metadata.
- Catatan.
- Versioning.

**Tahap 4 — Verifikasi**
- Status dokumen.
- Revisi.
- Notifikasi.
- Dashboard progres.

**Tahap 5 — Pelaporan dan implementasi**
- Ekspor laporan.
- Audit log.
- Pengujian keamanan.
- Pengujian lintas OPD.
- Backup, deployment, dan pelatihan operator.

## 20. Kesimpulan

SIAGA KLA menggunakan periode sebagai dasar penyelenggaraan evaluasi. Admin membuat periode, menyusun cluster, menetapkan OPD sasaran, lalu mengelola indikator dan kebutuhan bukti dukung.

Setiap OPD mengisi dokumen sesuai kewenangannya. Admin melakukan verifikasi dan memberikan catatan revisi sampai persyaratan terpenuhi.

Dengan struktur ini, SIAGA KLA mendukung pengelolaan evaluasi yang terorganisasi, pemisahan data antarperiode, transparansi penanggung jawab, dan penelusuran dokumen serta revisi secara terstruktur.
