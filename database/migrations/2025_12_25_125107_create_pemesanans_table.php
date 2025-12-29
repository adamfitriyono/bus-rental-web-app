<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedBigInteger('id_bus');
            $table->string('nomor_kursi', 100); // Bisa multiple kursi dipisah koma
            $table->integer('jumlah_kursi');
            $table->string('status_pemesanan', 20)->default('pending');
            $table->timestamp('tanggal_pemesanan')->useCurrent();
            $table->timestamps();
        });
        
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwals')->onDelete('cascade');
            $table->foreign('id_bus')->references('id_bus')->on('buses')->onDelete('cascade');
            $table->index('id_user');
            $table->index('status_pemesanan');
            $table->index('tanggal_pemesanan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanans');
    }
}
