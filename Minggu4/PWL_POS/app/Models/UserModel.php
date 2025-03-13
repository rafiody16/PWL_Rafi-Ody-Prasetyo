<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user'; // mendeklarasikan nama tabel
    protected $primaryKey = 'user_id'; // mendeklarasikan primary key

    protected $fillable = ['level_id', 'username', 'nama', 'password']; // mendaftarkan atribut (nama kolom) yang bisa kita isi ketika melakukan insert atau update ke database.

    // protected $fillable = ['level_id', 'username', 'nama'];

    // public function level(): HasOne
    // {
    //     return $this->HasOne(LevelModel::class, 'level_id', 'level_id');
    // }

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
}
