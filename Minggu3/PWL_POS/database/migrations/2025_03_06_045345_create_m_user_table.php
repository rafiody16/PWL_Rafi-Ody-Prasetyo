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
        Schema::create('m_user', function (Blueprint $table) {
            $table->id('user_id');
            $table->unsignedBigInteger('level_id')->index(); // Index() untuk foreign key
            $table->string('username', 20)->unique(); // Unique() agar tidak ada data yang sama
            $table->string('nama', 100);
            $table->string('password');
            $table->timestamps();

            // Digunakan untuk mendefinisikan foreign key sekaligus membuat relasi pada 2 tabel
            $table->foreign('level_id')->references('level_id')->on('m_level')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_user');
    }
};
