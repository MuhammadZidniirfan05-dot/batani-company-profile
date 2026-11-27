# Pengujian lokal

Gunakan PHP yang memenuhi [composer.json](../composer.json), dengan ekstensi PDO SQLite, SQLite3 dan ekstensi Composer yang dibutuhkan proyek, serta dependency dari lock file yang sudah terpasang. [phpunit.xml](../phpunit.xml) memaksa lingkungan testing, database SQLite `:memory:`, key sintetis dan driver lokal. Jangan gunakan nilai key ini di luar test.

Test bootstrap menolak file konfigurasi cache sebelum bootstrap dan menolak konfigurasi efektif yang tidak aman setelah konfigurasi dimuat, sebelum provider dan RefreshDatabase berjalan. Test tidak memuat `.env` atau `.env.testing` pengguna. Migration eksisting berjalan dalam proses PHPUnit dan database sementara; tidak menggunakan seeder atau database operasional. Cache, session, queue dan mail memakai array/sync. Test feature homepage menggunakan `withoutVite()`; tidak memerlukan manifest atau server Vite dan tidak menguji rendering browser.

```powershell
php vendor/bin/phpunit tests/Feature/HomePageTest.php tests/Feature/HealthCheckTest.php tests/Unit/TestEnvironmentSafetyTest.php
php vendor/bin/phpunit
```

Jika CLI belum memuat ekstensi SQLite tetapi DLL yang cocok sudah tersedia, ekstensi dapat diaktifkan hanya untuk proses ini, misalnya `php -d extension=pdo_sqlite -d extension=sqlite3 vendor/bin/phpunit`. Jika konfigurasi cache terdeteksi, test berhenti; periksa dan kelola cache sesuai lingkungan Anda secara sadar sebelum menjalankan ulang. Jangan menghapus cache pengguna secara otomatis. Hindari `composer test` jika tidak ingin menjalankan `config:clear` dari script Composer.

SQLite tidak membuktikan parity MySQL, deployment, browser E2E, semua fungsi CMS, atau full CI. Pengujian migration pada database MySQL uji terpisah dan CI manual direncanakan pada pekerjaan berikutnya. Lihat [roadmap](ROADMAP.md) dan [workflow](WORKFLOW.md).

Pada 27 November 2026, PHP 8.5.10 lokal belum memuat PDO SQLite secara default. Dengan ekstensi yang sudah tersedia diaktifkan melalui `-d extension=pdo_sqlite -d extension=sqlite3`, focused suite menghasilkan 18 test/97 assertion, suite lengkap 19 test/98 assertion, dan pengulangan focused suite 18 test/97 assertion (exit code 0). Percobaan pertama tanpa ekstensi menghasilkan tiga error driver; tidak ada koneksi database operasional. Hasil ini bukan full CI.
