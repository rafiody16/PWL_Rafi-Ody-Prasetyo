<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user'; // mendeklarasikan nama tabel
    protected $primaryKey = 'user_id'; // mendeklarasikan primary key

    protected $fillable = ['level_id', 'username', 'nama', 'password']; // mendaftarkan atribut (nama kolom) yang bisa kita isi ketika melakukan insert atau update ke database.

    protected $hidden = ['password']; // jangan ditampilkan saat select

    protected $casts = ['password' => 'hashed']; //  casting password agar otomatis di hash

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
