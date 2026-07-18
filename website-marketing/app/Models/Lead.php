<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'nama',
        'sekolah',
        'jabatan',
        'email',
        'no_hp',
        'perkiraan_siswa',
        'tier_diminati',
        'pesan',
        'sumber',
    ];

    protected function casts(): array
    {
        return [
            'perkiraan_siswa' => 'integer',
        ];
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
