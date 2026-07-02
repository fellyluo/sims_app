<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poin extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'poin';
    protected $fillable = ['tanggal', 'id_siswa', 'id_aturan'];

    protected $casts = ['tanggal' => 'date:Y-m-d'];

    public function aturan()
    {
        return $this->belongsTo(Aturan::class, 'id_aturan', 'uuid');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'uuid');
    }
}
