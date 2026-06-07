<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\TempatIbadah;
use App\Models\PenerimaBantuan;

use App\Models\User;

class MapController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role === 'user') {
            $ibadahs = TempatIbadah::where('id', Auth::user()->id_tempat_ibadah)->get();
            $penerimas = PenerimaBantuan::with('tempat_ibadah')
                            ->where('id_tempat_ibadah', Auth::user()->id_tempat_ibadah)->get();
        } else {
            $ibadahs = TempatIbadah::all();
            $penerimas = PenerimaBantuan::with('tempat_ibadah')->get();
        }

        $laporan_fill = null;
        if ($request->has('add_laporan')) {
            $laporan_fill = \App\Models\LaporanWarga::find($request->add_laporan);
        }

        return view('dashboard', compact('ibadahs', 'penerimas', 'laporan_fill'));
    }

    public function daftarPenerimaBantuan(Request $request)
    {
        $ibadahs = TempatIbadah::all();

        $query = PenerimaBantuan::with('tempat_ibadah');

        if (Auth::user()->role === 'user') {
            $query->where('id_tempat_ibadah', Auth::user()->id_tempat_ibadah);
            $ibadahs = TempatIbadah::where('id', Auth::user()->id_tempat_ibadah)->get();
        } elseif ($request->filled('filter_penyalur')) {
            $query->where('id_tempat_ibadah', (int)$request->filter_penyalur);
        }

        $penerimas = $query->get();

        return view('daftar-penerima-bantuan', compact('penerimas', 'ibadahs'));
    }

    public function daftarTempatIbadah()
    {
        if (Auth::user()->role === 'user') {
            $ibadahs = TempatIbadah::where('id', Auth::user()->id_tempat_ibadah)->get();
        } else {
            $ibadahs = TempatIbadah::all();
        }

        // Include admin email for each ibadah if admin, or just fetch all users
        $adminUsers = User::where('role', 'user')->get()->keyBy('id_tempat_ibadah');

        return view('daftar-tempat-ibadah', compact('ibadahs', 'adminUsers'));
    }

    public function save(Request $request)
    {
        $type = $request->type;
        $nama = $request->nama;
        $lat = $request->lat;
        $lng = $request->lng;
        $alamat = $request->alamat;

        if ($type == 'ibadah') {
            // ... (ibadah creation logic remains unchanged)
            if (Auth::user()->role !== 'admin') {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized']);
            }
            $ibadah = TempatIbadah::create([
                'nama_tempat' => $nama,
                'jenis_tempat_ibadah' => $request->jenis_tempat_ibadah,
                'nama_pengurus' => $request->nama_pengurus,
                'kontak_pengurus' => $request->kontak_pengurus,
                'lat' => $lat,
                'lng' => $lng,
                'radius_meter' => 500,
                'alamat' => $alamat
            ]);

            $baseEmail = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama));
            $email = $baseEmail . '@email.com';
            if (User::where('email', $email)->exists()) {
                $email = $baseEmail . rand(100, 999) . '@email.com';
            }

            User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'user',
                'id_tempat_ibadah' => $ibadah->id,
            ]);
        } else {
            if (Auth::user()->role !== 'user') {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized']);
            }

            $tempatIbadah = \App\Models\TempatIbadah::find(Auth::user()->id_tempat_ibadah);
            if ($tempatIbadah) {
                $distance = $this->calculateDistance($tempatIbadah->lat, $tempatIbadah->lng, $lat, $lng);
                if ($distance > $tempatIbadah->radius_meter) {
                    return response()->json([
                        'status' => 'error', 
                        'message' => 'Hanya bisa di lingkaran radius pelayanan'
                    ]);
                }
            }

            // Pengecekan NIK dan Nomor KK Ganda
            $existsNIK = \App\Models\PenerimaBantuan::where('nik_kepala_keluarga', $request->nik_kepala_keluarga)->first();
            if ($existsNIK) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal: NIK Kepala Keluarga (' . $request->nik_kepala_keluarga . ') sudah terdaftar di dalam sistem.'
                ]);
            }

            $existsKK = \App\Models\PenerimaBantuan::where('nomor_kk', $request->nomor_kk)->first();
            if ($existsKK) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal: Nomor Kartu Keluarga (' . $request->nomor_kk . ') sudah terdaftar di dalam sistem.'
                ]);
            }

            // Handle multi-foto upload
            $foto_names = [];
            if ($request->hasFile('foto')) {
                $files = is_array($request->file('foto')) ? $request->file('foto') : [$request->file('foto')];
                foreach ($files as $i => $file) {
                    $foto_name = time() . '_' . $i . '_' . $file->getClientOriginalName();
                    $file->storeAs('uploads', $foto_name, 'public');
                    $foto_names[] = $foto_name;
                }
            } else if ($request->filled('id_laporan_warga')) {
                $laporan = \App\Models\LaporanWarga::find($request->id_laporan_warga);
                if ($laporan && $laporan->foto) {
                    // Copy existing foto from laporan
                    $foto_names[] = $laporan->foto;
                }
            }

            // Handle dokumen laporan upload
            $dokumen_laporan = '';
            if ($request->hasFile('dokumen_laporan')) {
                $dokumen_laporan = time() . '_pdf_' . $request->file('dokumen_laporan')->getClientOriginalName();
                $request->file('dokumen_laporan')->storeAs('uploads', $dokumen_laporan, 'public');
            }

            PenerimaBantuan::create([
                'nama_kepala_keluarga' => $nama,
                'id_tempat_ibadah' => Auth::user()->role === 'user' ? Auth::user()->id_tempat_ibadah : $request->id_tempat_ibadah,
                'nik_kepala_keluarga' => $request->nik_kepala_keluarga,
                'nomor_kk' => $request->nomor_kk,
                'jumlah_tanggungan' => $request->jumlah_tanggungan,
                'foto' => implode(',', $foto_names),
                'lat' => $lat,
                'lng' => $lng,
                'alamat' => $alamat,
                'dokumen_laporan' => $dokumen_laporan,
                'status_persetujuan' => 'menunggu',
            ]);

            if ($request->filled('id_laporan_warga')) {
                \App\Models\LaporanWarga::where('id', $request->id_laporan_warga)->update(['status_laporan' => 'diproses']);
            }
        }

        $this->recalculateCoverage();
        return response()->json(['status' => 'success']);
    }

    public function updateRadius(Request $request)
    {
        $id = $request->id;
        $radius = $request->radius;
        TempatIbadah::where('id', $id)->update(['radius_meter' => $radius]);
        $this->recalculateCoverage();
        return response()->json(['status' => 'success']);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $nama = $request->nama;
        $alamat = $request->alamat;
        $type = $request->type;

        if ($type == 'ibadah') {
            $data = [
                'nama_tempat' => $nama,
                'alamat' => $alamat,
            ];
            if ($request->filled('jenis_tempat_ibadah')) $data['jenis_tempat_ibadah'] = $request->jenis_tempat_ibadah;
            if ($request->filled('nama_pengurus')) $data['nama_pengurus'] = $request->nama_pengurus;
            if ($request->filled('kontak_pengurus')) $data['kontak_pengurus'] = $request->kontak_pengurus;
            if ($request->filled('radius_meter')) $data['radius_meter'] = (int)$request->radius_meter;

            TempatIbadah::where('id', $id)->update($data);
            $this->recalculateCoverage();
        } else {
            $data = [
                'nama_kepala_keluarga' => $nama,
                'id_tempat_ibadah' => Auth::user()->role === 'user' ? Auth::user()->id_tempat_ibadah : $request->id_tempat_ibadah,
                'alamat' => $alamat,
            ];

            if ($request->filled('nik_kepala_keluarga')) $data['nik_kepala_keluarga'] = $request->nik_kepala_keluarga;
            if ($request->filled('nomor_kk')) $data['nomor_kk'] = $request->nomor_kk;
            if ($request->has('jumlah_tanggungan')) $data['jumlah_tanggungan'] = $request->jumlah_tanggungan;

            // Handle existing photos and deletions
            $penerima = PenerimaBantuan::find($id);
            $current_fotos = !empty($penerima->foto) ? explode(',', $penerima->foto) : [];

            if ($request->has('hapus_foto') && is_array($request->hapus_foto)) {
                foreach ($request->hapus_foto as $hf) {
                    if (($key = array_search($hf, $current_fotos)) !== false) {
                        unset($current_fotos[$key]);
                        $path = storage_path('app/public/uploads/' . $hf);
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }
                }
                $current_fotos = array_values($current_fotos);
            }

            // Handle new foto uploads
            if ($request->hasFile('foto')) {
                $files = is_array($request->file('foto')) ? $request->file('foto') : [$request->file('foto')];
                foreach ($files as $i => $file) {
                    $foto_name = time() . '_' . $i . '_' . $file->getClientOriginalName();
                    $file->storeAs('uploads', $foto_name, 'public');
                    $current_fotos[] = $foto_name;
                }
            }

            $data['foto'] = implode(',', $current_fotos);

            PenerimaBantuan::where('id', $id)->update($data);
        }

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        if ($type == 'ibadah') {
            TempatIbadah::where('id', $id)->delete();
        } else {
            PenerimaBantuan::where('id', $id)->delete();
        }

        $this->recalculateCoverage();
        return redirect()->back();
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));
        return $earthRadius * $c;
    }

    private function recalculateCoverage()
    {
        $all_ibadahs = TempatIbadah::all(['id', 'lat', 'lng', 'radius_meter']);

        $penerimas = PenerimaBantuan::all(['id', 'lat', 'lng']);

        foreach ($penerimas as $pb) {
            $best_id = null;
            $min_distance = INF;

            foreach ($all_ibadahs as $ib) {
                $distance = $this->calculateDistance($ib->lat, $ib->lng, $pb->lat, $pb->lng);

                if ($distance <= $ib->radius_meter) {
                    if ($distance < $min_distance) {
                        $min_distance = $distance;
                        $best_id = $ib->id;
                    }
                }
            }

            if ($best_id !== null) {
                PenerimaBantuan::where('id', $pb->id)->update([
                    'id_tempat_ibadah' => $best_id,
                    'jarak_ke_penyalur' => $min_distance,
                ]);
            } else {
                PenerimaBantuan::where('id', $pb->id)->update([
                    'id_tempat_ibadah' => null,
                    'jarak_ke_penyalur' => null,
                ]);
            }
        }
    }
}
