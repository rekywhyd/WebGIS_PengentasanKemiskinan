<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogDistribusi;
use App\Models\PenerimaBantuan;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = LogDistribusi::with(['penerimaBantuan.tempat_ibadah']);

        if ($request->filled('filter_periode')) {
            $periode = $request->filter_periode; // format YYYY-MM
            $query->whereRaw("DATE_FORMAT(tanggal_diterima, '%Y-%m') = ?", [$periode]);
        }

        $riwayats = $query->orderBy('tanggal_diterima', 'desc')->get();

        return view('riwayat', compact('riwayats'));
    }

    public function konfirmasiPenyaluran($id_log)
    {
        $log = LogDistribusi::where('id', $id_log)->where('status', 'Menunggu')->first();

        if ($log) {
            $log->update(['status' => 'Selesai']);
            PenerimaBantuan::where('id', $log->id_penerima)
                ->update(['tanggal_pencairan_terakhir' => $log->tanggal_diterima]);
        }

        return redirect()->back();
    }

    public function cetakPdf(Request $request)
    {
        if (!$request->filled('periode')) {
            abort(400, 'Periode tidak valid!');
        }

        $periode = $request->periode;
        $periode_obj = date_create_from_format('Y-m', $periode);
        if (!$periode_obj) {
            abort(400, 'Format periode tidak valid!');
        }

        $bulan_indonesia = [
            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
            'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
            'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
        ];
        $bulan_eng = date_format($periode_obj, 'F');
        $tahun = date_format($periode_obj, 'Y');
        $bulan_tahun = $bulan_indonesia[$bulan_eng] . ' ' . $tahun;

        $riwayats = LogDistribusi::with(['penerimaBantuan.tempat_ibadah'])
            ->whereRaw("DATE_FORMAT(tanggal_diterima, '%Y-%m') = ?", [$periode])
            ->orderBy('tanggal_diterima', 'desc')
            ->get();

        if ($riwayats->isEmpty()) {
            abort(404, 'Data riwayat tidak ditemukan untuk periode ini!');
        }

        $tgl_sekarang_indo = date('d') . ' ' . $bulan_indonesia[date('F')] . ' ' . date('Y');

        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Penyaluran Bantuan - ' . $bulan_tahun . '</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        h1 { text-align: center; font-size: 18px; text-transform: uppercase; margin-bottom: 5px; }
        h3 { text-align: center; font-size: 14px; margin-top: 0; margin-bottom: 20px; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Riwayat Penyaluran Bantuan</h1>
    <h3>Periode: ' . $bulan_tahun . '</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Terima</th>
                <th>Nama Kepala Keluarga</th>
                <th>NIK</th>
                <th>Alamat</th>
                <th>Penyalur</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

        $no = 1;
        foreach ($riwayats as $r) {
            $tgl = $r->tanggal_diterima->format('d-m-Y H:i');
            $nama_kk = $r->penerimaBantuan->nama_kepala_keluarga ?? 'Data Terhapus';
            $nik = $r->penerimaBantuan && $r->penerimaBantuan->nik_kepala_keluarga
                ? substr($r->penerimaBantuan->nik_kepala_keluarga, 0, 6) . '**********'
                : '-';
            $alamat = $r->penerimaBantuan->alamat ?? '-';
            $penyalur = $r->penerimaBantuan && $r->penerimaBantuan->tempat_ibadah
                ? $r->penerimaBantuan->tempat_ibadah->nama_tempat
                : '-';

            $html .= '
            <tr>
                <td style="text-align: center;">' . $no . '</td>
                <td>' . $tgl . '</td>
                <td>' . e($nama_kk) . '</td>
                <td>' . $nik . '</td>
                <td>' . e($alamat) . '</td>
                <td>' . e($penyalur) . '</td>
                <td style="text-align: center;">' . $r->status . '</td>
            </tr>';
            $no++;
        }

        $html .= '
        </tbody>
    </table>
    <div style="margin-top: 50px; text-align: right; padding-right: 50px;">
        Pontianak, ' . $tgl_sekarang_indo . '<br><br><br><br>
        <strong>Administrator</strong>
    </div>
</body>
</html>';

        $pdf = Pdf::loadHTML($html)->setPaper('A4', 'landscape');
        $filename = "Laporan_Penyaluran_" . $periode . ".pdf";

        return $pdf->stream($filename);
    }
}
