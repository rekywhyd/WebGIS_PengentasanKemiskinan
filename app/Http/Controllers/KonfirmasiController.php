<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenerimaBantuan;
use App\Models\TempatIbadah;
use App\Models\LogDistribusi;

class KonfirmasiController extends Controller
{
    public function show(Request $request)
    {
        $id = $request->query('id', 0);
        $periode_param = $request->query('periode', '');
        $signature_param = $request->query('signature', '');

        if ($id <= 0 || empty($signature_param) || empty($periode_param)) {
            abort(400, 'Permintaan tidak valid. Parameter periode atau signature hilang.');
        }

        // Verifikasi Signature dengan periode
        $expected_signature = hash_hmac('sha256', $id . '|' . $periode_param, config('app.key'));
        if (!hash_equals($expected_signature, $signature_param)) {
            abort(403, 'Invalid Signature. URL telah dimodifikasi atau tidak sah.');
        }

        // Validasi Periode Kupon
        if ($periode_param !== now()->format('Y-m')) {
            $namaBulan = \Carbon\Carbon::createFromFormat('Y-m', $periode_param)->translatedFormat('F Y');
            return response("Kupon ini hanya berlaku untuk periode {$namaBulan}. Saat ini adalah periode " . now()->translatedFormat('F Y') . ". Silakan perbarui kupon Anda.", 400);
        }

        $warga = PenerimaBantuan::findOrFail($id);

        if (empty($warga->id_tempat_ibadah)) {
            return response('Warga ini belum terdaftar di tempat ibadah manapun untuk pengambilan bantuan.', 400);
        }

        $id_tempat = $warga->id_tempat_ibadah;

        // Cek apakah sudah pernah dicairkan bulan ini
        $log = LogDistribusi::where('id_penerima', $id)
            ->whereMonth('tanggal_diterima', now()->month)
            ->whereYear('tanggal_diterima', now()->year)
            ->orderBy('tanggal_diterima', 'desc')
            ->first();

        $sudah_cair = ($log !== null);

        return view('konfirmasi', compact('warga', 'id_tempat', 'sudah_cair', 'log'));
    }

    public function process(Request $request)
    {
        $id = $request->query('id', 0);
        $periode_param = $request->query('periode', '');
        $signature_param = $request->query('signature', '');

        if ($id <= 0 || empty($signature_param) || empty($periode_param)) {
            abort(400, 'Permintaan tidak valid. Parameter periode atau signature hilang.');
        }

        // Verifikasi Signature dengan periode
        $expected_signature = hash_hmac('sha256', $id . '|' . $periode_param, config('app.key'));
        if (!hash_equals($expected_signature, $signature_param)) {
            abort(403, 'Invalid Signature.');
        }

        // Validasi Periode Kupon
        if ($periode_param !== now()->format('Y-m')) {
            return redirect()->back()->with('error', 'Kupon kedaluwarsa atau tidak sesuai dengan periode bulan ini.');
        }

        $warga = PenerimaBantuan::findOrFail($id);

        // Cek sudah dicairkan bulan ini
        $existing = LogDistribusi::where('id_penerima', $id)
            ->whereMonth('tanggal_diterima', now()->month)
            ->whereYear('tanggal_diterima', now()->year)
            ->exists();

        if ($existing) {
            return redirect()->back()->with('error', 'Bantuan bulan ini sudah diproses.');
        }

        $tgl_sekarang = now();

        // Upload foto penyerahan
        $foto_penyerahan = '';
        if ($request->hasFile('foto_penyerahan')) {
            $foto_penyerahan = time() . '_bukti_' . $request->file('foto_penyerahan')->getClientOriginalName();
            $request->file('foto_penyerahan')->storeAs('uploads', $foto_penyerahan, 'public');
        }

        LogDistribusi::create([
            'id_penerima' => $id,
            'tanggal_diterima' => $tgl_sekarang,
            'status' => 'Menunggu',
            'foto_penyerahan' => $foto_penyerahan,
        ]);

        // Redirect back with success
        $log = LogDistribusi::where('id_penerima', $id)
            ->whereMonth('tanggal_diterima', now()->month)
            ->whereYear('tanggal_diterima', now()->year)
            ->orderBy('tanggal_diterima', 'desc')
            ->first();

        $warga = PenerimaBantuan::findOrFail($id);
        $id_tempat = $warga->id_tempat_ibadah;
        $sudah_cair = true;
        $pesan_sukses = true;

        return view('konfirmasi', compact('warga', 'id_tempat', 'sudah_cair', 'log', 'pesan_sukses'));
    }

    public function scan()
    {
        abort_if(auth()->user()->role === 'admin', 403, 'Akses ditolak. Halaman ini bukan untuk admin.');
        return view('scan');
    }
}
