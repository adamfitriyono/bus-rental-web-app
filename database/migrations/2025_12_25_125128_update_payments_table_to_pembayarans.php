<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePaymentsTableToPembayarans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop foreign key constraint first
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        
        // Rename table from payments to pembayarans
        Schema::rename('payments', 'pembayarans');
        
        // Rename columns using raw SQL
        DB::statement('ALTER TABLE pembayarans CHANGE id id_pembayaran BIGINT UNSIGNED AUTO_INCREMENT');
        DB::statement('ALTER TABLE pembayarans CHANGE no_invoice kode_transaksi VARCHAR(255) NULL');
        DB::statement('ALTER TABLE pembayarans CHANGE total jumlah INT NOT NULL');
        DB::statement('ALTER TABLE pembayarans CHANGE status status_pembayaran VARCHAR(20) DEFAULT "pending"');
        DB::statement('ALTER TABLE pembayarans CHANGE bukti bukti_transfer LONGBLOB NULL');
        
        Schema::table('pembayarans', function (Blueprint $table) {
            // Drop user_id column if exists
            if (Schema::hasColumn('pembayarans', 'user_id')) {
                $table->dropColumn('user_id');
            }
            
            // Add new columns
            $table->unsignedBigInteger('id_pemesanan')->nullable()->after('id_pembayaran');
            $table->string('metode_pembayaran', 50)->after('id_pemesanan');
            $table->string('referensi_eksternal', 100)->nullable()->after('kode_transaksi');
            $table->timestamp('tanggal_pembayaran')->nullable()->after('status_pembayaran');
        });
        
        // Add foreign key and indexes
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanans')->onDelete('cascade');
            $table->index('status_pembayaran');
            $table->index('kode_transaksi');
            $table->unique('kode_transaksi', 'unique_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropForeign(['id_pemesanan']);
            $table->dropUnique('unique_transaksi');
            $table->dropIndex(['status_pembayaran']);
            $table->dropIndex(['kode_transaksi']);
            $table->dropColumn(['id_pemesanan', 'metode_pembayaran', 'referensi_eksternal', 'tanggal_pembayaran']);
        });
        
        // Rename columns back
        DB::statement('ALTER TABLE pembayarans CHANGE id_pembayaran id BIGINT UNSIGNED AUTO_INCREMENT');
        DB::statement('ALTER TABLE pembayarans CHANGE kode_transaksi no_invoice VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE pembayarans CHANGE jumlah total INT NOT NULL');
        DB::statement('ALTER TABLE pembayarans CHANGE status_pembayaran status INT DEFAULT 1');
        DB::statement('ALTER TABLE pembayarans CHANGE bukti_transfer bukti VARCHAR(255) NULL');
        
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
        Schema::rename('pembayarans', 'payments');
    }
}
