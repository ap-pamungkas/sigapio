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
        Schema::create('petugas_insiden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insiden_id')->constrained('insiden')->onDelete('cascade');
            $table->foreignId('petugas_id')->constrained('petugas')->onDelete('cascade');
            $table->foreignId('perangkat_id')->constrained('perangkat')->onDelete('cascade');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas_insidens');
    }
};
