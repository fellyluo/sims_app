<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Laragear\WebAuthn\Models\WebAuthnCredential;

return new class extends Migration
{
    /**
     * Tabel `webauthn_credentials` lama dibuat dengan skema Laragear versi lama
     * (kolom: name, type, algorithms, attachment, device_type, backed_up, disabled_at).
     * Package yang terpasang sekarang memakai skema berbeda (user_id, alias, rp_id,
     * origin, attestation_format, certificates) sehingga pendaftaran biometrik gagal:
     * "table webauthn_credentials has no column named user_id".
     *
     * Belum ada credential yang tersimpan (semua insert gagal), jadi tabel aman
     * di-drop lalu dibuat ulang menggunakan builder resmi package agar selalu
     * sinkron dengan versi Laragear yang aktif.
     *
     * morphUuid dipaksa eksplisit karena model `authenticatable` (User) di app ini
     * pakai UUID string sebagai primary key, bukan integer auto-increment bawaan
     * Laravel — tanpa ini kolom `authenticatable_id` ke-generate sebagai integer
     * dan tidak akan pernah cocok menyimpan UUID user. Nama index juga dipendekkan
     * manual karena default-nya (`webauthn_credentials_authenticatable_type_..._index`)
     * melebihi batas 64 karakter identifier MySQL (baru ketahuan di MySQL produksi,
     * SQLite lokal tidak menegakkan batas ini).
     */
    public function up(): void
    {
        Schema::dropIfExists('webauthn_credentials');

        WebAuthnCredential::migration()->morph('uuid', 'webauthn_cred_auth_index')->up();
    }

    public function down(): void
    {
        Schema::dropIfExists('webauthn_credentials');
    }
};
