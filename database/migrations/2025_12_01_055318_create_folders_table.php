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
            $table->string('name'); // Nama folder (e.g., UP Bendahara, 2024, Box 1)
            
            // Kolom krusial untuk struktur Parent-Child:
            // Ini akan menunjuk ke ID folder induknya.
            // Nullable() artinya boleh kosong, ini untuk folder Level 1 (root).
            $table->unsignedBigInteger('parent_id')->nullable(); 
            
            // foreignId adalah cara cepat Laravel untuk membuat kolom FK.
            // onDelete('cascade') artinya jika folder induk dihapus, anak-anaknya juga dihapus.
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('folders')
                  ->onDelete('cascade');
                  
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
