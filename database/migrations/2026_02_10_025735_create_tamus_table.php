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
        Schema::create('tamus', function (Blueprint $table) {
    $table->id('id_tamu');
    $table->string('nama_tamu');
    $table->string('nomor_hp');
    $table->unsignedBigInteger('id_jenis_tamu');
    $table->timestamps();

    $table->foreign('id_jenis_tamu')
          ->references('id_jenis_tamu')
          ->on('jenis_tamus')
          ->onDelete('cascade');
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamus');
    }
};
