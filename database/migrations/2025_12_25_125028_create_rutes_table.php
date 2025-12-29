<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rutes', function (Blueprint $table) {
            $table->id('id_rute');
            $table->string('kota_asal', 50);
            $table->string('kota_tujuan', 50);
            $table->integer('jarak_km')->nullable();
            $table->integer('estimasi_jam')->nullable();
            $table->integer('harga_base')->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });
        
        Schema::table('rutes', function (Blueprint $table) {
            $table->index(['kota_asal', 'kota_tujuan']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rutes');
    }
}
