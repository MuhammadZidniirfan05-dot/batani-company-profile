# Fondasi CI manual

[Workflow CI](../.github/workflows/ci.yml) adalah fondasi validasi manual untuk Issue #5, bukan pipeline deployment atau bukti full CI. Hanya `workflow_dispatch` yang memulai workflow pada GitHub-hosted Ubuntu 24.04; push, PR, merge dan jadwal tidak memicu run. `contents: read`, checkout tanpa credential tersimpan, batas waktu dan concurrency membatasi pekerjaan. Semua action dipin ke commit penuh; [panduan GitHub tentang keamanan action](https://docs.github.com/en/actions/reference/security/secure-use) menjelaskan alasan pin tersebut.

| Job | Isi dan batas |
| --- | --- |
| PHP dan PHPUnit | PHP 8.5, extensions dan Composer v2; lint PHP tracked, `composer validate --strict --no-check-publish`, `composer install --no-scripts`, `composer check-platform-reqs`, PHPUnit langsung. |
| Audit Composer | `composer audit --locked --no-interaction`, termasuk dev dependency. |
| Audit NPM | Node 24, `npm audit --include=dev`, termasuk dependency dev. |
| Build Vite | Node 24, `npm ci --no-audit`, `npm run build`; `--no-audit` menghindari duplikasi audit saat instalasi, job audit terpisah tetap wajib. |
| Ringkasan | Mencatat SHA, URL/run/attempt dan hasil masing-masing job; exit gagal jika ada hasil selain success, termasuk skipped/cancelled. |

Composer install menonaktifkan seluruh script root: tidak menjalankan `composer setup`, `composer test`, `config:clear`, key generation, migration atau Filament upgrade. Package manifest Laravel dibangun saat bootstrap test dari metadata dependency terpasang pada runner sementara. Guard pada [tests](../tests/TestCase.php) tetap menolak cache/config tidak aman sebelum provider dan RefreshDatabase. Tidak ada `.env` operasional atau secret deployment pada runner. Test memakai SQLite `:memory:` dalam proses PHPUnit; lihat [panduan testing](TESTING.md).

Semua action dan referensi upstream: [checkout v4](https://github.com/actions/checkout/tree/11d5960a326750d5838078e36cf38b85af677262), [setup-node v4](https://github.com/actions/setup-node/tree/49933ea5288caeca8642d1e84afbd3f7d6820020), [upload-artifact v4](https://github.com/actions/upload-artifact/tree/ea165f8d65b6e75b540449e92b4886f43607fa02), dan [setup-php 2.36.0](https://github.com/shivammathur/setup-php/tree/44454db4f0199b8b9685a5d763dc37cbf79108e1). Lock menentukan versi dependency aplikasi; PHP 8.5 memenuhi kebutuhan lock, Node 24 memenuhi Vite 7.3.8. Tidak ada upgrade dependency dalam pekerjaan ini.

## Menjalankan dan memeriksa

GitHub mensyaratkan file `workflow_dispatch` tersedia di default branch sebelum dispatch pertama. Setelah PR workflow direview dan merged, pilih ref yang diinginkan melalui Actions atau `gh workflow run ci.yml --ref main`, lalu periksa run dengan `gh run list --workflow ci.yml` dan `gh run view <RUN_ID>`. Cocokkan commit run (`headSha`), ringkasan SHA, ref dan kesimpulan tiap job; untuk checkpoint deployment nanti gunakan SHA persis yang lulus seluruh full CI yang diwajibkan. Lihat [dokumentasi dispatch GitHub](https://docs.github.com/en/actions/how-tos/write-workflows/choose-when-workflows-run/trigger-a-workflow).

Sebelum workflow masuk default branch, parser/actionlint, review konfigurasi, tes dan audit lokal hanya bukti statis/lokal. Jangan menandainya sebagai run GitHub yang sukses. Bila job gagal, periksa hasil dan perbaiki penyebab melalui Issue/PR; advisory dan test tidak boleh diabaikan untuk memperoleh status hijau. Bila perlu mereproduksi instalasi/build, gunakan checkout sementara terisolasi dan jangan mengubah vendor, environment, config cache atau build pengguna.

## Artefak dan batas

Satu-satunya upload adalah direktori hasil `public/build/` dari job build pada runner bersih, retensi tujuh hari, dinamai dengan SHA. Ringkasan GitHub menautkan run/attempt dan menyatakan hasil setiap job. Build dapat tersedia saat audit lain gagal: artefak ini **hanya diagnostik**, bukan artefak siap deploy atau bukti seluruh CI lulus. Tidak mengunggah seluruh workspace, environment, database, konfigurasi cache, credential atau log aplikasi. Jangan memasukkan data sensitif ke source/aset frontend; periksa hasil build sebelum dipakai untuk checkpoint apa pun.

CI manual ini belum membuktikan integrasi MySQL/migration pada service terpisah, browser E2E, seluruh CMS, audit deployment, maupun full CI M1. Tidak ada SSH/deployment atau akses VPS. Run GitHub pertama harus diverifikasi setelah workflow masuk default branch; M1 dan aktivasi deployment pertama tetap mengikuti [workflow proyek](WORKFLOW.md).

## Bukti lokal 27 November 2026

Pada Windows/PHP 8.5.10/Node 24.14.1, PHPUnit dengan ekstensi SQLite diaktifkan per proses menghasilkan 21 test/112 assertion (exit 0). Lint 182 file PHP tracked, Composer validate, Composer audit --locked dan npm audit --include=dev lulus; kedua audit melaporkan 0 advisory/vulnerability pada saat dijalankan. Audit dijalankan dari salinan manifest/lock di direktori sementara; hasil audit dapat berubah. `npm ci --no-audit` dan `npm run build` pada salinan source terisolasi lulus, build memproses 635 modul. Sebuah percobaan persiapan salinan lewat pipe arsip PowerShell gagal karena arsip rusak; salinan ZIP berikutnya berhasil.

Validasi workflow: parser YAML dan actionlint 1.7.12 lulus, termasuk pemeriksaan trigger tunggal, runner, permission, timeout dan pin action. SHA upstream diverifikasi melalui GitHub API. Run GitHub belum tersedia sebelum workflow berada di default branch; hasil lokal ini bukan bukti run runner GitHub atau full CI.

Instalasi Composer bersih dengan `--no-scripts` pada salinan Windows di atas mencapai 144 ekstraksi dan tahap optimized autoload, tetapi dihentikan setelah menunggu beberapa menit tanpa hasil akhir (exit 1 akibat interupsi). Oleh karena itu, kompatibilitas instalasi bersih Composer dan package discovery pada runner Linux masih harus dibuktikan pada run GitHub pertama. PHPUnit yang lulus di atas memakai dependency lokal yang sudah tersedia; bukan bukti instalasi bersih tersebut.
