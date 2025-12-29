<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateUsersTableAddTicketFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom status jika belum ada (untuk email verification)
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status', 20)->default('pending')->after('role');
            }
            // Tambah kolom email_verified
            if (!Schema::hasColumn('users', 'email_verified')) {
                $table->boolean('email_verified')->default(false)->after('status');
            }
            // email_verified_at sudah ada di migration awal, tidak perlu ditambah lagi
        });
        
        // Rename telepon ke nomor_hp menggunakan DB::statement
        if (Schema::hasColumn('users', 'telepon') && !Schema::hasColumn('users', 'nomor_hp')) {
            DB::statement('ALTER TABLE users CHANGE telepon nomor_hp VARCHAR(15) NULL');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('users', 'email_verified')) {
                $table->dropColumn('email_verified');
            }
        });
        
        if (Schema::hasColumn('users', 'nomor_hp') && !Schema::hasColumn('users', 'telepon')) {
            DB::statement('ALTER TABLE users CHANGE nomor_hp telepon VARCHAR(255) NULL');
        }
    }
}
