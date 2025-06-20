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
        Schema::create('log_insiden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insiden_id')->constrained('insiden')->onDelete('cascade');
            $table->foreignId('petugas_insiden_id')->constrained('petugas_insiden')->onDelete('cascade');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('suhu', 8, 2)->nullable();
            $table->float('kualitas_udara')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_insidens');
    }
};
