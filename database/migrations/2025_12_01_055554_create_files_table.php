<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            // Kunci asing: File ini milik folder ID berapa
            $table->foreignId('folder_id')->constrained()->onDelete('cascade'); 
            $table->string('name'); // Nama tampilan file (Contoh: Laporan Keuangan Juni)
            $table->string('file_path'); // Nama unik file di server (storage/app/public/files/...)
            $table->string('file_mime_type'); // Tipe file (application/pdf, image/jpeg, dll.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
