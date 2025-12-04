<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    // PASTIKAN 'parent_id' ada di sini. Tanpa ini, Laravel TIDAK AKAN bisa 
    // menyimpan parent_id NULL saat Anda membuat folder.
    protected $fillable = [
        'name', 
        'parent_id', 
        // Tambahkan 'user_id' jika Anda menggunakan autentikasi
        // 'user_id', 
    ];

    // Relasi Induk (Digunakan untuk Breadcrumb)
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    // Relasi Anak/Sub-Folder (Digunakan untuk listing dan withCount)
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // Jika Anda punya relasi dengan user
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}