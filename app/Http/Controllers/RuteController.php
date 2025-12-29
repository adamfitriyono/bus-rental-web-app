<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use Illuminate\Http\Request;

class RuteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rutes = Rute::orderBy('kota_asal')->orderBy('kota_tujuan')->get();
        
        return view('admin.rute.index', [
            'rutes' => $rutes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rute.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kota_asal' => 'required|string|max:50',
            'kota_tujuan' => 'required|string|max:50|different:kota_asal',
            'jarak_km' => 'nullable|integer|min:0',
            'estimasi_jam' => 'nullable|integer|min:0',
            'harga_base' => 'nullable|integer|min:0',
            'status' => 'required|string|in:aktif,nonaktif'
        ]);

        Rute::create($validated);

        return redirect()->route('admin.rute.index')
                         ->with('success', 'Rute berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rute $rute)
    {
        return view('admin.rute.show', ['rute' => $rute]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $rute = Rute::findOrFail($id);
        
        return view('admin.rute.edit', [
            'rute' => $rute
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kota_asal' => 'required|string|max:50',
            'kota_tujuan' => 'required|string|max:50|different:kota_asal',
            'jarak_km' => 'nullable|integer|min:0',
            'estimasi_jam' => 'nullable|integer|min:0',
            'harga_base' => 'nullable|integer|min:0',
            'status' => 'required|string|in:aktif,nonaktif'
        ]);

        $rute = Rute::findOrFail($id);
        $rute->update($validated);

        return redirect()->route('admin.rute.index')
                         ->with('success', 'Rute berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rute = Rute::findOrFail($id);
        
        // Check if rute has active jadwal
        if ($rute->jadwals()->where('status', 'aktif')->exists()) {
            return back()->with('error', 'Tidak dapat menghapus rute yang memiliki jadwal aktif');
        }

        $rute->delete();

        return redirect()->route('admin.rute.index')
                         ->with('success', 'Rute berhasil dihapus');
    }
}
