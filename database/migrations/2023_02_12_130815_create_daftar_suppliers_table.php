<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_supplier', function (Blueprint $table) {
            $table->id();
            $table->text('nama_supplier');
            $table->text('hp_supplier');
            $table->text('alamat_supplier');
            $table->text('deskripsi_supplier');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daftar_supplier');
    }
};
