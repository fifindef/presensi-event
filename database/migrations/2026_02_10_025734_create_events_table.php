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
        Schema::create('events', function (Blueprint $table) {
    $table->id('id_event');
    $table->string('nama_event');
    $table->date('tanggal');
    $table->time('jam');
    $table->string('lokasi');
    $table->unsignedBigInteger('id_kategori');
    $table->timestamps();

    $table->foreign('id_kategori')
          ->references('id_kategori')
          ->on('kategori_events')
          ->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
