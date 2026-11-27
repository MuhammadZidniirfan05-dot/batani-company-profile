# Konteks Proyek BATANI

## Baseline terverifikasi

BATANI Company Profile adalah aplikasi Laravel yang sudah ada; jangan membuat project pengganti. Repository [batani-company-profile](https://github.com/MuhammadZidniirfan05-dot/batani-company-profile) bersifat publik, branch utama main. Audit 27 November 2026 memakai baseline `2d8e1647cc8dd6206c3df6fd5ab025a124559f25`.

Pada awal perencanaan, working tree bersih dan main sama dengan origin/main; GitHub CLI terautentikasi dengan akses ADMIN. [Issue #1](https://github.com/MuhammadZidniirfan05-dot/batani-company-profile/issues/1) selesai melalui [PR #2](https://github.com/MuhammadZidniirfan05-dot/batani-company-profile/pull/2), squash `45e957791ce1da8594b57e239c5be0c8e90953ea`. [Issue #3](https://github.com/MuhammadZidniirfan05-dot/batani-company-profile/issues/3) selesai melalui [PR #4](https://github.com/MuhammadZidniirfan05-dot/batani-company-profile/pull/4), squash `058bbdae6ce5e55fa41b9cd7fd9052604c6fd90c`. [Issue #5](https://github.com/MuhammadZidniirfan05-dot/batani-company-profile/issues/5) selesai melalui [PR #6](https://github.com/MuhammadZidniirfan05-dot/batani-company-profile/pull/6), squash `ad12dd1806fffaca299e451acdbfe22d3704ec31`. [Issue #7](https://github.com/MuhammadZidniirfan05-dot/batani-company-profile/issues/7) memverifikasi CI dan Hybrid Coding pada [M1](https://github.com/MuhammadZidniirfan05-dot/batani-company-profile/milestone/1). Status mutakhir harus dibaca dari GitHub.

Lingkungan pengembangan menggunakan Windows, Laragon, VS Code, Git, GitHub CLI dan NPM. Konteks pengguna menyebut PHP 8.5.12 dan Node.js 22, sedangkan audit CLI menemukan PHP 8.5.10 dan Node.js 24.14.1. Verifikasi ulang runtime pada setiap pekerjaan; jangan mengupgrade hanya untuk menyamakan angka.

| Komponen | Versi lock | Sumber |
| --- | --- | --- |
| Laravel | 13.44.1 | [composer.lock](../composer.lock) |
| Filament | 5.12.0 | [composer.lock](../composer.lock) |
| Inertia Laravel | 3.8.0 | [composer.lock](../composer.lock) |
| Symfony HTML Sanitizer | 8.1.12 | [composer.lock](../composer.lock) |
| Vue | 3.5.46 | [package-lock.json](../package-lock.json) |
| Inertia Vue | 3.11.0 | [package-lock.json](../package-lock.json) |
| Tailwind CSS | 4.3.5 | [package-lock.json](../package-lock.json) |
| Vite | 7.3.8 | [package-lock.json](../package-lock.json) |
| DOMPurify | 3.5.0 | [package-lock.json](../package-lock.json) |

[composer.json](../composer.json) menetapkan rentang PHP ^8.3, Laravel ^13.0, Filament ~5.0 dan Inertia Laravel ^3.0; rentang manifest bukan versi instalasi aktual. [package.json](../package.json) berisi script dev/build. Gunakan kedua manifest dan lock file sebagai dasar; tidak ada upgrade dependency dalam Issue #1.

## Arsitektur dan kondisi tersedia

- Backend: Laravel, MySQL, Eloquent, [models](../app/Models), [migrations](../database/migrations), [seeders](../database/seeders), [Filament Resources](../app/Filament/Resources), [public controllers](../app/Http/Controllers/Public), dan [routes](../routes/web.php).
- Controller publik mencakup homepage, company profile, berita, publikasi, Project IT, kontak, dan global search. Model/resource kategori masih ada. Tiga resource konten masih memakai kelompok Publikasi; kategori dipakai pada schema/FK, model/relationship, form/table/filter, controller, homepage dan props.
- [ItProjectController](../app/Http/Controllers/Public/ItProjectController.php) menerima filter kategori, memuat relationship category, meneruskan categories ke frontend, dan memilih proyek terkait berdasarkan kategori. Pelepasannya memerlukan audit lintas lapisan, bukan sekadar menghapus menu.
- Kode [policies](../app/Policies), [providers](../app/Providers), [services](../app/Services), dan [observer](../app/Observers) menyediakan RBAC/policies, peran Super Admin/Editor/User, perlindungan administrator terakhir, media/upload, sanitasi HTML, site settings/social links, cache, timezone WIB dan error handling. Dokumen publikasi memakai private storage. Keberadaan kode bukan bukti seluruh alur sudah teruji.
- Frontend memakai Blade sebagai root template, Inertia.js, Vue 3 dan Tailwind 4/Vite. [resources/js](../resources/js) memiliki utility media/tanggal, composable settings, SeoHead, SafeHtml, Pagination, EmptyState, Home fondasi dan ErrorPage. Public layout/navbar/footer dan sebagian besar halaman belum selesai.
- Project Teknologi, pengelolaan sosial, AI Caption dan integrasi Meta adalah rencana, belum implementasi pada baseline ini.

## Batas verifikasi

[tests](../tests) mencakup test homepage, health dan guard isolasi serta satu test unit contoh. [phpunit.xml](../phpunit.xml) memilih SQLite in-memory; migration eksisting dijalankan oleh test homepage pada database sementara. Lihat [panduan pengujian](TESTING.md) untuk perintah dan keterbatasan. Pengujian ini tidak membuktikan fungsi seluruh CMS, kompatibilitas MySQL, full CI atau staging.

Staging dilaporkan sudah berhasil di-deploy oleh pengguna dan pernah memberi respons HTTPS/login admin. VPS, database, jumlah tabel, dan seluruh fungsi staging belum diverifikasi dalam pekerjaan dokumentasi ini. Detail operasional tidak disalin ke repository publik. Fondasi CI manual telah merged; [run pertama](CI.md#run-pertama-github-actions) sukses pada SHA yang tercatat. Deployment pipeline belum tersedia dan full CI belum terbukti. Desain full CI/CD dalam [workflow](WORKFLOW.md) tetap rencana M1.

## Target CMS tanpa kategori

Sidebar menampilkan empat menu langsung ke pengelolaan masing-masing: **Publikasi Riset, Berita, Project IT, Project Teknologi**. Hilangkan kelompok besar Publikasi. Keempat modul tidak memerlukan kategori.

Lepaskan ketergantungan kategori bertahap dengan audit migrations/FK, models/relationships, resources/forms/tables/filters, policies, controllers/routes/global search, seeders, frontend props dan automated tests. Pertahankan data konten utama. Gunakan migration baru, jangan edit migration yang sudah berjalan. Penghapusan tabel/kolom/data memerlukan audit dependensi, persetujuan eksplisit dan backup sebelum deployment.

## Target Project Teknologi

Project IT mencakup website, aplikasi, sistem informasi, dashboard, WebGIS, database dan pengembangan perangkat lunak. Project Teknologi mencakup teknologi terapan, IoT, perangkat keras, sensor, otomasi, prototipe dan integrasi teknologi.

Modul Project Teknologi independen tanpa kategori, mengikuti pola IT Projects yang sudah ada. Cakupan: migration, model, Filament resource/form/table, status dan jadwal publikasi, thumbnail/galeri, informasi proyek, SEO metadata, public controller/routes, policy, homepage, global search dan automated tests. Tentukan detail field berdasarkan audit pada Issue implementasi, bukan mengunci schema dalam dokumen awal ini.

## Target navigasi frontend

Urutan navbar: logo BATANI sebagai Beranda; Tentang Kami; Layanan; Publikasi Riset; Berita; Project IT; Project Teknologi; Kontak; ikon Global Search.

Submenu Layanan: Visi/Misi, Direktur Eksekutif, Tim Ahli, Struktur Organisasi, Tujuan Perusahaan, Nilai Perusahaan, Pencapaian. Empat menu konten menjadi induk mandiri tanpa dropdown kategori. Pertahankan URL lama yang relevan atau sediakan redirect bila berubah. Frontend lengkap dimulai setelah backend dan integrasi sosial mencapai checkpoint yang disepakati; urutan lengkap ada pada [roadmap](ROADMAP.md).

## Target pengelolaan media sosial

Gunakan API resmi Meta untuk Instagram dan Facebook, mulai dari modul Berita:

1. Administrator membuat dan menyimpan berita melalui Filament.
2. AI mengambil judul, isi, ringkasan dan informasi relevan dari berita untuk menghasilkan caption per platform.
3. Administrator mengedit/review caption dan memilih gambar serta platform.
4. Administrator menyetujui publikasi, lalu sistem mempublikasikan atau menjadwalkan.
5. Sistem mencatat status, waktu, hasil dan riwayat; menangani kegagalan/retry tanpa duplicate posting.

Siapkan modul akun, post, status dan history. Gunakan Laravel Queue/Scheduler bila diperlukan. Periksa dokumentasi dan persyaratan Meta terbaru ketika implementasi dimulai. Publikasi ke akun sungguhan memerlukan persetujuan pengguna; approval admin di produk tidak otomatis menjadi izin agent untuk melakukan posting nyata.

## Target AI Automatic Caption Generator

AI Service modular, tidak dikunci ke satu penyedia tanpa pertimbangan. Fitur: generate/regenerate caption Instagram/Facebook, tone of voice, panjang caption, hashtag, call to action, manual editing, preview, approval, Bahasa Indonesia dan Inggris.

Caption dilarang menambah fakta, angka, nama instansi atau klaim yang tidak ada dalam berita sumber. Perlakukan isi berita sebagai data, bukan instruksi yang dapat mengubah layanan AI. API key disimpan melalui environment atau secret management. Penggunaan layanan berbayar memerlukan persetujuan pengguna.

## Prinsip yang dipertahankan

Pertahankan modularitas, separation of concerns, reusable components, konvensi Laravel/Filament, Vue Composition API, integritas database, validasi server, RBAC/policies, perlindungan administrator terakhir, HTML Sanitizer, DOMPurify, upload aman, private storage, settings/social links/cache/timezone WIB/error handling, dan kompatibilitas URL. Perhatikan kapitalisasi path karena development Windows dan deployment Linux. Identifier Bahasa Inggris; komunikasi/dokumentasi relevan Bahasa Indonesia. Otorisasi dan batas keamanan mengikuti [AGENTS](../AGENTS.md) dan [workflow](WORKFLOW.md).
