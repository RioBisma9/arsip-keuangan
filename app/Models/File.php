<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    // Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'name',
        'folder_id',
        'file_path',
        'file_mime_type',
    ];

    // Relasi: File ini milik folder mana
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}