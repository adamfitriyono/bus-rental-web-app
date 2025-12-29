<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Bus;
use App\Models\Kursi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jadwal::with(['rute', 'bus']);

        // Filter by tanggal
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter by rute
        if ($request->has('rute') && $request->rute != '') {
            $query->where('id_rute', $request->rute);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $jadwals = $query->orderBy('tanggal', 'DESC')
                        ->orderBy('jam_berangkat', 'ASC')
                        ->paginate(15);

        $rutes = Rute::where('status', 'aktif')->get();

        return view('admin.jadwal.index', [
            'jadwals' => $jadwals,
            'rutes' => $rutes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rutes = Rute::where('status', 'aktif')->get();
        $buses = Bus::where('status', 'aktif')->get();
        
        return view('admin.jadwal.create', [
            'rutes' => $rutes,
            'buses' => $buses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_rute' => 'required|exists:rutes,id_rute',
            'id_bus' => 'required|exists:buses,id_bus',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_tiba' => 'required|date_format:H:i|after:jam_berangkat',
            'harga_tiket' => 'required|integer|min:0',
            'status' => 'required|string|in:aktif,dibatalkan'
        ]);

        $jadwal = Jadwal::create($validated);

        // Create kursi records for this jadwal
        $bus = Bus::findOrFail($validated['id_bus']);
        for ($i = 1; $i <= $bus->kapasitas_kursi; $i++) {
            Kursi::create([
                'id_jadwal' => $jadwal->id_jadwal,
                'id_bus' => $validated['id_bus'],
                'nomor_kursi' => (string)$i,
                'status_kursi' => 'tersedia'
            ]);
        }

        return redirect()->route('admin.jadwal.index')
                         ->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Bulk create jadwal untuk beberapa hari
     */
    public function bulkCreate(Request $request)
    {
        $validated = $request->validate([
            'id_rute' => 'required|exists:rutes,id_rute',
            'id_bus' => 'required|exists:buses,id_bus',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_tiba' => 'required|date_format:H:i|after:jam_berangkat',
            'harga_tiket' => 'required|integer|min:0',
        ]);

        $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        $tanggalSelesai = Carbon::parse($validated['tanggal_selesai']);
        $bus = Bus::findOrFail($validated['id_bus']);

        $count = 0;
        while ($tanggalMulai <= $tanggalSelesai) {
            // Check if jadwal already exists
            $exists = Jadwal::where('id_rute', $validated['id_rute'])
                           ->where('id_bus', $validated['id_bus'])
                           ->whereDate('tanggal', $tanggalMulai->format('Y-m-d'))
                           ->where('jam_berangkat', $validated['jam_berangkat'])
                           ->exists();

            if (!$exists) {
                $jadwal = Jadwal::create([
                    'id_rute' => $validated['id_rute'],
                    'id_bus' => $validated['id_bus'],
                    'tanggal' => $tanggalMulai->format('Y-m-d'),
                    'jam_berangkat' => $validated['jam_berangkat'],
                    'jam_tiba' => $validated['jam_tiba'],
                    'harga_tiket' => $validated['harga_tiket'],
                    'status' => 'aktif'
                ]);

                // Create kursi records
                for ($i = 1; $i <= $bus->kapasitas_kursi; $i++) {
                    Kursi::create([
                        'id_jadwal' => $jadwal->id_jadwal,
                        'id_bus' => $validated['id_bus'],
                        'nomor_kursi' => (string)$i,
                        'status_kursi' => 'tersedia'
                    ]);
                }

                $count++;
            }

            $tanggalMulai->addDay();
        }

        return redirect()->route('admin.jadwal.index')
                         ->with('success', "$count jadwal berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(Jadwal $jadwal)
    {
        return view('admin.jadwal.show', ['jadwal' => $jadwal]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $rutes = Rute::where('status', 'aktif')->get();
        $buses = Bus::where('status', 'aktif')->get();
        
        return view('admin.jadwal.edit', [
            'jadwal' => $jadwal,
            'rutes' => $rutes,
            'buses' => $buses
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_rute' => 'required|exists:rutes,id_rute',
            'id_bus' => 'required|exists:buses,id_bus',
            'tanggal' => 'required|date',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_tiba' => 'required|date_format:H:i|after:jam_berangkat',
            'harga_tiket' => 'required|integer|min:0',
            'status' => 'required|string|in:aktif,dibatalkan,selesai'
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update($validated);

        return redirect()->route('admin.jadwal.index')
                         ->with('success', 'Jadwal berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        
        // Check if has pemesanan
        if ($jadwal->pemesanans()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus jadwal yang sudah memiliki pemesanan');
        }

        // Delete associated kursis
        $jadwal->kursis()->delete();
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
                         ->with('success', 'Jadwal berhasil dihapus');
    }
}
