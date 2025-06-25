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
        Schema::create('petugas', function (Blueprint $table) {
           $table->id();
            $table->string( 'nama');
            $table->text('alamat')->nullable();
            $table->enum('status', allowed: ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->enum('jenis_kelamin', allowed: ['Laki-laki', 'Perempuan']);
            $table->date('tgl_lahir')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('foto')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
