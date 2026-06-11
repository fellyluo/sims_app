<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruKetersediaan extends Model
{
    use HasFactory;

    protected $fillable = ['id_guru', 'hari', 'jam_ke'];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'uuid');
    }
}
