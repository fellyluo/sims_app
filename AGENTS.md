# Agent Instructions — SIMS

Baca `PRD.md` dan seluruh isi folder `features/` sebelum mengerjakan task apa pun terkait Arena Belajar / kuis interaktif. Urutan pengerjaan ikuti nomor fase di `PRD.md` dan task di `features/01-*.md` → `02` → `03`. Prompt implementasi Fase 1 ada di `PROMPT-ARENA-BELAJAR.md`.

## Skills

Skill FL terpasang di `.claude/skills/`. Baca `SKILL.md` skill terkait sebelum mengerjakan task yang cocok:

- `.claude/skills/skill-fl/SKILL.md` — skill inti FL: identitas kerja, konvensi teknis kanonik (rujukan tunggal), router ke skill lain. Rujuk untuk setiap build software FL.
- `.claude/skills/prd-generator/SKILL.md` — generate PRD format Struktur-PRD-Task (`PRD.md` + folder `features/`) saat diminta buat PRD/breakdown fitur.
- `.claude/skills/senior-prompt-engineering/SKILL.md` — susun satu blok prompt siap-tempel untuk agent coding (bukan dokumen perencanaan).
- `.claude/skills/laravel-security-audit/SKILL.md` — checklist audit keamanan Laravel multi-tenant saat diminta security patch/harden/review.
- `.claude/skills/android-native-offline-first/SKILL.md` — build/review app Android native (Kotlin/Compose/Room) offline-first yang sync ke backend Laravel.
- `.claude/skills/android-webview-app-builder/SKILL.md` — build/review app Android WebView wrapper / PWA-to-APK.
