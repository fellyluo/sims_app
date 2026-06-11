<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';
    protected $fillable = [
        'id_kelas',
        'id_pelajaran',
        'id_guru',
        'hari',
        'jam_ke'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'uuid');
    }

    public function pelajaran()
    {
        return $this->belongsTo(Pelajaran::class, 'id_pelajaran', 'uuid');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'uuid');
    }
}
