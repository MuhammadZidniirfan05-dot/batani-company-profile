# Workflow Pengembangan BATANI

## Prinsip koordinasi

GitHub Issues, linked branches, PR, labels, milestones dan dokumentasi menjadi pusat koordinasi. Gunakan Simplified Hybrid Coding: satu kelompok fitur/Issue/linked branch/PR utama, dengan beberapa subpekerjaan dan commit terkait; beberapa kelompok membentuk milestone. Baca [AGENTS](../AGENTS.md), Issue aktif, [Roadmap](ROADMAP.md) dan source yang relevan; dokumen lain dibaca bila diperlukan. Gunakan GitHub CLI dan hindari audit seluruh repository atau Issue administratif berulang. Model Planner/Reviewer dan Worker dipilih manual oleh pengguna dalam satu chat; agent tidak mengganti model otomatis.

Fast track berlaku bagi perubahan rendah risiko seperti label, ikon, nama/urutan menu, typo, teks, styling atau komponen sederhana yang masih sesuai scope Issue aktif; tidak perlu Issue atau PR terpisah. Perubahan database destruktif, autentikasi, otorisasi, keamanan, credential dan infrastruktur berisiko tinggi tetap mengikuti persetujuan dan pengujian yang berlaku. Kelompokkan pekerjaan terkait dalam Issue; Planner mencatat perubahan scope yang sah tanpa mengubah acceptance criteria sepihak.

## PLANNER

1. Periksa Issue aktif, source dan status Git yang relevan, baseline dan perubahan pengguna. Jangan menimpa perubahan atau membuat Issue duplikat; audit tambahan dilakukan sesuai risiko.
2. Tentukan pekerjaan berikut berdasarkan roadmap: scope/out of scope, dependensi/risiko, rencana teknis, acceptance criteria terukur, targeted tests, database/security/deployment/rollback.
3. Buat satu Issue memakai [Issue Form](../.github/ISSUE_TEMPLATE/implementation.yml), pilih milestone, label planned. Jangan membuat semua Issue roadmap sekaligus.
4. Buat linked branch dari main terbaru jika aman, sesuai nomor Issue aktual. Handoff ke Worker; jangan mengimplementasikan fitur sebagai Planner.

## WORKER

1. Baca Issue lengkap dan instruksi, verifikasi linked branch/HEAD/status dan pekerjaan pengguna. Ubah planned menjadi in-progress.
2. Implementasikan seluruh subpekerjaan terkait dalam scope Issue; beberapa commit pada branch yang sama diperbolehkan. Gunakan identifier Bahasa Inggris dan konvensi Laravel/Filament/Vue. Jalankan syntax/targeted tests, validasi fungsional dan keamanan bagian diubah, perbaiki error, periksa diff.
3. Stage file spesifik; commit Bahasa Indonesia; push feature branch; buat PR menuju main menggunakan [PR template](../.github/PULL_REQUEST_TEMPLATE.md) dan `Closes #ISSUE_NUMBER` yang diganti nomor aktual.
4. Sertakan bukti tes/build jika relevan, pemetaan acceptance criteria, keterbatasan, dampak database/security/deployment/rollback. Ganti in-progress menjadi ready-for-review pada Issue dan PR, lalu handoff Reviewer.

Worker tidak push main, merge, deploy, mengubah kriteria sepihak, melemahkan/menghapus tests untuk lolos, menyembunyikan kegagalan, menonaktifkan keamanan, mengganti arsitektur di luar scope atau menjalankan migration destruktif tanpa izin. Bila terhambat, label blocked dan tuliskan penyebab/bukti/kebutuhan penyelesaian; jangan tutup Issue.

## REVIEWER

Baca Issue asli, acceptance criteria, PR, diff aktual, source terdampak, tests, required checks dan komentar review terdahulu. Jangan hanya mempercayai ringkasan Worker. Evaluasi functional correctness, scope, kualitas/maintainability, keamanan, integritas database, kompatibilitas, testing, deployment readiness dan regresi fitur eksisting.

