<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buses = Bus::orderBy('nama_bus')->get();
        
        return view('admin.bus.index', [
            'buses' => $buses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bus' => 'required|string|max:100',
            'jenis_kelas' => 'required|string|max:50',
            'plat_nomor' => 'required|string|max:20|unique:buses,plat_nomor',
            'kapasitas_kursi' => 'required|integer|min:1',
            'tahun_pembuatan' => 'nullable|integer|min:1900|max:' . date('Y'),
            'fasilitas' => 'nullable|string',
            'status' => 'required|string|in:aktif,nonaktif'
        ]);

        // Set kursi_tersedia sama dengan kapasitas_kursi saat pertama dibuat
        $validated['kursi_tersedia'] = $validated['kapasitas_kursi'];

        Bus::create($validated);

        return redirect()->route('admin.bus.index')
                         ->with('success', 'Bus berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bus $bus)
    {
        return view('admin.bus.show', ['bus' => $bus]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bus = Bus::findOrFail($id);
        
        return view('admin.bus.edit', [
            'bus' => $bus
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bus = Bus::findOrFail($id);

        $validated = $request->validate([
            'nama_bus' => 'required|string|max:100',
            'jenis_kelas' => 'required|string|max:50',
            'plat_nomor' => 'required|string|max:20|unique:buses,plat_nomor,' . $id . ',id_bus',
            'kapasitas_kursi' => 'required|integer|min:1',
            'tahun_pembuatan' => 'nullable|integer|min:1900|max:' . date('Y'),
            'fasilitas' => 'nullable|string',
            'status' => 'required|string|in:aktif,nonaktif'
        ]);

        // Update kursi_tersedia jika kapasitas berubah
        if ($validated['kapasitas_kursi'] != $bus->kapasitas_kursi) {
            $selisih = $validated['kapasitas_kursi'] - $bus->kapasitas_kursi;
            $validated['kursi_tersedia'] = max(0, $bus->kursi_tersedia + $selisih);
        }

        $bus->update($validated);

        return redirect()->route('admin.bus.index')
                         ->with('success', 'Bus berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bus = Bus::findOrFail($id);
        
        // Soft delete - check if has active jadwal
        if ($bus->jadwals()->where('status', 'aktif')->exists()) {
            // Update status instead of delete
            $bus->update(['status' => 'nonaktif']);
            return back()->with('warning', 'Bus memiliki jadwal aktif, status diubah menjadi nonaktif');
        }

        $bus->delete();

        return redirect()->route('admin.bus.index')
                         ->with('success', 'Bus berhasil dihapus');
    }
}
