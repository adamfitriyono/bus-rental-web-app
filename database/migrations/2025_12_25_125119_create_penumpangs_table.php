<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenumpangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penumpangs', function (Blueprint $table) {
            $table->id('id_penumpang');
            $table->unsignedBigInteger('id_pemesanan');
            $table->string('nama_penumpang', 100);
            $table->string('tipe_identitas', 20);
            $table->string('nomor_identitas', 50);
            $table->string('nomor_hp', 15)->nullable();
            $table->timestamps();
        });
        
        Schema::table('penumpangs', function (Blueprint $table) {
            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanans')->onDelete('cascade');
            $table->index('id_pemesanan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penumpangs');
    }
}
