<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenerimaBantuan;
use Illuminate\Support\Facades\Auth;

class PersetujuanController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Only fetch 'menunggu' status to show in the approval table
        $penerimas = PenerimaBantuan::with('tempat_ibadah')->where('status_persetujuan', 'menunggu')->get();
        return view('persetujuan', compact('penerimas'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status_persetujuan' => 'required|in:disetujui,ditolak',
            'alasan_penolakan' => 'nullable|string'
        ]);

        $penerima = PenerimaBantuan::findOrFail($id);
        $penerima->status_persetujuan = $request->status_persetujuan;
        if ($request->status_persetujuan === 'ditolak') {
            $penerima->alasan_penolakan = $request->alasan_penolakan;
        } else {
            $penerima->alasan_penolakan = null;
        }
        $penerima->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui!');
    }
}
