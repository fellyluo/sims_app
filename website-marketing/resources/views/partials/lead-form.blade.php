<form method="POST" action="{{ route('contact.store') }}" class="grid gap-5" novalidate>
    @csrf
    <input type="hidden" name="sumber" value="{{ $source }}">
    <div class="absolute -left-[9999px]" aria-hidden="true">
        <label for="website-{{ $source }}">Website</label>
        <input id="website-{{ $source }}" type="text" name="website" tabindex="-1" autocomplete="off">
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="nama-{{ $source }}" class="form-label">Nama lengkap <span class="text-rose-600">*</span></label>
            <input id="nama-{{ $source }}" name="nama" value="{{ old('nama') }}" required autocomplete="name" class="form-input" placeholder="Nama Anda">
            @error('nama') <p class="mt-2 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="sekolah-{{ $source }}" class="form-label">Nama sekolah <span class="text-rose-600">*</span></label>
            <input id="sekolah-{{ $source }}" name="sekolah" value="{{ old('sekolah') }}" required autocomplete="organization" class="form-input" placeholder="Nama sekolah / yayasan">
            @error('sekolah') <p class="mt-2 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="email-{{ $source }}" class="form-label">Email <span class="text-rose-600">*</span></label>
            <input id="email-{{ $source }}" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="form-input" placeholder="nama@sekolah.sch.id">
            @error('email') <p class="mt-2 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="no-hp-{{ $source }}" class="form-label">Nomor WhatsApp</label>
            <input id="no-hp-{{ $source }}" name="no_hp" value="{{ old('no_hp') }}" autocomplete="tel" class="form-input" placeholder="08xxxxxxxxxx">
            @error('no_hp') <p class="mt-2 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="jabatan-{{ $source }}" class="form-label">Jabatan</label>
            <input id="jabatan-{{ $source }}" name="jabatan" value="{{ old('jabatan') }}" class="form-input" placeholder="Kepala sekolah / Operator">
        </div>
        <div>
            <label for="tier-{{ $source }}" class="form-label">Paket yang diminati</label>
            <select id="tier-{{ $source }}" name="tier_diminati" class="form-input">
                <option value="">Belum menentukan</option>
                <option value="dasar" @selected(old('tier_diminati', request('tier')) === 'dasar')>Dasar</option>
                <option value="pro" @selected(old('tier_diminati', request('tier')) === 'pro')>Pro</option>
                <option value="enterprise" @selected(old('tier_diminati', request('tier')) === 'enterprise')>Enterprise</option>
            </select>
        </div>
    </div>

    @if ($source === 'kontak')
        <div>
            <label for="perkiraan-siswa" class="form-label">Perkiraan jumlah siswa</label>
            <input id="perkiraan-siswa" type="number" name="perkiraan_siswa" value="{{ old('perkiraan_siswa') }}" min="1" max="100000" class="form-input" placeholder="Contoh: 500">
            @error('perkiraan_siswa') <p class="mt-2 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
    @endif

    <div>
        <label for="pesan-{{ $source }}" class="form-label">Apa yang ingin dibahas?</label>
        <textarea id="pesan-{{ $source }}" name="pesan" rows="4" class="form-input resize-y" placeholder="Ceritakan kebutuhan sekolah Anda">{{ old('pesan') }}</textarea>
    </div>

    <button type="submit" class="btn-primary w-full sm:w-fit">
        Kirim permintaan demo
        <i data-lucide="arrow-right" class="size-4" aria-hidden="true"></i>
    </button>
    <p class="text-xs leading-5 text-slate-500">Dengan mengirim form ini, Anda menyetujui tim SIMS menghubungi Anda terkait demo produk.</p>
</form>
