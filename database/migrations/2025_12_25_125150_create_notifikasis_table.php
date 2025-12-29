<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifikasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->unsignedBigInteger('id_user');
            $table->string('tipe', 50);
            $table->string('email_penerima', 100);
            $table->string('judul_email', 255)->nullable();
            $table->longText('isi_email')->nullable();
            $table->string('status_pengiriman', 20)->default('pending');
            $table->timestamp('tanggal_pengiriman')->nullable();
            $table->timestamps();
        });
        
        Schema::table('notifikasis', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->index('status_pengiriman');
            $table->index('id_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifikasis');
    }
}
