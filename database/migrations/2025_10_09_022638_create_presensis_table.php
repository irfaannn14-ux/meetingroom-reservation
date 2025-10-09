<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nama', 255);
            $table->enum('jabatan', ['OPD','Lainnya']);
            $table->string('organisasi', 100);
            $table->string('ttd_path')->nullable();
            $table->timestamps();

            // sesuaikan nama FK kalau ada
            // $table->foreign('pengajuan_id')->references('id')->on('pengajuans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
