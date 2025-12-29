<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->unsignedBigInteger('id_rute');
            $table->unsignedBigInteger('id_bus');
            $table->time('jam_berangkat');
            $table->time('jam_tiba');
            $table->date('tanggal');
            $table->integer('harga_tiket');
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });
        
        Schema::table('jadwals', function (Blueprint $table) {
            $table->foreign('id_rute')->references('id_rute')->on('rutes')->onDelete('cascade');
            $table->foreign('id_bus')->references('id_bus')->on('buses')->onDelete('cascade');
            $table->index('tanggal');
            $table->index(['id_rute', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwals');
    }
}
