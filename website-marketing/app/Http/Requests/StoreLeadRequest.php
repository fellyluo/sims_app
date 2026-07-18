<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:120'],
            'sekolah' => ['required', 'string', 'max:180'],
            'jabatan' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'no_hp' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\-\s]+$/'],
            'perkiraan_siswa' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'tier_diminati' => ['nullable', Rule::in(['dasar', 'pro', 'enterprise'])],
            'pesan' => ['nullable', 'string', 'max:3000'],
            'sumber' => ['required', Rule::in(['landing', 'kontak'])],
            'website' => ['nullable', 'string', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'sekolah.required' => 'Nama sekolah wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email belum valid.',
            'no_hp.regex' => 'Format nomor WhatsApp belum valid.',
            'perkiraan_siswa.integer' => 'Perkiraan siswa harus berupa angka.',
            'tier_diminati.in' => 'Paket yang dipilih tidak valid.',
            'website.max' => 'Permintaan tidak dapat diproses.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => is_string($this->email) ? mb_strtolower(trim($this->email)) : $this->email,
            'no_hp' => is_string($this->no_hp) ? trim($this->no_hp) : $this->no_hp,
        ]);
    }
}
