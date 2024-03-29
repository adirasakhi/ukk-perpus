<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    use HasFactory;
    public $table = 'kategori_buku';

    public $fillable = [
        'nama_kategori'
    ];
    public function buku()
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}
