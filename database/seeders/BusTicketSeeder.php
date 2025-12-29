<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rute;
use App\Models\Bus;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class BusTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Superuser if not exists
        User::firstOrCreate(
            ['email' => 'superuser@kalinggajaya.com'],
            [
                'name' => 'Super User',
                'password' => Hash::make('password123'),
                'role' => 2,
                'nomor_hp' => '081234567890',
                'status' => 'aktif',
                'email_verified' => true,
                'email_verified_at' => now()
            ]
        );

        // Create Admin if not exists
        User::firstOrCreate(
            ['email' => 'admin@kalinggajaya.com'],
            [
                'name' => 'Admin Kalingga Jaya',
                'password' => Hash::make('password123'),
                'role' => 1,
                'nomor_hp' => '081234567891',
                'status' => 'aktif',
                'email_verified' => true,
                'email_verified_at' => now()
            ]
        );

        // Create Sample Routes
        $rute1 = Rute::firstOrCreate(
            ['kota_asal' => 'Jepara', 'kota_tujuan' => 'Jakarta'],
            [
                'jarak_km' => 600,
                'estimasi_jam' => 10,
                'harga_base' => 150000,
                'status' => 'aktif'
            ]
        );

        $rute2 = Rute::firstOrCreate(
            ['kota_asal' => 'Jepara', 'kota_tujuan' => 'Surabaya'],
            [
                'jarak_km' => 350,
                'estimasi_jam' => 6,
                'harga_base' => 100000,
                'status' => 'aktif'
            ]
        );

        $rute3 = Rute::firstOrCreate(
            ['kota_asal' => 'Jakarta', 'kota_tujuan' => 'Jepara'],
            [
                'jarak_km' => 600,
                'estimasi_jam' => 10,
                'harga_base' => 150000,
                'status' => 'aktif'
            ]
        );

        $rute4 = Rute::firstOrCreate(
            ['kota_asal' => 'Surabaya', 'kota_tujuan' => 'Jepara'],
            [
                'jarak_km' => 350,
                'estimasi_jam' => 6,
                'harga_base' => 100000,
                'status' => 'aktif'
            ]
        );

        // Create Sample Buses
        $bus1 = Bus::firstOrCreate(
            ['plat_nomor' => 'H-1234-AB'],
            [
                'nama_bus' => 'Kalingga Jaya Executive',
                'jenis_kelas' => 'Executive',
                'kapasitas_kursi' => 45,
                'kursi_tersedia' => 45,
                'tahun_pembuatan' => 2020,
                'fasilitas' => 'AC, WiFi, Reclining Seat, Entertainment, Toilet, Snack',
                'status' => 'aktif'
            ]
        );

        $bus2 = Bus::firstOrCreate(
            ['plat_nomor' => 'H-5678-CD'],
            [
                'nama_bus' => 'Kalingga Jaya Luxury',
                'jenis_kelas' => 'Luxury',
                'kapasitas_kursi' => 30,
                'kursi_tersedia' => 30,
                'tahun_pembuatan' => 2021,
                'fasilitas' => 'AC, WiFi, Reclining Seat, Entertainment, Toilet, Snack, USB Charger, Blanket',
                'status' => 'aktif'
            ]
        );

        $bus3 = Bus::firstOrCreate(
            ['plat_nomor' => 'H-9012-EF'],
            [
                'nama_bus' => 'Kalingga Jaya Standard',
                'jenis_kelas' => 'Standard',
                'kapasitas_kursi' => 55,
                'kursi_tersedia' => 55,
                'tahun_pembuatan' => 2019,
                'fasilitas' => 'AC, Toilet',
                'status' => 'aktif'
            ]
        );

        // Create Sample Schedules for next 7 days
        $rutes = [$rute1, $rute2, $rute3, $rute4];
        $buses = [$bus1, $bus2, $bus3];
        
        for ($day = 0; $day < 7; $day++) {
            $tanggal = Carbon::now()->addDays($day);
            
            foreach ($rutes as $rute) {
                foreach ($buses as $bus) {
                    // Skip if jadwal already exists
                    $exists = Jadwal::where('id_rute', $rute->id_rute)
                                   ->where('id_bus', $bus->id_bus)
                                   ->whereDate('tanggal', $tanggal->format('Y-m-d'))
                                   ->exists();
                    
                    if (!$exists) {
                        // Create jadwal with different times
                        $jamBerangkat = ['06:00', '14:00', '22:00'];
                        
                        foreach ($jamBerangkat as $jam) {
                            $jamTiba = Carbon::createFromFormat('H:i', $jam)
                                            ->addHours($rute->estimasi_jam)
                                            ->format('H:i');
                            
                            $hargaTiket = $rute->harga_base;
                            if ($bus->jenis_kelas == 'Luxury') {
                                $hargaTiket = $rute->harga_base * 1.5;
                            } elseif ($bus->jenis_kelas == 'Standard') {
                                $hargaTiket = $rute->harga_base * 0.9;
                            }

                            $jadwal = Jadwal::create([
                                'id_rute' => $rute->id_rute,
                                'id_bus' => $bus->id_bus,
                                'tanggal' => $tanggal->format('Y-m-d'),
                                'jam_berangkat' => $jam,
                                'jam_tiba' => $jamTiba,
                                'harga_tiket' => $hargaTiket,
                                'status' => 'aktif'
                            ]);

                            // Create kursi records for this jadwal
                            for ($i = 1; $i <= $bus->kapasitas_kursi; $i++) {
                                Kursi::create([
                                    'id_jadwal' => $jadwal->id_jadwal,
                                    'id_bus' => $bus->id_bus,
                                    'nomor_kursi' => (string)$i,
                                    'status_kursi' => 'tersedia'
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
