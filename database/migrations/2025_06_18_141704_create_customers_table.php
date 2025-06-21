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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('contract_no')->unique()
                ->comment('Nomor kontrak unik untuk setiap pelanggan');
            $table->string('perusahaan')
                ->comment('Nama perusahaan pelanggan');
            $table->bigInteger('customer_pic')->unsigned()
                ->comment('ID pengguna yang menjadi PIC pelanggan');
            $table->bigInteger('product_id')->unsigned();
            $table->string('notelp');
            $table->string('alamat');
            $table->bigInteger('industry_type')->unsigned()
                ->comment('ID jenis industri pelanggan');
            $table->enum('skema_berlangganan', ['none', 'bulanan', 'triwulan', 'semester', 'tahunan'])
                ->default('none')
                ->comment('Jenis skema berlangganan pelanggan');
            $table->date('tanggal_mulai')
                ->comment('Tanggal mulai kontrak pelanggan');
            $table->date('tanggal_akhir')
                ->comment('Tanggal akhir kontrak pelanggan');
            $table->enum('status', ['active', 'inactive'])
                ->default('inactive')
                ->comment('Status aktif/inaktif pelanggan');
            $table->bigInteger('marketing_pic')->unsigned()
                ->comment('Nama PIC marketing yang menangani pelanggan ini');
            // tipe data untuk dokumen bisa 1 hingga 3 dokumen untuk satu data
            $table->text('dokumen')->nullable()
                ->comment('Dokumen terkait pelanggan, bisa berupa file atau link');

            $table->timestamps();
            $table->foreign('customer_pic')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('industry_type')->references('id')->on('industry')->onDelete('cascade');
            $table->foreign('marketing_pic')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
