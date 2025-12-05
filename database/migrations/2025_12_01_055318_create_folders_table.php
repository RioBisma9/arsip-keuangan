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
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            
            // KOLOM PENTING 1: Kepemilikan User
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            $table->string('name');
            
            // KOLOM PENTING 2: Hubungan Parent (bisa null untuk folder utama)
            $table->foreignId('parent_id')->nullable()->constrained('folders')->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};