| Severity | Makna |
| --- | --- |
| CRITICAL | Risiko kehilangan data, keamanan serius, atau gangguan layanan. |
| HIGH | Kegagalan fungsi atau acceptance criteria material. |
| MEDIUM | Masalah kualitas atau risiko yang perlu diperbaiki. |
| LOW | Penyempurnaan non-blocking. |

### CHANGES REQUESTED

Kumpulkan seluruh temuan blocking yang ditemukan dalam satu komentar PR berisi severity, file/lokasi, kondisi aktual, harapan, alasan penolakan, instruksi perbaikan, tes yang diulang dan kriteria belum terpenuhi. Ganti status Issue/PR menjadi changes-requested dan berikan satu prompt Worker. Gunakan Issue, branch, dan PR yang sama; jangan memulai pekerjaan lain sebelum blocking findings selesai. Worker kembali in-progress, memperbaiki dan mengirim ready-for-review kembali.

### APPROVED dan merge

Syarat: semua acceptance criteria terpenuhi, tidak ada blocking findings, required checks sukses, tidak ada konflik, target main, HEAD terbaru persis sama dengan SHA yang direview, tidak ada rahasia dan izin perubahan database yang diperlukan telah diperoleh. Bila HEAD berubah, review perubahan/checks ulang sebelum merge.

Kedua model memakai satu akun GitHub: catat APPROVED/CHANGES REQUESTED melalui komentar PR, jangan mencoba formal approval PR sendiri. Ganti status menjadi approved. Reviewer boleh squash merge sesuai standing authorization dan aturan repository; jangan administrator bypass atau melewati required checks. Jika gerbang tidak terpenuhi, jangan merge.

### Setelah merge

1. Verifikasi PR benar-benar merged dan Issue tertutup karena selesai; set status merged pada Issue/PR.
2. Hapus feature branch remote yang selesai. Sinkronkan main lokal secara aman, hanya bila status lokal memungkinkan. Bersihkan branch lokal setelah memverifikasi seluruh pekerjaan terintegrasi (squash merge dapat memiliki SHA berbeda); jangan menghapus pekerjaan unik atau perubahan pengguna.
3. Perbarui progres roadmap/milestone berbasis bukti. Perubahan file melalui branch/PR, tidak push langsung main. Jangan menutup milestone hanya karena satu PR selesai.
4. Kembali sebagai Planner, siapkan satu Issue/linked branch berikutnya, dan handoff Worker.

## Status dan housekeeping

| Label | Dipakai pada Issue/PR ketika |
| --- | --- |
| planned | Rencana siap; sebelum PR ada, hanya Issue. |
| in-progress | Worker mulai/melanjutkan implementasi atau perbaikan. |
| ready-for-review | PR dan bukti validasi siap diperiksa. |
| changes-requested | Review menuntut perbaikan. |
| approved | Review pada SHA tertentu lulus semua gerbang. |
| merged | PR sudah merged dan Issue selesai. |
| deployed | Perubahan sudah termasuk deployment yang terverifikasi, dengan SHA dan bukti. |
| blocked | Ada hambatan nyata, alasan dan kebutuhan penyelesaian tercatat. |

Hapus label status sebelumnya saat mengganti status, pertahankan label non-status. Sinkronkan status Issue dan PR yang sudah ada. Setelah blocked teratasi, kembalikan ke tahap yang sesuai dengan alasan; bukan otomatis approved. Dokumentasi yang tidak perlu deployment boleh tetap merged. Label deployed tidak membuka kembali Issue yang selesai.

Issue ditutup sebagai completed setelah PR merged. Pembatalan, duplikat atau penggantian harus berdasarkan keputusan yang sah, disertai komentar/referensi dan alasan not planned bila tidak dikerjakan. Jangan tutup pekerjaan blocked/gagal. PR diterima harus merged; PR dibatalkan boleh closed tanpa merge. Pertahankan seluruh riwayat Issue/PR/review/commit.

Branch menggunakan `feature/ISSUE_NUMBER-short-description`, `refactor/ISSUE_NUMBER-short-description`, `fix/ISSUE_NUMBER-short-description`, atau `docs/ISSUE_NUMBER-short-description`. Contoh commit: `fitur: menambahkan modul project teknologi`, `refaktor: menyederhanakan navigasi CMS`, `dokumentasi: menyiapkan workflow multi-agent`. Jangan force push, reset --hard atau operasi yang kehilangan perubahan pengguna.

