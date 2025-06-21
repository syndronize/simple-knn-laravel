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
        Schema::create('followup', function (Blueprint $table) {
            $table->id();
            // tanggal follow-up
            $table->bigInteger('lead_id')
                ->unsigned()
                ->comment('ID lead yang di-follow-up');
            $table->date('tanggal_followup')
                ->comment('Tanggal follow-up dilakukan')
                ->default(now());
            // enum blasting, pitching, offering
            $table->enum('jenis_followup', ['blasting', 'pitching', 'offering'])
                ->default('blasting')
                ->comment('Jenis follow-up yang dilakukan');
            $table->timestamps();
            $table->foreign('lead_id')
                ->references('id')
                ->on('leads')
                ->onDelete('cascade')
                ->comment('Referensi ke tabel leads untuk ID lead yang di-follow-up');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followup');
    }
};
