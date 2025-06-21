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
        Schema::create('penagihan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan')
                ->comment('Nama perusahaan yang ditagih');
            $table->bigInteger('customer_id')
                ->comment('ID customer berlangganan yang digunakan')
                ->unsigned();
            // jumlah tagihan
            $table->decimal('jumlah_tagihan', 15, 2)
                ->comment('Jumlah tagihan yang harus dibayar');
            // tanggal\
            $table->date('tanggal_tagihan');
            //skema pembayaran pacabayar dan prabayar default none
            $table->enum('skema_pembayaran', ['none', 'prabayar', 'pacabayar'])
                ->default('none')
                ->comment('Skema pembayaran tagihan');
            // file invoice
            $table->string('invoice')
                ->nullable()
                ->comment('File invoice tagihan, jika ada');

            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
            $table->index(['nama_perusahaan', 'tanggal_tagihan'], 'idx_penagihan_perusahaan_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penagihan');
    }
};
