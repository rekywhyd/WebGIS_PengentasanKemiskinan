<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Penyaluran Bantuan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Description & Filter Card -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl overflow-hidden shadow-xl sm:rounded-3xl border border-slate-200/50 dark:border-slate-800/50 p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h3 class="text-2xl font-bold font-outfit text-slate-900 dark:text-white mb-1 tracking-tight">Daftar Pencairan Bantuan</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                            Catatan waktu dan identitas penerima yang telah berhasil mencairkan bantuan melalui pemindaian QR Code.
                        </p>
                    </div>

                    <form method="GET" action="" class="flex flex-wrap items-center gap-3">
                        <div class="relative w-full sm:w-auto">
                            <input type="month" name="filter_periode" id="filter_periode" 
                                value="{{ request('filter_periode', '') }}"
                                onchange="this.form.submit()"
                                class="bg-white dark:bg-slate-800/50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full px-4 py-2.5 dark:border-slate-600 dark:placeholder-slate-400 dark:text-white shadow-sm transition-colors cursor-pointer appearance-none">
                        </div>
                        
                        @if(request('filter_periode'))
                            <a href="{{ route('riwayat.cetakPdf', ['periode' => request('filter_periode')]) }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-xl font-medium shadow-lg shadow-emerald-500/30 hover:-translate-y-0.5 transition-all text-sm whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak PDF
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($riwayats as $r)
                    @php
                        $nama_kk = $r->penerimaBantuan->nama_kepala_keluarga ?? 'Data Terhapus';
                        $nik = $r->penerimaBantuan && $r->penerimaBantuan->nik_kepala_keluarga
                            ? substr($r->penerimaBantuan->nik_kepala_keluarga, 0, 6) . '**********'
                            : '-';
                        $alamat = $r->penerimaBantuan->alamat ?? '-';
                        $penyalur = $r->penerimaBantuan && $r->penerimaBantuan->tempat_ibadah
                            ? $r->penerimaBantuan->tempat_ibadah->nama_tempat : '-';
                        $isMenunggu = $r->status == 'Menunggu';
                    @endphp
                    
                    <div class="bg-white/60 dark:bg-slate-800/60 backdrop-blur-md border {{ $isMenunggu ? 'border-amber-300/50 dark:border-amber-700/50' : 'border-slate-200/80 dark:border-slate-700/80' }} rounded-3xl shadow-lg shadow-slate-200/50 dark:shadow-slate-900/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                        
                        <!-- Card Header -->
                        <div class="px-6 py-5 border-b {{ $isMenunggu ? 'border-amber-100 dark:border-amber-800/30 bg-amber-50/80 dark:bg-amber-900/20' : 'border-indigo-100 dark:border-indigo-800/30 bg-indigo-50/80 dark:bg-indigo-900/30' }} flex justify-between items-center transition-colors">
                            <div class="flex items-center text-sm font-semibold text-slate-900 dark:text-white">
                                <svg class="w-4 h-4 mr-2 {{ $isMenunggu ? 'text-amber-500' : 'text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $r->tanggal_diterima->format('d M Y, H:i') }}
                            </div>
                            <span class="px-3 py-1.5 text-xs font-bold rounded-lg {{ $isMenunggu ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' }}">
                                {{ $r->status }}
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-grow space-y-4 text-sm">
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold mb-1">Nama Kepala Keluarga</p>
                                <p class="text-base font-bold font-outfit text-slate-900 dark:text-white">{{ $nama_kk }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold mb-1">NIK</p>
                                    <p class="text-slate-800 dark:text-slate-200 font-mono font-medium">{{ $nik }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold mb-1">Penyalur</p>
                                    <p class="text-slate-800 dark:text-slate-200 truncate font-medium" title="{{ $penyalur }}">{{ $penyalur }}</p>
                                </div>
                            </div>
                            <div class="border-t border-slate-100 dark:border-slate-700/50 pt-3">
                                <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold mb-1">Alamat</p>
                                <p class="text-slate-800 dark:text-slate-300 line-clamp-2 leading-relaxed" title="{{ $alamat }}">{{ $alamat }}</p>
                            </div>

                            @if(!empty($r->foto_penyerahan))
                                <div class="pt-3 border-t border-slate-100 dark:border-slate-700/50">
                                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold mb-2">Bukti Foto Penyerahan</p>
                                    <a href="{{ asset('storage/uploads/' . $r->foto_penyerahan) }}" target="_blank" class="block rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900 hover:opacity-90 transition-all shadow-inner aspect-video relative group">
                                        <img src="{{ asset('storage/uploads/' . $r->foto_penyerahan) }}" class="w-full h-full object-cover" alt="Bukti Penyerahan">
                                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all backdrop-blur-[2px]">
                                            <div class="bg-white/20 p-2 rounded-full backdrop-blur-md">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        @if($isMenunggu)
                            <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-200/50 dark:border-slate-700/50 flex justify-end">
                                <a href="{{ route('riwayat.konfirmasi', $r->id) }}" 
                                   onclick="return confirm('Setujui bukti ini dan selesaikan penyaluran?')" 
                                   class="w-full flex justify-center items-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 hover:-translate-y-0.5 transition-all shadow-lg shadow-indigo-500/30">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Setujui Penyaluran
                                </a>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 px-4 bg-white/40 dark:bg-slate-800/40 backdrop-blur-sm rounded-3xl border border-slate-200/50 dark:border-slate-700/50 shadow-sm text-center">
                        <div class="bg-slate-100 dark:bg-slate-700 rounded-full w-20 h-20 flex items-center justify-center mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-600 dark:text-slate-400 mb-1">Belum ada riwayat pencairan</h3>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