## Standar Issue dan PR

Issue wajib memiliki 14 bagian: Objective, Background, Scope, Out of Scope, Technical Implementation Plan, Affected Files, Database Impact, Security Requirements, Acceptance Criteria, Testing Requirements, Deployment Impact, Rollback Considerations, Worker Instructions, Definition of Done. Setiap bagian diisi; N/A wajib disertai alasan. Acceptance criteria harus spesifik, terukur dan dapat diuji. Pilih milestone sesuai scope, bukan otomatis M1.

PR wajib memuat referensi Issue/Closes, ringkasan perubahan, file utama, database, keamanan, hasil tes, build jika relevan, pemetaan acceptance criteria, kendala/keterbatasan, deployment, risiko/rollback. Tulis bukti nyata; tidak dijalankan berarti tidak dijalankan. Worker siap review belum berarti DoD final; DoD memerlukan review/checks/merge dan penutupan Issue sesuai kriterianya.

## Validasi per Issue

Pilih pemeriksaan sesuai perubahan: syntax, targeted tests, diff checking, validasi fungsional, code review, dan keamanan bagian berubah. Dokumentasi: parse YAML, cek schema Issue Forms/ID unik/required fields, tautan relatif, konsistensi aturan dan kebocoran data. Jangan menambah test yang sekadar menyalin isi dokumen. Periksa diff lengkap dan staged diff agar scope benar.

Perintah yang tersedia pada baseline: `git diff --check`, `git diff --cached --check`, `composer test`, dan `npm run build`. Perintah aplikasi hanya dijalankan bila relevan dan lingkungan uji aman; composer test juga menjalankan config:clear. Jangan menjalankan setup/migration terhadap staging sebagai bagian tes. Test menggunakan database terpisah, tidak database operasional.

## Rencana full CI/CD berbasis milestone

Bagian ini adalah spesifikasi untuk Issue M1 berikutnya; belum ada pipeline aktif pada baseline dokumentasi ini. Full CI/CD tidak dijalankan pada setiap Issue/merge. Pemicu utama workflow_dispatch pada GitHub-hosted runner untuk mengurangi beban lokal/VPS. Checks ringan pada PR boleh bila perlu/diwajibkan. Jangan auto-deploy setiap push main.

Jalankan full CI ketika milestone siap, beberapa Issue membentuk checkpoint fungsional utuh, pengujian integrasi diperlukan, pengguna meminta deployment atau ada perbaikan kritis. Tidak ada jumlah Issue yang kaku.

Full CI mencakup PHP syntax audit, Composer Audit, NPM Audit, migration testing pada database uji terpisah, Laravel automated tests, regression/integration testing, validasi komponen Vue dan Vite production build. Simpan bukti run dan SHA. Jika audit/checks gagal, jangan deploy; perbaiki dahulu tanpa melemahkan pemeriksaan.

### Batch deployment dan rollback

Aktivasi deployment pertama dan perubahan schema destruktif memerlukan persetujuan eksplisit. Persiapkan rencana konkret dan bukti CI sebelum meminta izin. Deployment berikutnya tetap mengikuti scope/otorisasi yang berlaku; jangan menyimpulkan izin perubahan infrastruktur dari izin rutin pengembangan.

