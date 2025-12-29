<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->id('id_tiket');
            $table->string('kode_tiket', 50)->unique();
            $table->unsignedBigInteger('id_pemesanan');
            $table->longText('qr_code')->nullable();
            $table->longText('file_pdf')->nullable(); // Store as base64 or file path instead of binary
            $table->string('status_tiket', 20)->default('aktif');
            $table->timestamp('tanggal_terbit')->useCurrent();
            $table->timestamps();
        });
        
        Schema::table('tikets', function (Blueprint $table) {
            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanans')->onDelete('cascade');
            $table->index('kode_tiket');
            $table->index('status_tiket');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tikets');
    }
}
