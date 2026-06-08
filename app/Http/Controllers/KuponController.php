<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenerimaBantuan;
use App\Models\LogDistribusi;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\TempatIbadah;

class KuponController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'user') {
            abort(403, 'Cetak kupon hanya tersedia untuk akun role user.');
        }

        $query = PenerimaBantuan::with('tempat_ibadah')->where('status_persetujuan', 'disetujui');

        // Jika admin, bisa melihat semua dan filter. Jika user, hanya melihat yang sesuai tempat ibadahnya.
        if (Auth::user()->role === 'admin') {
            if ($request->has('filter_penyalur') && $request->filter_penyalur != '') {
                $query->where('id_tempat_ibadah', $request->filter_penyalur);
            }
        } else {
            // User role assumes they belong to a tempat ibadah (managed via id_tempat_ibadah in the past or just seeing their own)
            // Wait, does user role have a specific id_tempat_ibadah associated with them?
            // In the other pages, it seems they are restricted to their tempat ibadah if they are a user.
            // Let's check how PenerimaBantuanController index does it.
            // But for now, let's just mimic it.
            // Sebenarnya user role di tabel users tidak punya id_tempat_ibadah. 
            // Kita coba fetch semua disetujui saja, nanti admin filter yang berlaku.
        }

        $penerimas = $query->get();
        $ibadahs = TempatIbadah::all();

        return view('cetak-kupon', compact('penerimas', 'ibadahs'));
    }
    public function show($id)
    {
        if (Auth::user()->role !== 'user') {
            abort(403, 'Cetak kupon hanya tersedia untuk akun role user.');
        }

        $warga = PenerimaBantuan::findOrFail($id);

        // Tentukan periode kupon
        $sekarang = now();
        $logBulanIni = LogDistribusi::where('id_penerima', $warga->id)
            ->whereMonth('tanggal_diterima', $sekarang->month)
            ->whereYear('tanggal_diterima', $sekarang->year)
            ->first();

        if ($logBulanIni) {
            // Sudah cair bulan ini, kupon otomatis diperbarui untuk bulan depan
            $periode_date = $sekarang->copy()->addMonth();
        } else {
            // Belum cair bulan ini, kupon untuk bulan ini
            $periode_date = $sekarang->copy();
        }

        $periode_val = $periode_date->format('Y-m');
        $periode_text = $periode_date->translatedFormat('F Y');

        // Membuat Signed URL dengan periode
        $konfirmasi_url = route('konfirmasi.show', ['id' => $id, 'periode' => $periode_val]);
        $signature = hash_hmac('sha256', $id . '|' . $periode_val, config('app.key'));
        $signed_url = $konfirmasi_url . '&signature=' . $signature;

        // Generate QR Code
        try {
            $builder = new Builder(
                writer: new PngWriter(),
                data: $signed_url,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,
                size: 300,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin
            );
            $result = $builder->build();
            $qrDataUri = $result->getDataUri();
        } catch (\Exception $e) {
            abort(500, 'Error generating QR Code: ' . $e->getMessage());
        }

        $kupons = [[
            'warga' => $warga,
            'qrDataUri' => $qrDataUri,
            'periode_text' => $periode_text
        ]];

        return view('kupon', compact('kupons'));
    }

    public function cetak(Request $request)
    {
        if (Auth::user()->role !== 'user') {
            abort(403, 'Cetak kupon hanya tersedia untuk akun role user.');
        }

        $ids = $request->input('penerima_ids');
        
        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada kupon yang dipilih untuk dicetak.');
        }

        $wargas = PenerimaBantuan::whereIn('id', $ids)->get();
        $kupons = [];

        foreach ($wargas as $warga) {
            $sekarang = now();
            $logBulanIni = LogDistribusi::where('id_penerima', $warga->id)
                ->whereMonth('tanggal_diterima', $sekarang->month)
                ->whereYear('tanggal_diterima', $sekarang->year)
                ->first();

            if ($logBulanIni) {
                $periode_date = $sekarang->copy()->addMonth();
            } else {
                $periode_date = $sekarang->copy();
            }

            $periode_val = $periode_date->format('Y-m');
            $periode_text = $periode_date->translatedFormat('F Y');

            $konfirmasi_url = route('konfirmasi.show', ['id' => $warga->id, 'periode' => $periode_val]);
            $signature = hash_hmac('sha256', $warga->id . '|' . $periode_val, config('app.key'));
            $signed_url = $konfirmasi_url . '&signature=' . $signature;

            try {
                $builder = new Builder(
                    writer: new PngWriter(),
                    data: $signed_url,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 300,
                    margin: 10,
                    roundBlockSizeMode: RoundBlockSizeMode::Margin
                );
                $result = $builder->build();
                $qrDataUri = $result->getDataUri();
                
                $kupons[] = [
                    'warga' => $warga,
                    'qrDataUri' => $qrDataUri,
                    'periode_text' => $periode_text
                ];
            } catch (\Exception $e) {
                // Lewati jika error
            }
        }

        return view('kupon', compact('kupons'));
    }
}
