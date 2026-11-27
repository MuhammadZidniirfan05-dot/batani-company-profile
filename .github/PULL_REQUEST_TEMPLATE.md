## Referensi Issue

Closes #ISSUE_NUMBER

<!-- Ganti dengan nomor aktual. Satu Issue/branch/PR utama, target main. Jangan memasukkan rahasia. -->

## Ringkasan perubahan

<!-- Jelaskan masalah, perilaku sebelum/sesudah dan scope perubahan. -->

## File utama terdampak

<!-- Daftar file dengan tautan relatif dan tujuan perubahan. -->

## Perubahan database

<!-- Migration/data/FK, kompatibilitas, backup dan referensi izin destruktif; atau N/A dengan alasan. -->

## Dampak keamanan

<!-- Validasi, policies/RBAC, sanitasi, upload/storage/secret handling dan pemeriksaan kebocoran data. -->

## Hasil pengujian dan build

| Pemeriksaan/perintah | Hasil aktual | Bukti atau alasan tidak dijalankan |
| --- | --- | --- |
| Syntax/targeted tests | Isi hasil | Isi bukti |
| Validasi fungsional dan keamanan | Isi hasil | Isi bukti |
| Git diff checking | Isi hasil | Isi bukti |
| Build jika relevan | Isi hasil atau N/A | Isi alasan |

<!-- Dokumentasi: YAML/Issue Forms, tautan, konsistensi dan keamanan isi. Jangan mengklaim tes yang belum dijalankan. -->

## Acceptance criteria

| AC dari Issue | Perubahan/file yang memenuhi | Bukti validasi |
| --- | --- | --- |
| AC1 | Isi pemetaan | Isi bukti |

## Kendala dan keterbatasan

<!-- Catat kegagalan, asumsi, pengujian yang tidak dilakukan dan pekerjaan tersisa; jangan sembunyikan blocker. -->

## Catatan deployment

<!-- Checkpoint milestone/SHA full CI/artifact/approval/health check yang relevan, atau N/A beralasan. Tidak auto-deploy tiap merge. -->

## Risiko dan rollback

<!-- Risiko regresi, langkah pemulihan, kompatibilitas database, backup/approval yang diperlukan. -->

## Handoff Reviewer

<!-- Cantumkan Issue/branch/HEAD SHA, pemeriksaan yang perlu direview, dan prompt Reviewer. Pergantian model manual. -->

- [ ] Scope dan seluruh AC dipetakan; tidak ada perubahan pengguna yang tertimpa.
- [ ] Bukti tes/build atau N/A beralasan dicatat; tidak ada rahasia/detail operasional sensitif.
- [ ] Target main, Closes nomor aktual, dan status ready-for-review pada Issue/PR.
- [ ] Worker berhenti sebelum merge/deploy; Reviewer memeriksa HEAD/checks/izin aktual tanpa bypass.
