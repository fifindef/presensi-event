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
        Schema::create('presensis', function (Blueprint $table) {
    $table->id('id_presensi');
    $table->unsignedBigInteger('id_event');
    $table->unsignedBigInteger('id_tamu');
    $table->string('kode_unik')->unique();
    $table->string('qr_code')->nullable();
    $table->boolean('status_hadir')->default(0);
    $table->timestamp('waktu_hadir')->nullable();
    $table->timestamps();

    $table->foreign('id_event')
          ->references('id_event')
          ->on('events')
          ->onDelete('cascade');

    $table->foreign('id_tamu')
          ->references('id_tamu')
          ->on('tamus')
          ->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
