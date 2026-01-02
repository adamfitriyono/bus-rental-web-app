<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRentalTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop rental system tables
        Schema::dropIfExists('orders');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('alats');
        Schema::dropIfExists('categories');
        // Note: payments table might be used by both systems, handle carefully
        // Schema::dropIfExists('payments'); // Uncomment if you want to drop old payments
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Cannot reverse dropping tables without data loss
        // Keep this empty or recreate schema if needed
    }
}
