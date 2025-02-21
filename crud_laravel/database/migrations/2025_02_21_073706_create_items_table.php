<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // Function up() digunakan untuk mengubah atau membuat tabel dalam database saat migration dijalankan.
    public function up(): void
    {
        //Schema::create digunakan untuk membuat tabel baru pada database.
        Schema::create('items', function (Blueprint $table) {
            // Dibawah ini merupakan kolom-kolom dan juga tipe data dari kolom yang akan dibuat pada tabel database.
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

    //Function down() digunakan untuk apabila tabel terjadi perubahan.
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
