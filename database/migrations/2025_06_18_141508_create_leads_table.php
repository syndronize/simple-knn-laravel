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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->bigInteger('industry_id')->unsigned();
            $table->string('alamat');
            $table->bigInteger('pic_name')->unsigned();
            $table->tinyInteger('status')->default(0);
            $table->enum('type', ['none', 'cold leads', 'warm leads', 'hot leads'])->default('none');
            $table->string('leads_0')->nullable();
            $table->timestamps();
            $table->foreign('industry_id')->references('id')->on('industry')->onDelete('cascade');
            $table->foreign('pic_name')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
