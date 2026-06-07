<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanWarga;

class LaporanController extends Controller
{
    // Publik: Tampilkan form pelaporan
    public function create($kode_lapor)
    {
        $tempatIbadah = \App\Models\TempatIbadah::where('kode_lapor', $kode_lapor)->firstOrFail();
        return view('laporan.lapor', compact('tempatIbadah'));
    }

    // Publik: Simpan pelaporan
    public function store(Request $request, $kode_lapor)
    {
        $tempatIbadah = \App\Models\TempatIbadah::where('kode_lapor', $kode_lapor)->firstOrFail();

        $request->validate([
            'nama_calon' => 'required|string|max:255',
            'jumlah_tanggungan' => 'required|integer|min:0',
            'alamat' => 'nullable|string|max:500',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $foto_name = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $foto_name = time() . '_lapor_' . $file->getClientOriginalName();
            $file->storeAs('uploads', $foto_name, 'public');
        }

        LaporanWarga::create([
            'nama_calon' => $request->nama_calon,
            'jumlah_tanggungan' => $request->jumlah_tanggungan,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto_name,
            'status_laporan' => 'menunggu',
            'id_tempat_ibadah' => $tempatIbadah->id,
        ]);

        return redirect()->back()->with('success', 'Laporan Anda berhasil dikirim! Terima kasih atas partisipasinya.');
    }

    // Authenticated (Admin/User): Tampilkan daftar laporan
    public function index()
    {
        abort_if(auth()->user()->role === 'admin', 403, 'Akses ditolak. Halaman ini bukan untuk admin.');
        
        $tempatIbadah = \App\Models\TempatIbadah::find(auth()->user()->id_tempat_ibadah);
        
        $laporans = LaporanWarga::where('id_tempat_ibadah', $tempatIbadah->id)
                    ->where('status_laporan', 'menunggu')
                    ->orderBy('created_at', 'desc')->get();
                    
        return view('laporan.index', compact('laporans', 'tempatIbadah'));
    }
}
