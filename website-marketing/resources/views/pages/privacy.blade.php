@extends('layouts.marketing')

@section('title', 'Privasi — SIMS')
@section('description', 'Ringkasan cara situs pemasaran SIMS mengelola data permintaan demo dan kontak.')
@section('og_title', 'Privasi — SIMS')
@section('og_description', 'Informasi singkat tentang data yang dikumpulkan saat Anda meminta demo SIMS.')

@section('content')
<section class="py-20 sm:py-24">
    <div class="shell max-w-3xl">
        <span class="eyebrow">Privasi</span>
        <h1 class="section-title mt-5">Bagaimana kami memperlakukan data Anda.</h1>
        <p class="section-copy">Halaman ini menjelaskan praktik situs pemasaran SIMS. Kebijakan lengkap aplikasi produksi dapat berbeda dan akan disampaikan saat onboarding.</p>

        <div class="mt-12 space-y-8 text-sm leading-7 text-slate-600 dark:text-slate-400">
            <article>
                <h2 class="text-lg font-bold text-ink dark:text-white">Data yang kami kumpulkan</h2>
                <p class="mt-3">Saat Anda mengisi formulir demo, kami menyimpan nama, sekolah, jabatan (opsional), email, nomor WhatsApp (opsional), perkiraan jumlah siswa, paket yang diminati, pesan, serta sumber halaman formulir.</p>
            </article>
            <article>
                <h2 class="text-lg font-bold text-ink dark:text-white">Tujuan penggunaan</h2>
                <p class="mt-3">Data dipakai untuk menghubungi Anda terkait permintaan demo, penawaran langganan, dan tindak lanjut penjualan SIMS. Kami tidak menjual data lead kepada pihak ketiga.</p>
            </article>
            <article>
                <h2 class="text-lg font-bold text-ink dark:text-white">Penyimpanan & akses</h2>
                <p class="mt-3">Lead disimpan di database situs pemasaran dan dikirim ke email notifikasi internal yang dikonfigurasi oleh pengelola. Akses dibatasi pada tim yang mengelola penjualan dan dukungan.</p>
            </article>
            <article>
                <h2 class="text-lg font-bold text-ink dark:text-white">Kontak</h2>
                <p class="mt-3">
                    Untuk permintaan penghapusan atau koreksi data lead, hubungi
                    <a href="mailto:{{ config('marketing.contact.email') }}" class="font-semibold text-tide-dark hover:underline dark:text-teal-300">{{ config('marketing.contact.email') }}</a>
                    atau WhatsApp
                    <a href="https://wa.me/{{ \App\Support\Marketing::whatsappDigits() }}" class="font-semibold text-tide-dark hover:underline dark:text-teal-300">{{ \App\Support\Marketing::whatsappDisplay() }}</a>.
                </p>
            </article>
        </div>

        <div class="mt-12">
            <a href="{{ route('contact') }}" class="btn-primary">Kembali ke kontak</a>
        </div>
    </div>
</section>
@endsection
