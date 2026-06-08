<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Penerima Bantuan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl overflow-hidden shadow-xl sm:rounded-3xl border border-slate-200/50 dark:border-slate-800/50">
                <div class="p-6 md:p-8 text-slate-900 dark:text-slate-100">
                    @if(session('success'))
                        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-2xl mb-8 flex items-center shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Header & Filter -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                        <div>
                            <h3 class="text-2xl font-bold font-outfit text-slate-900 dark:text-white tracking-tight">Data Penerima Bantuan</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola dan tinjau data penerima bantuan di wilayah Anda.</p>
                        </div>
                        <div class="w-full sm:w-auto flex flex-wrap items-center gap-3">
                            @if(Auth::user()->role === 'admin')
                            <form method="GET" action="" class="flex items-center w-full sm:w-auto">
                                <div class="relative w-full sm:w-64">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                    </div>
                                    <select name="filter_penyalur" id="filter_penyalur" class="bg-white dark:bg-slate-800/50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-9 pr-8 py-2.5 dark:border-slate-600 dark:placeholder-slate-400 dark:text-white shadow-sm transition-colors cursor-pointer appearance-none" onchange="this.form.submit()">
                                        <option value="">Semua Penyalur</option>
                                        @foreach($ibadahs as $ib)
                                            <option value="{{ $ib->id }}" {{ request('filter_penyalur') == $ib->id ? 'selected' : '' }}>{{ Str::limit($ib->nama_tempat, 25) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </form>
                            @endif
                            @if(Auth::user()->role === 'user')
                            <button type="button" onclick="opencetakModal()" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-medium shadow-lg shadow-blue-500/30 hover:-translate-y-0.5 transition-all flex items-center text-sm whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                Cetak Kupon
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Edit Form Panel -->
                    <div id="panel-edit" class="hidden bg-amber-50/80 dark:bg-amber-900/20 backdrop-blur-md border border-amber-200/50 dark:border-amber-700/50 rounded-3xl p-6 md:p-8 mb-8 shadow-lg transition-all duration-300">
                        <div class="flex items-center mb-6 border-b border-amber-200/50 dark:border-amber-700/50 pb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 mr-4 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2-2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold font-outfit text-slate-900 dark:text-white tracking-tight">Edit Data Penerima Bantuan</h3>
                        </div>
                        <form action="{{ route('map.edit') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @csrf
                            <input type="hidden" name="id" id="edit-id">
                            <input type="hidden" name="type" id="edit-type" value="penerima">
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Kepala Keluarga</label>
                                <input type="text" name="nama" id="edit-nama" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">NIK</label>
                                <input type="text" name="nik_kepala_keluarga" id="edit-nik" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nomor KK</label>
                                <input type="text" name="nomor_kk" id="edit-kk" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Jumlah Tanggungan</label>
                                <input type="number" name="jumlah_tanggungan" id="edit-tanggungan" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50" min="0">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tempat Ibadah / Penyalur</label>
                                <select name="id_tempat_ibadah" id="edit-ibadah" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50">
                                    <option value="">- Pilih Tempat Ibadah -</option>
                                    @foreach($ibadahs as $ib)
                                        <option value="{{ $ib->id }}">{{ $ib->nama_tempat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Lengkap</label>
                                <input type="text" name="alamat" id="edit-alamat" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50" required>
                            </div>
                            
                            <div class="col-span-1 md:col-span-2 mt-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Foto Saat Ini (Centang untuk menghapus):</label>
                                <div id="current-photos-container" class="bg-white/60 dark:bg-slate-800/60 backdrop-blur-sm p-4 border border-slate-200/50 dark:border-slate-700/50 rounded-2xl flex gap-4 flex-wrap">
                                    <!-- Foto existing dimuat lewat JS -->
                                </div>
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Upload Foto Baru (opsional)</label>
                                <input type="file" name="foto[]" id="edit-foto" accept="image/*" multiple class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/50 dark:file:text-indigo-300 transition-colors bg-white/50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-1">
                            </div>

                            <div class="col-span-1 md:col-span-2 flex justify-end space-x-4 mt-6 pt-4 border-t border-amber-200/50 dark:border-amber-700/50">
                                <button type="button" onclick="closeEdit()" class="px-6 py-2.5 bg-white/80 dark:bg-slate-700/80 backdrop-blur-sm border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-full hover:bg-slate-50 dark:hover:bg-slate-600 font-medium transition-all shadow-sm">Batal</button>
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white rounded-full font-medium shadow-lg shadow-amber-500/30 hover:-translate-y-0.5 transition-all">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>

                    <!-- Cards Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @forelse($penerimas as $index => $r)
                            @php
                                $logBulanIni = App\Models\LogDistribusi::where('id_penerima', $r->id)
                                    ->whereMonth('tanggal_diterima', now()->month)
                                    ->whereYear('tanggal_diterima', now()->year)
                                    ->first();
                                
                                if ($logBulanIni) {
                                    if ($logBulanIni->status == 'Menunggu') {
                                        $status_badge = "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300'>Menunggu Verifikasi</span>";
                                    } else {
                                        $status_badge = "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'>Selesai</span>";
                                    }
                                } else {
                                    if ($r->tanggal_pencairan_terakhir && $r->tanggal_pencairan_terakhir->format('Y-m') === now()->format('Y-m')) {
                                        $status_badge = "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'>Selesai</span>";
                                    } else {
                                        $status_badge = "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'>Belum Cair</span>";
                                    }
                                }

                                $penyalur = $r->tempat_ibadah ? $r->tempat_ibadah->nama_tempat : '-';
                                $jarak = $r->jarak_ke_penyalur !== null ? round($r->jarak_ke_penyalur) . " m" : "-";
                                $tgl_format = $r->tanggal_pencairan_terakhir ? $r->tanggal_pencairan_terakhir->format('d M Y H:i') : "-";
                                $dokumen_pdf = $r->dokumen_laporan ? "<a href='".asset('storage/uploads/'.$r->dokumen_laporan)."' target='_blank' class='text-indigo-600 dark:text-indigo-400 hover:underline inline-flex items-center'><svg class='w-4 h-4 mr-1' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'></path></svg>Lihat PDF</a>" : "-";
                                $id_laporan = $r->id_laporan ?? '-';
                                
                                $foto_html = "-";
                                if(!empty($r->foto)) {
                                    $fotos = explode(',', $r->foto);
                                    $links = [];
                                    foreach($fotos as $idx => $f) {
                                        $links[] = "<a href='".asset('storage/uploads/'.$f)."' target='_blank' class='text-indigo-600 dark:text-indigo-400 hover:underline'>Foto ".($idx+1)."</a>";
                                    }
                                    $foto_html = implode(' <span class="text-gray-300 dark:text-gray-600">|</span> ', $links);
                                }

                                $status_persetujuan_badge = "";
                                if ($r->status_persetujuan === 'disetujui') {
                                    $status_persetujuan_badge = "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'>Disetujui</span>";
                                } elseif ($r->status_persetujuan === 'ditolak') {
                                    $status_persetujuan_badge = "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'>Ditolak</span><div class='text-red-600 dark:text-red-400 text-xs mt-1 font-medium italic'>Alasan: " . ($r->alasan_penolakan ?? '-') . "</div>";
                                } else {
                                    $status_persetujuan_badge = "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300'>Menunggu Persetujuan</span>";
                                }
                            @endphp

                            <div class="bg-white/60 dark:bg-slate-800/60 backdrop-blur-md border border-slate-200/80 dark:border-slate-700/80 rounded-3xl shadow-lg shadow-slate-200/50 dark:shadow-slate-900/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                                <div class="bg-indigo-50/80 dark:bg-indigo-900/30 px-6 py-5 border-b border-indigo-100 dark:border-indigo-800/30 flex justify-between items-start gap-4 transition-colors">
                                    <div class="flex-grow">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-800 dark:text-indigo-300 py-1 px-2.5 rounded-lg">#{{ $index + 1 }}</span>
                                            <h4 class="font-bold font-outfit text-slate-900 dark:text-white text-xl">{{ $r->nama_kepala_keluarga }}</h4>
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400 font-medium">NIK: <span class="text-slate-700 dark:text-slate-300">{{ $r->nik_kepala_keluarga }}</span> | KK: <span class="text-slate-700 dark:text-slate-300">{{ $r->nomor_kk }}</span></div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2 shrink-0">
                                        {!! str_replace(['rounded-full', 'px-2.5'], ['rounded-lg', 'px-3'], $status_persetujuan_badge) !!}
                                        {!! str_replace(['rounded-full', 'px-2.5'], ['rounded-lg', 'px-3'], $status_badge) !!}
                                    </div>
                                </div>
                                
                                <div class="p-6 flex-grow text-sm space-y-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                        <div class="flex flex-col">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium mb-1">Tanggungan</span>
                                            <span class="text-slate-900 dark:text-slate-200 font-semibold">{{ $r->jumlah_tanggungan }} Orang</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium mb-1">Penyalur Bantuan</span>
                                            <span class="text-slate-900 dark:text-slate-200 font-semibold">{{ $penyalur }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium mb-1">Jarak (Estimasi)</span>
                                            <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $jarak }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium mb-1">Tgl Cair Terakhir</span>
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ $tgl_format }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="border-t border-slate-100 dark:border-slate-700/50 pt-4">
                                        <span class="text-slate-500 dark:text-slate-400 font-medium mb-1 block">Alamat</span>
                                        <span class="text-slate-900 dark:text-slate-200 leading-relaxed block">{{ $r->alamat }}</span>
                                        <span class="text-xs text-slate-400 dark:text-slate-500 mt-2 font-mono bg-slate-100 dark:bg-slate-900/50 py-1 px-2 rounded w-max inline-block border border-slate-200 dark:border-slate-800">Koordinat: {{ $r->lat }}, {{ $r->lng }}</span>
                                    </div>

                                    <div class="border-t border-slate-100 dark:border-slate-700/50 pt-4 grid grid-cols-2 gap-6">
                                        <div class="flex flex-col">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium mb-1.5">Dokumen</span>
                                            <span class="font-medium">{!! $dokumen_pdf !!}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-slate-500 dark:text-slate-400 font-medium mb-1.5">Foto Bukti</span>
                                            <span class="font-medium">{!! $foto_html !!}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-200/50 dark:border-slate-700/50 flex justify-end gap-3 flex-wrap">
                                    @if (Auth::user()->role === 'admin' && $r->status_persetujuan !== 'disetujui' && $r->status_persetujuan !== 'ditolak')
                                        <form action="{{ route('persetujuan.update', $r->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status_persetujuan" value="disetujui">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-emerald-200 dark:border-emerald-800/50 text-sm font-semibold rounded-xl text-emerald-800 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 focus:outline-none transition-all shadow-sm hover:-translate-y-0.5" title="Setujui">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Setujui
                                            </button>
                                        </form>
                                        <button type="button" class="inline-flex items-center px-4 py-2 border border-rose-200 dark:border-rose-800/50 text-sm font-semibold rounded-xl text-rose-800 dark:text-rose-300 bg-rose-50 dark:bg-rose-900/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 focus:outline-none transition-all shadow-sm hover:-translate-y-0.5" title="Tolak" onclick="openTolakModal({{ $r->id }})">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Tolak
                                        </button>
                                    @endif

                                    <button class='inline-flex items-center px-4 py-2 border border-amber-200 dark:border-amber-700/50 text-sm font-semibold rounded-xl text-amber-800 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/30 hover:bg-amber-100 dark:hover:bg-amber-900/50 focus:outline-none transition-all shadow-sm hover:-translate-y-0.5' title='Edit' onclick='openEdit({{ $r->id }}, "{{ addslashes($r->nama_kepala_keluarga) }}", "{{ addslashes($r->alamat) }}", "{{ addslashes($r->nik_kepala_keluarga) }}", "{{ addslashes($r->nomor_kk) }}", "{{ $r->jumlah_tanggungan }}", "{{ $r->id_tempat_ibadah }}", `{{ $r->foto }}`)'>
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </button>
                                    
                                    <a href='{{ route("map.delete", ["type" => "penerima", "id" => $r->id]) }}' class='inline-flex items-center px-4 py-2 border border-rose-200 dark:border-rose-700/50 text-sm font-semibold rounded-xl text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-900/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 focus:outline-none transition-all shadow-sm hover:-translate-y-0.5' title='Hapus' onclick='return confirm("Hapus data penerima ini?")'>
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-1 lg:col-span-2 py-16 text-center bg-white/40 dark:bg-slate-800/40 backdrop-blur-sm rounded-3xl border border-slate-200/50 dark:border-slate-700/50 shadow-sm">
                                <div class="w-20 h-20 mx-auto bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 font-bold text-lg">Data penerima bantuan tidak ditemukan.</p>
                            </div>
                        @endforelse
                    </div>

                    <script>
                        function openEdit(id, nama, alamat, nik, kk, tanggungan, ibadah_id, fotoStr) {
                            var panel = document.getElementById('panel-edit');
                            panel.classList.remove('hidden');
                            
                            document.getElementById('edit-id').value = id;
                            document.getElementById('edit-nama').value = nama;
                            document.getElementById('edit-alamat').value = alamat;
                            document.getElementById('edit-nik').value = nik || '';
                            document.getElementById('edit-kk').value = kk || '';
                            document.getElementById('edit-tanggungan').value = tanggungan || '';
                            document.getElementById('edit-ibadah').value = ibadah_id || '';
                            
                            // Render current photos with delete checkbox
                            const container = document.getElementById('current-photos-container');
                            
                            if(fotoStr && fotoStr.trim() !== '') {
                                const fotos = fotoStr.split(',');
                                let htmlStr = '';
                                fotos.forEach(f => {
                                    const url = '{{ asset("storage/uploads/") }}/' + f;
                                    htmlStr += `
                                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden w-24 flex flex-col shadow-sm">
                                            <img src="${url}" class="w-24 h-24 object-cover bg-slate-100 dark:bg-slate-900">
                                            <label class="text-xs font-semibold text-center py-2 bg-slate-50 dark:bg-slate-800 cursor-pointer hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-600 transition-colors border-t border-slate-200 dark:border-slate-700 block">
                                                <input type="checkbox" name="hapus_foto[]" value="${f}" class="mr-1 rounded border-slate-300 text-rose-600 focus:ring-rose-500"> Hapus
                                            </label>
                                        </div>
                                    `;
                                });
                                container.innerHTML = htmlStr;
                            } else {
                                container.innerHTML = '<span class="text-sm text-slate-400 dark:text-slate-500 italic font-medium">Belum ada foto yang diunggah.</span>';
                            }

                            panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        
                        function closeEdit() { 
                            document.getElementById('panel-edit').classList.add('hidden'); 
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Penolakan -->
    <div id="tolakModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-md z-50 justify-center items-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900 dark:text-white">Alasan Penolakan</h3>
                <button onclick="closeTolakModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 bg-white dark:bg-slate-700 p-1 rounded-full shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="formTolak" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="status_persetujuan" value="ditolak">
                
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilih alasan penolakan berkas pengajuan ini:</label>
                    <select name="alasan_penolakan" required class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50 w-full">
                        <option value="">-- Pilih Alasan --</option>
                        <option value="Data tidak valid / fiktif">Data tidak valid / fiktif</option>
                        <option value="Sudah terdaftar sebelumnya">Sudah terdaftar sebelumnya</option>
                        <option value="Dokumen investigasi tidak lengkap atau salah">Dokumen investigasi tidak lengkap atau salah</option>
                        <option value="Kondisi ekonomi dianggap masih mampu">Kondisi ekonomi dianggap masih mampu</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeTolakModal()" class="px-5 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-full hover:bg-slate-50 dark:hover:bg-slate-600 font-medium transition-colors shadow-sm">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white rounded-full font-medium shadow-lg shadow-rose-500/30 transition-all">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openTolakModal(id) {
            document.getElementById('formTolak').action = "/persetujuan/" + id;
            document.getElementById('tolakModal').classList.remove('hidden');
            document.getElementById('tolakModal').classList.add('flex');
        }
        function closeTolakModal() {
            document.getElementById('tolakModal').classList.remove('flex');
            document.getElementById('tolakModal').classList.add('hidden');
        }
    </script>
    <!-- Modal Cetak Kupon Massal -->
    <div id="cetakModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-md z-50 justify-center items-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all flex flex-col max-h-[90vh] border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/80 dark:bg-slate-800/50 backdrop-blur-sm">
                <h3 class="text-xl font-bold font-outfit text-slate-900 dark:text-white flex items-center tracking-tight">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    Pilih Kupon untuk Dicetak
                </h3>
                <button onclick="closecetakModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 bg-white dark:bg-slate-700 p-1.5 rounded-full shadow-sm transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="formcetak" action="{{ route('kupon.cetak') }}" method="POST" target="_blank" class="flex flex-col flex-grow overflow-hidden">
                @csrf
                <div class="p-6 bg-white dark:bg-slate-800 overflow-y-auto flex-grow">
                    <div class="mb-5 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                        <span class="text-sm text-slate-600 dark:text-slate-400 font-medium">Pilih penerima bantuan (yang sudah disetujui) yang kuponnya ingin dicetak.</span>
                        <button type="button" onclick="selectAllKupon()" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-semibold bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 px-4 py-2 rounded-xl transition-colors shadow-sm whitespace-nowrap">Pilih Semua</button>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($penerimas->where('status_persetujuan', 'disetujui') as $p)
                            <label class="flex items-center p-4 border border-slate-200 dark:border-slate-700 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition-all shadow-sm">
                                <input type="checkbox" name="penerima_ids[]" value="{{ $p->id }}" class="kupon-checkbox w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 dark:bg-slate-800 dark:border-slate-600 dark:checked:bg-indigo-500">
                                <div class="ml-4 flex-grow">
                                    <span class="block text-sm font-bold text-slate-900 dark:text-white">{{ $p->nama_kepala_keluarga }}</span>
                                    <span class="block text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">NIK: {{ substr($p->nik_kepala_keluarga ?? '', 0, 6) }}********** <span class="mx-1 text-slate-300 dark:text-slate-600">|</span> Penyalur: <span class="text-slate-700 dark:text-slate-300">{{ $p->tempat_ibadah ? $p->tempat_ibadah->nama_tempat : '-' }}</span></span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3 bg-slate-50/80 dark:bg-slate-800/50 backdrop-blur-sm">
                    <button type="button" onclick="closecetakModal()" class="px-5 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-full hover:bg-slate-50 dark:hover:bg-slate-600 font-medium transition-all shadow-sm">Batal</button>
                    <button type="submit" onclick="closecetakModal()" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-full font-medium shadow-lg shadow-blue-500/30 hover:-translate-y-0.5 transition-all flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        Cetak Terpilih
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function opencetakModal() {
            document.getElementById('cetakModal').classList.remove('hidden');
            document.getElementById('cetakModal').classList.add('flex');
        }
        function closecetakModal() {
            document.getElementById('cetakModal').classList.remove('flex');
            document.getElementById('cetakModal').classList.add('hidden');
        }
        function selectAllKupon() {
            const checkboxes = document.querySelectorAll('.kupon-checkbox');
            const allChecked = Array.from(checkboxes).every(c => c.checked);
            checkboxes.forEach(c => c.checked = !allChecked);
        }
    </script>
</x-app-layout>
