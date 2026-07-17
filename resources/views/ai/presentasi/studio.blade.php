@extends('layouts.app')
@section('title', $presentation->title.' — Studio Presentasi')

@push('styles')
<style>
.pres-shell { min-height: calc(100dvh - 5.5rem); }
.pres-stage {
    background: linear-gradient(145deg, #0b3d6e 0%, #12345b 45%, #0f172a 100%);
    border-radius: 1.25rem;
    min-height: 360px;
    aspect-ratio: 16 / 9;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 2rem 2.5rem;
    color: #fff;
    box-shadow: 0 16px 40px rgba(18, 52, 91, 0.25);
}
.pres-stage h2 { font-size: clamp(1.4rem, 3vw, 2.1rem); font-weight: 800; line-height: 1.25; }
.pres-stage .body { margin-top: 1rem; font-size: clamp(.95rem, 1.6vw, 1.15rem); line-height: 1.55; white-space: pre-wrap; color: #e2e8f0; }
.pres-fullscreen {
    position: fixed; inset: 0; z-index: 80;
    background: #0b1220;
    display: flex; flex-direction: column;
}
.pres-fullscreen .pres-stage {
    flex: 1; border-radius: 0; aspect-ratio: auto; min-height: 0;
    padding: 4rem 6vw;
}
</style>
@endpush

@section('content')
<div class="pres-shell space-y-4" x-data="presentationStudio()" @keydown.window="onKey($event)">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="min-w-0 flex items-center gap-3">
            <a href="{{ route('ai.teacher.presentasi.index') }}" class="grid place-items-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-primary">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-wider text-primary">Studio Presentasi</p>
                <h1 class="font-bold text-slate-800 dark:text-slate-100 truncate" x-text="form.title">{{ $presentation->title }}</h1>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-[11px] text-slate-400" x-show="savedAt" x-cloak>Tersimpan <span x-text="savedAt"></span></span>
            <button type="button" @click="save(false)" :disabled="saving" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-xs font-bold min-h-[40px]">
                <i data-lucide="save" class="w-3.5 h-3.5"></i> <span x-text="saving ? 'Menyimpan…' : 'Simpan'"></span>
            </button>
            <a href="{{ route('ai.teacher.presentasi.pdf', $presentation) }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2 text-xs font-bold min-h-[40px]">
                <i data-lucide="file-down" class="w-3.5 h-3.5"></i> PDF
            </a>
            <button type="button" @click="startPresent()" class="inline-flex items-center gap-2 rounded-xl bg-primary text-white px-4 py-2 text-xs font-bold min-h-[40px]">
                <i data-lucide="play" class="w-3.5 h-3.5"></i> Presentasikan
            </button>
        </div>
    </div>

    <div class="grid lg:grid-cols-[320px_minmax(0,1fr)] gap-4">
        <aside class="card p-4 space-y-3">
            <div>
                <label class="form-label">Judul</label>
                <input type="text" x-model="form.title" @change="queueSave()" class="form-input text-sm">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="form-label">Mapel</label>
                    <input type="text" x-model="form.subject" @change="queueSave()" class="form-input text-sm">
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select x-model="form.status" @change="queueSave()" class="form-select text-sm">
                        @foreach($statuses as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="form-label">Outline (satu nomor = satu slide)</label>
                <textarea x-model="form.outline" @input="rebuildFromOutline(); queueSave()" rows="10" class="form-input font-mono text-xs leading-relaxed"
                          placeholder="1. Judul&#10;Poin pendukung&#10;2. Materi..."></textarea>
            </div>
            <div>
                <label class="form-label">Catatan</label>
                <textarea x-model="form.notes" @input="queueSave()" rows="2" class="form-input text-sm"></textarea>
            </div>
            <p class="text-[11px] text-emerald-600 font-semibold" x-show="message" x-cloak x-text="message"></p>
            <p class="text-[11px] text-rose-500 font-semibold" x-show="error" x-cloak x-text="error"></p>
            <form method="POST" action="{{ route('ai.teacher.presentasi.destroy', $presentation) }}" onsubmit="return confirm('Hapus presentasi ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs font-bold text-rose-600 hover:underline">Hapus</button>
            </form>
        </aside>

        <section class="space-y-3">
            <div class="flex items-center justify-between gap-2">
                <p class="text-xs font-bold text-slate-500">
                    Slide <span x-text="index + 1"></span> / <span x-text="Math.max(slides.length, 1)"></span>
                </p>
                <div class="flex gap-2">
                    <button type="button" @click="prev()" class="rounded-lg border border-slate-200 dark:border-slate-600 px-3 py-1.5 text-xs font-bold">←</button>
                    <button type="button" @click="next()" class="rounded-lg border border-slate-200 dark:border-slate-600 px-3 py-1.5 text-xs font-bold">→</button>
                </div>
            </div>

            <div class="pres-stage">
                <p class="text-xs font-bold uppercase tracking-wider text-sky-300/90 mb-2" x-text="form.subject || 'Presentasi'"></p>
                <h2 x-text="current.title || 'Belum ada slide'"></h2>
                <div class="body" x-text="current.body || 'Tambahkan outline bernomor di panel kiri.'"></div>
            </div>

            <div class="flex flex-wrap gap-2">
                <template x-for="(s, i) in slides" :key="i">
                    <button type="button" @click="index = i"
                            class="rounded-lg px-2.5 py-1.5 text-[11px] font-bold border max-w-[9rem] truncate"
                            :class="i === index ? 'bg-primary text-white border-primary' : 'border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300'"
                            x-text="(i + 1) + '. ' + (s.title || 'Slide')"></button>
                </template>
            </div>
        </section>
    </div>

    {{-- Mode presentasi penuh --}}
    <div x-show="presenting" x-cloak class="pres-fullscreen" @click.self="stopPresent()">
        <div class="flex items-center justify-between px-4 py-3 text-white/80 text-xs">
            <span x-text="form.title"></span>
            <div class="flex items-center gap-3">
                <span x-text="(index + 1) + ' / ' + Math.max(slides.length, 1)"></span>
                <button type="button" @click="stopPresent()" class="rounded-lg bg-white/10 px-3 py-1.5 font-bold hover:bg-white/20">Esc · Tutup</button>
            </div>
        </div>
        <div class="pres-stage" @click="next()">
            <p class="text-sm font-bold uppercase tracking-wider text-sky-300/90 mb-3" x-text="form.subject || ''"></p>
            <h2 x-text="current.title"></h2>
            <div class="body" x-text="current.body"></div>
        </div>
        <div class="flex justify-center gap-3 py-4">
            <button type="button" @click="prev()" class="rounded-xl bg-white/10 text-white px-4 py-2 text-sm font-bold">Sebelumnya</button>
            <button type="button" @click="next()" class="rounded-xl bg-sky-500 text-white px-4 py-2 text-sm font-bold">Berikutnya</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function presentationStudio() {
    return {
        form: {
            title: @js($presentation->title),
            subject: @js($presentation->subject ?? ''),
            status: @js($presentation->status),
            outline: @js($presentation->outline ?? ''),
            notes: @js($presentation->notes ?? ''),
        },
        slides: @js($slides),
        index: 0,
        presenting: false,
        saving: false,
        savedAt: '',
        message: '',
        error: '',
        saveTimer: null,
        get current() {
            return this.slides[this.index] || { title: '', body: '' };
        },
        rebuildFromOutline() {
            const text = (this.form.outline || '').trim();
            if (!text) { this.slides = []; this.index = 0; return; }
            const lines = text.split(/\r\n|\n|\r/);
            const out = [];
            let cur = null;
            const flush = () => {
                if (!cur) return;
                cur.body = (cur.body || '').trim();
                if (cur.title || cur.body) out.push(cur);
                cur = null;
            };
            for (const line of lines) {
                const t = line.trim();
                if (!t) { if (cur) cur.body += '\n'; continue; }
                const m = t.match(/^(?:slide\s*)?(\d+)\s*[\.\)\:\-]\s*(.+)$/i) || t.match(/^#{1,3}\s+(.+)$/);
                if (m) {
                    flush();
                    cur = { title: (m[2] || m[1] || '').trim(), body: '' };
                    continue;
                }
                if (!cur) cur = { title: t, body: '' };
                else cur.body += (cur.body && !cur.body.endsWith('\n') ? '\n' : '') + t;
            }
            flush();
            this.slides = out.length ? out : [{ title: 'Presentasi', body: text }];
            if (this.index >= this.slides.length) this.index = Math.max(0, this.slides.length - 1);
        },
        prev() { if (this.index > 0) this.index--; },
        next() { if (this.index < this.slides.length - 1) this.index++; },
        startPresent() { this.presenting = true; },
        stopPresent() { this.presenting = false; },
        onKey(e) {
            if (!this.presenting && e.target && ['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) return;
            if (e.key === 'Escape') this.stopPresent();
            if (e.key === 'ArrowRight' || e.key === ' ') { e.preventDefault(); this.next(); }
            if (e.key === 'ArrowLeft') { e.preventDefault(); this.prev(); }
        },
        queueSave() {
            clearTimeout(this.saveTimer);
            this.saveTimer = setTimeout(() => this.save(true), 800);
        },
        async save(silent = false) {
            if (this.saving) return;
            this.saving = true;
            this.error = '';
            try {
                const r = await fetch(@js(route('ai.teacher.presentasi.update', $presentation)), {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ ...this.form, slides: this.slides }),
                });
                const d = await r.json().catch(() => ({}));
                if (!r.ok) {
                    this.error = d.message || 'Gagal menyimpan.';
                    return;
                }
                if (d.slides) this.slides = d.slides;
                this.savedAt = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                if (!silent) {
                    this.message = d.message || 'Tersimpan.';
                    setTimeout(() => { this.message = ''; }, 2500);
                }
            } catch (_) {
                this.error = 'Gagal menyimpan.';
            } finally {
                this.saving = false;
            }
        },
    };
}
</script>
@endpush
