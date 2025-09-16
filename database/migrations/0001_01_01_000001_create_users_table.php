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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('organization_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            // Menambahkan foreign key constraint.
            // Kolom 'organization_id' merujuk ke 'bkd_organization_id' di tabel 'organization'.
            // Menggunakan onDelete('set null') agar data user tidak terhapus jika data organisasinya dihapus.
            $table->foreign('organization_id')
                  ->references('organization_id')
                  ->on('organization')
                  ->onDelete('set null');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
