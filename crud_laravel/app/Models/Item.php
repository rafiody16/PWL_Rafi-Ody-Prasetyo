<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    //$fillabel digunakan untuk melindungi kolom mana saja yang boleh diisi.
    protected $fillabel = [
        'name',
        'description',
        'stock'
    ];
}
