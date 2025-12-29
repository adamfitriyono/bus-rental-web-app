<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKursisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kursis', function (Blueprint $table) {
            $table->id('id_kursi');
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedBigInteger('id_bus');
            $table->string('nomor_kursi', 10);
            $table->string('status_kursi', 20)->default('tersedia');
            $table->timestamps();
        });
        
        Schema::table('kursis', function (Blueprint $table) {
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwals')->onDelete('cascade');
            $table->foreign('id_bus')->references('id_bus')->on('buses')->onDelete('cascade');
            $table->index(['id_jadwal', 'status_kursi']);
            $table->unique(['id_jadwal', 'nomor_kursi'], 'unique_kursi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kursis');
    }
}
