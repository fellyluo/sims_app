# Progress — Arena Belajar

## Fase 1: Bank Soal & Kuis Async — SELESAI
- [x] UI + navigasi tab Ruang Kelas
- [x] Migration `game_*` (6 tabel)
- [x] Models + GameAnswerGrader + GameQuizImporter
- [x] GameQuizController + GameAttemptController + routes
- [x] GameQuizPolicy
- [x] Transfer nilai + Audit::log
- [x] Seeder + Feature test (5 passed)

## Fase 2: Live Session & Leaderboard — SELESAI
- [x] `game_live_sessions` + model
- [x] GameLiveController (start/advance/end/state/leaderboard/answer)
- [x] Match Up + Short Answer (builder + grading fuzzy/proporsional)
- [x] FCM `ArenaLiveStartedNotification` (best-effort)
- [x] UI live host/siswa + polling 3d
- [x] Feature test GameLiveTest (4 passed)

## Fase 3: Template Interaktif & Mode Tim — SELESAI
- [x] Template switcher (quiz/match/flashcard/crossword/unjumble)
- [x] Mode Tim (`game_teams` + members) + podium agregat
- [x] PDF worksheet DomPDF (+ kunci guru-only)
- [x] Offline queue localStorage + `syncOffline` endpoint
- [x] Feature test GameTemplateTest (4 passed)

**Total tes Arena: 13 passed (50 assertions)**

## Fase 4: Jagat Misi (migrasi dari JagatMISI) — SELESAI (inti)
- [x] Migration `missions`, `mission_steps`, `mission_attempts`, `mission_attempt_responses`, `mission_badges`, `mission_student_badges`, `mission_collection_items`, `mission_activity_logs`
- [x] Models + `MissionNalarScoringService` + `MissionProgressionService`
- [x] Controllers: `MissionNalarController`, `MissionProgressController`
- [x] Routes `/jagat-misi/*` + API JSON
- [x] Blade views: katalog, player nalar, progres/leaderboard
- [x] Assets CSS/JS dari prototype JagatMISI → `public/jagat-misi/`
- [x] `JagatMisiSeeder` + Feature test (6 passed)

**Total tes Jagat Misi: 6 passed**

### Belum dimigrasi
- [ ] Admin dashboard khusus JagatMISI (SIMS sudah punya admin sendiri)

## Fase 5–7 Jagat Misi — SELESAI
- [x] Fase 2: Mission Player recall/quiz + matching + avatar config
- [x] Fase 4: Debrief refleksi + gate server + panel guru
- [x] Fase 5: Analytics concept_mastery + laporan PDF (print)
- [x] Fase 7: Mission Builder CRUD + bank item + publish
- [x] Feature test Jagat Misi: 14 passed (total dengan fase 3 & 6)

## Fase 8: Integrasi Ruang Kelas — SELESAI
- [x] Migration `mission_assignments` + kolom `assignment_id` di `mission_attempts`
- [x] Model `MissionAssignment` + relasi di `Mission` / `MissionAttempt`
- [x] `MissionClassroomController` (index, assign, show, play, results, transferGrades)
- [x] Routes `classroom.jagat.*` di group Ruang Kelas
- [x] Tab Jagat Misi di `classroom/show.blade.php`
- [x] Views `classroom/jagat-misi/*`
- [x] `MissionPolicy` scoped ke classroom member (`playInClassroom`, `viewInClassroom`)
- [x] Simpan `assignment_id` saat submit player/nalar dari konteks kelas
- [x] Transfer nilai ke `NilaiFormatif` / `NilaiSumatif` (pola Arena Belajar)
- [x] Feature test `MissionClassroomTest` (7 tests)

## Merge branding: Jagat Misi → Arena Belajar — SELESAI
- [x] Satu tab Ruang Kelas: Arena Belajar (tab Jagat dihapus; `?tab=jagat` → arena)
- [x] Hub `arena-belajar/index` menampilkan section Kuis + Misi
- [x] Modul toggle tunggal `arena_belajar` (key `jagat_misi` dihapus dari registry)
- [x] Route misi digate `modul:arena_belajar`; path internal `/jagat-misi/*` tetap
- [x] Rebrand label user-facing; `classroom.jagat.index` redirect ke hub Arena
- [x] Panduan + tes brand diperbarui