1. Pilih SHA persis yang lulus full CI dan artifacts yang berasal dari SHA tersebut; jangan deploy main bergerak tanpa verifikasi.
2. Gunakan dedicated SSH deployment key melalui secrets dan verifikasi host fingerprint. Jangan StrictHostKeyChecking=no. Jangan menaruh key/target/fingerprint aktual di docs/log publik.
3. Backup database sebelum migration, verifikasi backup dapat dipakai, catat rencana pemulihan serta SHA/artifacts sebelumnya secara aman.
4. Update source ke SHA teruji; composer install berdasarkan lock, pasang build artifacts, jalankan migration yang sudah diaudit/diizinkan, publikasi aset Filament dan cache management.
5. Pertahankan .env, APP_KEY, credential, database, public/private storage. Jangan migrate:fresh staging, restart VPS, upgrade sistem/software global atau mengganggu website lain tanpa izin.
6. Jalankan health check sesuai fungsi yang dideploy, catat status/waktu/SHA/hasil tanpa rahasia, serta periksa Queue/Scheduler bila relevan.
7. Jika gagal, hentikan perluasan deployment, laporkan dan buat Issue perbaikan bila diperlukan. Pulihkan source/artifacts ke versi sebelumnya hanya setelah menilai kompatibilitas schema. Pemulihan database memakai backup terverifikasi dengan persetujuan tindakan destruktif; jangan rollback data secara buta. Ulangi health check dan catat hasil.

Tutup milestone hanya bila seluruh deliverables, full CI dan deployment yang diwajibkan berhasil. Status merged tidak sama dengan deployed. Untuk perubahan dokumentasi saja, rollback berupa koreksi/revert melalui PR, tanpa reset/force push atau rollback database.

## Otorisasi

Tindakan rutin sesuai peran sudah diizinkan: milestone/labels/Issue/linked branch, implementasi dalam scope, targeted tests, commit/push feature branch, PR/review, merge yang memenuhi syarat, close Issue, cleanup branch selesai dan Issue berikutnya. Tidak melewati sandbox/tool/repository approvals.

Izin eksplisit: penghapusan tabel/kolom/data, migration destruktif, perubahan credential, penghapusan pekerjaan belum terintegrasi, branch protection/aturan keamanan, aktivasi deployment pertama, infrastruktur VPS, layanan berbayar, posting sosial nyata, arsitektur utama, atau gangguan layanan lain. Gunakan migration baru, audit dependensi dan backup sebelum penghapusan schema; jangan edit migration yang sudah berjalan. Keamanan informasi publik mengikuti [AGENTS](../AGENTS.md).

## Prompt handoff

Ganti semua placeholder sebelum menyerahkan; pergantian model dilakukan pengguna.

### Planner ke Worker

```text
MODE: WORKER
Baca Issue <URL_ISSUE> lengkap melalui GitHub CLI dan AGENTS.md.
Verifikasi linked branch <BRANCH>, baseline <SHA>, status dan perubahan pengguna.
Kerjakan hanya scope/acceptance criteria Issue. Ganti label menjadi in-progress.
Jalankan validasi relevan, laporkan bukti/keterbatasan, stage file spesifik,
commit Bahasa Indonesia, push branch dan buat PR ke main dengan Closes #<NUMBER>.
Ganti status menjadi ready-for-review dan berikan handoff Reviewer.
Jangan merge/deploy atau mengubah kriteria sepihak.
```

### Worker ke Reviewer

```text
MODE: REVIEWER
Baca Issue <URL_ISSUE>, PR <URL_PR>, AGENTS.md, diff/source/tests/checks aktual
dan komentar sebelumnya. HEAD yang diajukan: <SHA>.
Validasi AC, scope, keamanan, kompatibilitas dan bukti tes; jangan hanya
mengandalkan ringkasan Worker. Catat severity dan hasil melalui komentar PR.
Jika gagal: changes-requested dan prompt perbaikan pada Issue/branch/PR sama.
Jika lolos: verifikasi HEAD/checks/target main/izin, lalu squash merge sesuai
otorisasi tanpa bypass. Verifikasi merged/Issue closed, cleanup aman,
perbarui progres melalui PR bila mengubah file, kembali Planner.
```

### Reviewer ke Worker untuk perbaikan

```text
MODE: WORKER
Perbaiki temuan pada PR <URL_PR> untuk Issue <URL_ISSUE>, branch <BRANCH>.
Baca komentar review <REFERENSI>; blocking findings: <DAFTAR>.
Ganti status in-progress, penuhi AC <AC>, jalankan ulang <TES>, dan laporkan
bukti. Pertahankan scope dan PR yang sama, tanpa force push/merge/deploy.
Kembalikan ready-for-review dan handoff Reviewer dengan SHA terbaru.
```
