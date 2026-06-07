<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Penyerahan Bantuan</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; margin-top: 0; }
        .info-box { background: #eef2f5; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .info-box p { margin: 5px 0; }
        .info-box strong { color: #2c3e50; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; color: #555; }
        input[type="password"], input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; background: #28a745; color: white; border: none; padding: 12px; font-size: 16px; font-weight: bold; border-radius: 6px; cursor: pointer; transition: 0.3s; }
        button:hover { background: #218838; }
        .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; text-align: center; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-success { background: #28a745; color: white; padding: 5px 10px; border-radius: 20px; font-size: 14px; font-weight: bold; display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Konfirmasi Penyerahan Bantuan</h2>

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if(isset($pesan_sukses))
        <div class="alert alert-success">Berhasil! Bukti penyerahan telah disimpan dan menunggu persetujuan Admin.</div>
    @endif

    <div class="info-box">
        <p><strong>Penerima:</strong> {{ $warga->nama_kepala_keluarga }}</p>
        <p><strong>NIK:</strong> {{ substr($warga->nik_kepala_keluarga ?? '', 0, 6) }}**********</p>
        <p><strong>Alamat:</strong> {{ $warga->alamat }}</p>
    </div>

    @if($sudah_cair)
        <div style="text-align: center; margin-bottom: 20px;">
            <div class="badge-success">✔ Sudah Dicairkan Bulan Ini</div>
            <p style="color: #666; font-size: 14px; margin-top: 10px;">Bantuan untuk penerima ini telah diproses pada <strong>{{ $log->tanggal_diterima->format('d F Y H:i') }}</strong>.</p>
        </div>
    @else
        <form id="konfirmasiForm" action="{{ route('konfirmasi.process', ['id' => $warga->id, 'signature' => request('signature')]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="foto_penyerahan">Unggah Bukti Foto Penyerahan</label>
                <input type="file" id="foto_penyerahan" name="foto_penyerahan" accept="image/*" required>
            </div>

            <button type="submit" id="btnSubmit">Konfirmasi Pencairan</button>
        </form>
    @endif

</div>

</body>
</html>
