<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $ibadahs = \App\Models\TempatIbadah::all();
        $penerimas = \App\Models\PenerimaBantuan::with('tempat_ibadah')->get();
        return view('dashboard', compact('ibadahs', 'penerimas'));
    }

    public function daftarPenerimaBantuan()
    {
        $penerimas = \App\Models\PenerimaBantuan::with('tempat_ibadah')->get();
        $ibadahs = \App\Models\TempatIbadah::all();
        return view('daftar-penerima-bantuan', compact('penerimas', 'ibadahs'));
    }

    public function daftarTempatIbadah()
    {
        $ibadahs = \App\Models\TempatIbadah::all();
        return view('daftar-tempat-ibadah', compact('ibadahs'));
    }

    public function save(Request $request)
    {
        $type = $request->type;
        $nama = $request->nama;
        $lat = $request->lat;
        $lng = $request->lng;
        $alamat = $request->alamat;

        if ($type == 'ibadah') {
            \App\Models\TempatIbadah::create([
                'nama_tempat' => $nama,
                'jenis_tempat_ibadah' => $request->jenis_tempat_ibadah,
                'kontak_person' => $request->kontak_person,
                'radius_meter' => 500,
                'lat' => $lat,
                'lng' => $lng,
                'alamat' => $alamat
            ]);
        } else {
            $fotoPath = null;
            if ($request->hasFile('foto_kondisi_rumah')) {
                $fotoPath = $request->file('foto_kondisi_rumah')->store('fotos', 'public');
            }

            \App\Models\PenerimaBantuan::create([
                'nama_kepala_keluarga' => $nama,
                'id_tempat_ibadah' => $request->id_tempat_ibadah,
                'nik_kepala_keluarga' => $request->nik_kepala_keluarga,
                'nomor_kk' => $request->nomor_kk,
                'jumlah_tanggungan' => $request->jumlah_tanggungan,
                'foto_kondisi_rumah' => $fotoPath,
                'lat' => $lat,
                'lng' => $lng,
                'alamat' => $alamat
            ]);
        }
        return response()->json(['status' => 'success']);
    }

    public function updateRadius(Request $request)
    {
        $id = $request->id;
        $radius = $request->radius;
        \App\Models\TempatIbadah::where('id', $id)->update(['radius_meter' => $radius]);
        $this->checkAndUnlinkPenerima($id);
        return response()->json(['status' => 'success']);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $nama = $request->nama;
        $alamat = $request->alamat;
        $type = $request->type;

        if ($type == 'ibadah') {
            \App\Models\TempatIbadah::where('id', $id)->update([
                'nama_tempat' => $nama,
                'jenis_tempat_ibadah' => $request->jenis_tempat_ibadah,
                'kontak_person' => $request->kontak_person,
                'alamat' => $alamat,
                'radius_meter' => $request->radius_meter
            ]);
            $this->checkAndUnlinkPenerima($id);
        } else {
            $updateData = [
                'nama_kepala_keluarga' => $nama,
                'id_tempat_ibadah' => $request->id_tempat_ibadah,
                'nik_kepala_keluarga' => $request->nik_kepala_keluarga,
                'nomor_kk' => $request->nomor_kk,
                'jumlah_tanggungan' => $request->jumlah_tanggungan,
                'alamat' => $alamat
            ];

            if ($request->hasFile('foto_kondisi_rumah')) {
                $updateData['foto_kondisi_rumah'] = $request->file('foto_kondisi_rumah')->store('fotos', 'public');
            }

            \App\Models\PenerimaBantuan::where('id', $id)->update($updateData);
        }
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        if ($type == 'ibadah') {
            \App\Models\TempatIbadah::where('id', $id)->delete();
        } else {
            \App\Models\PenerimaBantuan::where('id', $id)->delete();
        }
        return redirect()->back();
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371000; // in meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        return $earthRadius * $c;
    }

    private function checkAndUnlinkPenerima($ibadahId) {
        $ibadah = \App\Models\TempatIbadah::find($ibadahId);
        if (!$ibadah) return;

        $penerimas = \App\Models\PenerimaBantuan::where('id_tempat_ibadah', $ibadahId)->get();
        foreach ($penerimas as $penerima) {
            $distance = $this->calculateDistance($ibadah->lat, $ibadah->lng, $penerima->lat, $penerima->lng);
            if ($distance > $ibadah->radius_meter) {
                $penerima->update(['id_tempat_ibadah' => null]);
            }
        }
    }
}
