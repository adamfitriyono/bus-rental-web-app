<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id('id_bus');
            $table->string('nama_bus', 100);
            $table->string('jenis_kelas', 50);
            $table->string('plat_nomor', 20)->unique();
            $table->integer('kapasitas_kursi');
            $table->integer('kursi_tersedia');
            $table->integer('tahun_pembuatan')->nullable();
            $table->text('fasilitas')->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
        });
        
        Schema::table('buses', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buses');
    }
}
