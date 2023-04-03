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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->text('nota');
            $table->text('tanggal');
            $table->text('id_user');
            $table->text('id_customer');
            $table->text('sub_total');
            $table->text('diskon');
            $table->text('grand_total');
            $table->text('cash');
            $table->text('kembalian');
            $table->text('total');
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
        Schema::dropIfExists('transaksi');
    }
};
