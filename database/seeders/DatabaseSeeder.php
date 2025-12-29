<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Run BusTicketSeeder untuk data awal sistem tiket bis
        $this->call([
            BusTicketSeeder::class,
        ]);
    }
}
