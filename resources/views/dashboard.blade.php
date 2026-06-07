<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Pemetaan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl overflow-hidden shadow-2xl sm:rounded-3xl border border-slate-200/50 dark:border-slate-800/50">
                <div class="p-6 sm:p-10 text-slate-900 dark:text-slate-100">
                    
                    <!-- Map Interface Start -->
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <style>
                        #map { z-index: 1; }
                        .leaflet-popup-content-wrapper { border-radius: 1rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(8px); }
                        .leaflet-popup-content { text-align: center; color: #0f172a; font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; font-weight: 500;}
                        .popup-slider { width: 100%; margin-top: 10px; accent-color: #6366f1; }
                        
                        /* Crosshair when adding point */
                        .mode-tambah .leaflet-container { cursor: crosshair !important; }
                        
                        /* Custom form inputs to match Tailwind forms plugin if not installed */
                        .form-input { display: block; width: 100%; border-radius: 0.75rem; border: 1px solid #e2e8f0; padding: 0.75rem 1rem; font-size: 0.875rem; line-height: 1.25rem; transition: all 0.2s ease-in-out; background-color: rgba(255, 255, 255, 0.5); color: #0f172a; backdrop-filter: blur(8px); }
                        .form-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15); background-color: #fff; }
                        .dark .form-input { background-color: rgba(30, 41, 59, 0.5); border-color: #334155; color: #f8fafc; }
                        .dark .form-input:focus { border-color: #818cf8; box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.15); background-color: #1e293b; }
                    </style>

                    @php
                        $total_penerima = $penerimas->count();
                        $sudah_cair = 0;
                        $belum_cair = 0;
                        $current_month = now()->format('Y-m');

                        foreach($penerimas as $p) {
                            if ($p->tanggal_pencairan_terakhir && $p->tanggal_pencairan_terakhir->format('Y-m') === $current_month) {
                                $sudah_cair++;
                            } else {
                                $belum_cair++;
                            }
                        }
                    @endphp

                    <!-- Top Action Bar -->
                    <div class="flex flex-col lg:flex-row lg:items-stretch gap-6 mb-8">
                        <!-- Add Button -->
                        <button id="btn-toggle-form" class="w-full lg:w-1/4 flex-shrink-0 flex items-center justify-center px-6 py-4 border border-transparent text-base font-semibold rounded-2xl shadow-lg shadow-indigo-500/30 text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 hover:-translate-y-1 transition-all duration-300">
                            <svg id="btn-icon-plus" class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <svg id="btn-icon-minus" class="w-6 h-6 mr-2 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            <span id="btn-text">Tambah Data Baru</span>
                        </button>
                        
                        <!-- Stats Panel -->
                        <div class="flex-grow flex bg-white/50 dark:bg-slate-800/50 backdrop-blur-md rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden divide-x divide-slate-200/50 dark:divide-slate-700/50">
                            <div class="flex-1 p-4 flex flex-col justify-center items-center hover:bg-slate-50/50 dark:hover:bg-slate-800/80 transition-colors">
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1 text-center">Total Penerima</span>
                                <span class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">{{ $total_penerima }}</span>
                            </div>
                            <div class="flex-1 p-4 flex flex-col justify-center items-center bg-gradient-to-b from-emerald-50/50 to-emerald-100/50 dark:from-emerald-900/10 dark:to-emerald-800/10 hover:from-emerald-100/50 hover:to-emerald-200/50 transition-colors">
                                <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1 text-center">Sudah Cair</span>
                                <span class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $sudah_cair }}</span>
                            </div>
                            <div class="flex-1 p-4 flex flex-col justify-center items-center bg-gradient-to-b from-rose-50/50 to-rose-100/50 dark:from-rose-900/10 dark:to-rose-800/10 hover:from-rose-100/50 hover:to-rose-200/50 transition-colors">
                                <span class="text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-widest mb-1 text-center">Belum Cair</span>
                                <span class="text-3xl font-extrabold text-rose-600 dark:text-rose-400">{{ $belum_cair }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Add Form Container -->
                    <div id="form-container" class="hidden bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl rounded-3xl border border-slate-200 dark:border-slate-700 p-6 md:p-8 mb-8 shadow-xl transition-all duration-500 transform origin-top">
                        <div class="flex items-center mb-6 border-b border-slate-200 dark:border-slate-700 pb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 mr-4 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold font-outfit text-slate-900 dark:text-white tracking-tight">Lengkapi Data Lokasi</h3>
                        </div>
                        
                        <input type="hidden" id="mode" value="{{ Auth::user()->role === 'admin' ? 'ibadah' : 'penerima' }}">
                        <input type="hidden" id="id_laporan_warga" value="{{ $laporan_fill ? $laporan_fill->id : '' }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Extra Fields for Ibadah -->
                            <div id="fields-ibadah" class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6" style="display:none;">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Tempat Ibadah <span class="text-rose-500">*</span></label>
                                    <input type="text" id="nama_ibadah" class="form-input" placeholder="Contoh: Masjid Al-Ikhlas">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Jenis Tempat Ibadah <span class="text-rose-500">*</span></label>
                                    <select id="jenis_tempat_ibadah" class="form-input">
                                        <option value="">- Pilih Jenis Tempat Ibadah -</option>
                                        <option value="Masjid">Masjid</option>
                                        <option value="Gereja Katolik">Gereja Katolik</option>
                                        <option value="Gereja Protestan">Gereja Protestan</option>
                                        <option value="Vihara">Vihara</option>
                                        <option value="Pura">Pura</option>
                                        <option value="Klenteng">Klenteng</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Pengurus</label>
                                    <input type="text" id="nama_pengurus" class="form-input" placeholder="Masukkan nama pengurus">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Kontak Pengurus</label>
                                    <input type="text" id="kontak_pengurus" class="form-input" placeholder="Nomor telepon/HP">
                                </div>
                            </div>

                            <!-- Extra Fields for Penerima -->
                            <div id="fields-penerima" class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6" style="display:none;">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Kepala Keluarga <span class="text-rose-500">*</span></label>
                                    <input type="text" id="nama_kepala_keluarga" class="form-input" placeholder="Masukkan nama kepala keluarga" value="{{ $laporan_fill ? $laporan_fill->nama_calon : '' }}" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">NIK Kepala Keluarga <span class="text-rose-500">*</span></label>
                                    <input type="text" id="nik_kepala_keluarga" class="form-input" placeholder="16 digit NIK" maxlength="16" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nomor Kartu Keluarga <span class="text-rose-500">*</span></label>
                                    <input type="text" id="nomor_kk" class="form-input" placeholder="16 digit Nomor KK" maxlength="16" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Jumlah Tanggungan <span class="text-rose-500">*</span></label>
                                    <input type="number" id="jumlah_tanggungan" class="form-input" placeholder="Jumlah anggota keluarga" min="0" value="{{ $laporan_fill ? $laporan_fill->jumlah_tanggungan : '' }}" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">PDF Form Investigasi <span class="text-rose-500">*</span></label>
                                    <input type="file" id="dokumen_laporan" accept=".pdf" class="form-input !p-1.5" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Foto Rumah <span class="text-slate-400 font-normal">(Opsional)</span></label>
                                    <input type="file" id="foto" accept="image/*" multiple class="form-input !p-1.5">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 p-4 bg-indigo-50/50 dark:bg-indigo-900/20 backdrop-blur-sm rounded-xl border border-indigo-100 dark:border-indigo-800/50 flex items-start">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-800 flex items-center justify-center shrink-0 mr-3 mt-0.5">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-sm text-indigo-800 dark:text-indigo-200 mt-1.5">
                                Setelah melengkapi formulir di atas, <strong>klik pada titik lokasi yang sesuai di peta</strong> di bawah untuk menyimpan data.
                            </p>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            @if(isset($laporan_fill))
                                document.getElementById('btn-toggle-form').click();
                                alert('Data laporan diisi otomatis. Silakan klik pada peta untuk menentukan lokasi rumah penerima bantuan, lengkapi data lainnya, lalu klik Simpan.');
                            @endif
                        });

                        document.getElementById('btn-toggle-form').addEventListener('click', function() {
                            var formContainer = document.getElementById('form-container');
                            var mapEl = document.getElementById('map');
                            var mode = document.getElementById('mode').value;
                            var btnText = document.getElementById('btn-text');
                            var btnIconPlus = document.getElementById('btn-icon-plus');
                            var btnIconMinus = document.getElementById('btn-icon-minus');
                            
                            if (formContainer.classList.contains('hidden')) {
                                formContainer.classList.remove('hidden');
                                btnText.innerText = 'Batal Tambah Data';
                                this.classList.replace('bg-indigo-600', 'bg-red-500');
                                this.classList.replace('hover:bg-indigo-700', 'hover:bg-red-600');
                                this.classList.replace('focus:ring-indigo-500', 'focus:ring-red-500');
                                btnIconPlus.classList.add('hidden');
                                btnIconMinus.classList.remove('hidden');
                                
                                if(mode === 'ibadah') {
                                    document.getElementById('fields-ibadah').style.display = 'grid';
                                    document.getElementById('fields-penerima').style.display = 'none';
                                } else {
                                    document.getElementById('fields-ibadah').style.display = 'none';
                                    document.getElementById('fields-penerima').style.display = 'grid';
                                }
                                mapEl.classList.add('mode-tambah');
                            } else {
                                formContainer.classList.add('hidden');
                                btnText.innerText = 'Tambah Data Baru';
                                this.classList.replace('bg-red-500', 'bg-indigo-600');
                                this.classList.replace('hover:bg-red-600', 'hover:bg-indigo-700');
                                this.classList.replace('focus:ring-red-500', 'focus:ring-indigo-500');
                                btnIconMinus.classList.add('hidden');
                                btnIconPlus.classList.remove('hidden');
                                
                                document.getElementById('fields-ibadah').style.display = 'none';
                                document.getElementById('fields-penerima').style.display = 'none';
                                mapEl.classList.remove('mode-tambah');
                            }
                        });
                    </script>

                    <!-- Edit Data Panel (Map specific) -->
                    <div id="panel-edit" class="hidden bg-amber-50/80 dark:bg-amber-900/20 backdrop-blur-md border border-amber-200/50 dark:border-amber-700/50 rounded-3xl p-6 md:p-8 mb-8 shadow-lg transition-all duration-300">
                        <div class="flex items-center mb-6 border-b border-amber-200/50 dark:border-amber-700/50 pb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 mr-4 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2-2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold font-outfit text-slate-900 dark:text-white tracking-tight">Edit Data Lokasi Peta</h3>
                        </div>
                        <form action="{{ route('map.edit') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @csrf
                            <input type="hidden" name="id" id="edit-id">
                            <input type="hidden" name="type" id="edit-type">
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama</label>
                                <input type="text" name="nama" id="edit-nama" class="form-input" placeholder="Nama">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Lengkap</label>
                                <input type="text" name="alamat" id="edit-alamat" class="form-input" placeholder="Alamat">
                            </div>
                            
                            <div class="col-span-1 md:col-span-2 flex justify-end space-x-4 mt-4">
                                <button type="button" onclick="closeEdit()" class="px-6 py-2.5 bg-white/80 dark:bg-slate-700/80 backdrop-blur-sm border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-full hover:bg-slate-50 dark:hover:bg-slate-600 font-medium transition-all shadow-sm">Batal</button>
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white rounded-full font-medium shadow-lg shadow-amber-500/30 hover:-translate-y-0.5 transition-all">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>

                    <!-- The Map Layout -->
                    <div class="{{ Auth::user()->role === 'user' ? 'flex flex-col lg:flex-row gap-6 items-stretch' : '' }}">
                        <!-- Map Column -->
                        <div class="{{ Auth::user()->role === 'user' ? 'flex-grow lg:w-2/3 flex flex-col' : '' }}">
                            <div class="rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 border border-slate-200/80 dark:border-slate-700/80 relative {{ Auth::user()->role === 'user' ? 'flex-grow min-h-[500px]' : '' }} bg-slate-100 dark:bg-slate-800 z-10">
                                <div id="map" class="{{ Auth::user()->role === 'user' ? 'absolute inset-0 w-full h-full' : 'w-full' }}" style="{{ Auth::user()->role !== 'user' ? 'height: 550px;' : '' }}"></div>
                            </div>
                        </div>

                        <!-- Info Column (User Only) -->
                        @if(Auth::user()->role === 'user')
                        <div class="w-full lg:w-1/3 flex flex-col">
                            <div class="bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl border border-slate-200/80 dark:border-slate-700/80 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 overflow-hidden flex flex-col h-full min-h-[500px] max-h-[500px]">
                                <div class="bg-indigo-50/80 dark:bg-indigo-900/40 backdrop-blur-sm px-6 py-5 border-b border-indigo-100 dark:border-indigo-800/50 flex justify-between items-center shrink-0">
                                    <h3 class="font-bold font-outfit text-xl text-slate-900 dark:text-white tracking-tight truncate">Tempat Ibadah Anda</h3>
                                </div>
                                <div class="p-6 flex-grow overflow-y-auto">
                                    @forelse($ibadahs as $index => $ib)
                                        <div class="mb-6 last:mb-0 bg-white dark:bg-slate-700/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-600 shadow-sm">
                                            <h4 class="font-bold text-lg text-slate-900 dark:text-white mb-2">{{ $ib->nama_tempat }}</h4>
                                            <span class="inline-block px-3 py-1.5 bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300 text-xs font-bold rounded-full mb-4">{{ $ib->jenis_tempat_ibadah ?? 'Lainnya' }}</span>
                                            
                                            <div class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                                                <div class="flex justify-between border-b border-slate-100 dark:border-slate-600/50 pb-1.5">
                                                    <span class="text-slate-500 font-medium">Pengurus</span>
                                                    <span class="text-slate-900 dark:text-slate-200">{{ $ib->nama_pengurus ?? '-' }}</span>
                                                </div>
                                                <div class="flex justify-between border-b border-slate-100 dark:border-slate-600/50 pb-1.5">
                                                    <span class="text-slate-500 font-medium">Kontak</span>
                                                    <span class="text-slate-900 dark:text-slate-200">{{ $ib->kontak_pengurus ?? '-' }}</span>
                                                </div>
                                                <div class="flex justify-between border-b border-slate-100 dark:border-slate-600/50 pb-1.5">
                                                    <span class="text-slate-500 font-medium">Radius</span>
                                                    <span class="text-slate-900 dark:text-slate-200 font-bold text-indigo-600 dark:text-indigo-400">{{ $ib->radius_meter }} m</span>
                                                </div>
                                                <div class="flex flex-col pt-1.5">
                                                    <span class="text-slate-500 font-medium mb-1">Alamat</span>
                                                    <span class="text-slate-900 dark:text-slate-200 leading-relaxed">{{ $ib->alamat }}</span>
                                                </div>
                                            </div>
                                            <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-600">
                                                <button onclick='openEditIbadah({{ $ib->id }}, "{{ addslashes($ib->nama_tempat) }}", "{{ addslashes($ib->alamat) }}", {{ $ib->radius_meter }}, "{{ addslashes($ib->jenis_tempat_ibadah) }}", "{{ addslashes($ib->nama_pengurus) }}", "{{ addslashes($ib->kontak_pengurus) }}")' class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-xl shadow-sm text-amber-800 bg-amber-100/80 hover:bg-amber-200 focus:outline-none transition-all duration-300">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    Edit Data Lokasi
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M12 20V4"></path></svg>
                                            </div>
                                            <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Belum ada data</h4>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">Anda belum menambahkan lokasi tempat ibadah.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script>
                        var map = L.map('map').setView([-0.02, 109.34], 14);
                        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
                            subdomains: 'abcd',
                            maxZoom: 20
                        }).addTo(map);

                        var listIbadah = [];
                        var listPenerima = [];

                        var iconIbadah = L.divIcon({
                            className: 'custom-div-icon',
                            html: `<div class="shadow-lg" style="background:#4f46e5; color:#fff; border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; border:3px solid #fff; font-size:16px;">🏛️</div>`,
                            iconSize: [36, 36],
                            iconAnchor: [18, 18]
                        });

                        // 1. Fungsi Edit UI
                        function openEdit(id, type, nama, alamat) {
                            var panel = document.getElementById('panel-edit');
                            panel.classList.remove('hidden');
                            document.getElementById('edit-id').value = id;
                            document.getElementById('edit-type').value = type;
                            document.getElementById('edit-nama').value = nama;
                            document.getElementById('edit-alamat').value = alamat;
                            
                            // Scroll smoothly to edit panel
                            panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        function closeEdit() { document.getElementById('panel-edit').classList.add('hidden'); }

                        // 2. Reverse Geocoding & Save
                        map.on('click', async function(e) {
                            const mode = document.getElementById('mode').value;
                            if(document.getElementById('form-container').classList.contains('hidden')) return; 
                            
                            let nama = '';
                            if (mode === 'ibadah') {
                                nama = document.getElementById('nama_ibadah').value;
                                if(!nama) return alert("Isi nama tempat ibadah dulu!");
                                if(!document.getElementById('jenis_tempat_ibadah').value) return alert("Pilih jenis tempat ibadah!");
                            } else {
                                nama = document.getElementById('nama_kepala_keluarga').value;
                                if(!nama) return alert("Isi nama kepala keluarga (wajib)!");
                                
                                const nik = document.getElementById('nik_kepala_keluarga').value;
                                if(!nik) return alert("Isi NIK Kepala Keluarga (wajib)!");
                                
                                const kk = document.getElementById('nomor_kk').value;
                                if(!kk) return alert("Isi Nomor KK (wajib)!");
                                
                                const tgg = document.getElementById('jumlah_tanggungan').value;
                                if(!tgg) return alert("Isi Jumlah Tanggungan (wajib)!");
                                
                                const docInput = document.getElementById('dokumen_laporan');
                                if(docInput.files.length === 0) return alert("PDF Form Investigasi wajib diunggah!");
                            }
                            
                            // Loading indicator
                            document.body.style.cursor = 'wait';
                            
                            try {
                                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${e.latlng.lat}&lon=${e.latlng.lng}`);
                                const data = await res.json();
                                const addr = data.address || {};
                                const alamatDetail = [addr.road, addr.village || addr.suburb, addr.city].filter(Boolean).join(", ");

                                let fd = new FormData();
                                fd.append('_token', '{{ csrf_token() }}');
                                fd.append('type', mode);
                                fd.append('nama', nama);
                                fd.append('lat', e.latlng.lat);
                                fd.append('lng', e.latlng.lng);
                                fd.append('alamat', alamatDetail || data.display_name);

                                let idLaporan = document.getElementById('id_laporan_warga').value;
                                if (idLaporan) {
                                    fd.append('id_laporan_warga', idLaporan);
                                }

                                if (mode === 'ibadah') {
                                    fd.append('jenis_tempat_ibadah', document.getElementById('jenis_tempat_ibadah').value);
                                    fd.append('nama_pengurus', document.getElementById('nama_pengurus').value);
                                    fd.append('kontak_pengurus', document.getElementById('kontak_pengurus').value);
                                } else {
                                    fd.append('nik_kepala_keluarga', document.getElementById('nik_kepala_keluarga').value);
                                    fd.append('nomor_kk', document.getElementById('nomor_kk').value);
                                    fd.append('jumlah_tanggungan', document.getElementById('jumlah_tanggungan').value);
                                    
                                    const docInput = document.getElementById('dokumen_laporan');
                                    if (docInput.files.length > 0) {
                                        fd.append('dokumen_laporan', docInput.files[0]);
                                    }

                                    const fotoInput = document.getElementById('foto');
                                    if (fotoInput.files.length > 0) {
                                        for (let i = 0; i < fotoInput.files.length; i++) {
                                            fd.append('foto[]', fotoInput.files[i]);
                                        }
                                    }
                                }
                                
                                const saveRes = await fetch('{{ route("map.save") }}', { method: 'POST', body: fd });
                                const saveData = await saveRes.json();
                                
                                if (saveData.status === 'error') {
                                    alert(saveData.message);
                                    document.body.style.cursor = 'default';
                                    return;
                                }

                                location.reload();
                            } catch (err) {
                                alert("Terjadi kesalahan saat menyimpan data lokasi.");
                                document.body.style.cursor = 'default';
                            }
                        });

                        // 3. Load Data Ibadah
                        @foreach($ibadahs as $ib)
                        (function(){
                            var rAwal = {{ $ib->radius_meter }};
                            var circle = L.circle([{{ $ib->lat }}, {{ $ib->lng }}], {
                                radius: rAwal, color: '#4f46e5', fillColor: '#4f46e5', fillOpacity: 0.1, weight: 2, dashArray: '5, 5'
                            }).addTo(map);

                            var marker = L.marker([{{ $ib->lat }}, {{ $ib->lng }}], {icon: iconIbadah}).addTo(map);
                            listIbadah.push({id: {{ $ib->id }}, nama: "{{ addslashes($ib->nama_tempat) }}", circle: circle, latlng: L.latLng({{ $ib->lat }}, {{ $ib->lng }})});

                            marker.bindPopup(`
                                <div class="text-center pb-2">
                                    <strong class="text-gray-800 text-base block mb-1">{{ addslashes($ib->nama_tempat) }}</strong>
                                    <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full">Radius: <span id="val_{{ $ib->id }}">${rAwal}</span> m</span>
                                </div>
                                @if(Auth::user()->role !== 'user')
                                <input type="range" class="popup-slider" min="100" max="3000" value="${rAwal}" oninput="updateRadius(${rAwal}, this.value, {{ $ib->id }})" onchange="saveRadius(this.value, {{ $ib->id }})">
                                @endif
                            `);
                        })();
                        @endforeach

                        // 4. Load Data Penerima Bantuan
                        @foreach($penerimas as $r)
                            @if($r->status_persetujuan !== 'ditolak')
                            var rm = L.circleMarker([{{ $r->lat }}, {{ $r->lng }}], {radius: 8, color: '#fff', fillOpacity: 1, weight: 2}).addTo(map);
                            rm.bindPopup(`<strong class="text-gray-800">{{ addslashes($r->nama_kepala_keluarga) }}</strong>`);
                            listPenerima.push({marker: rm, latlng: L.latLng({{ $r->lat }}, {{ $r->lng }}), statusPersetujuan: "{{ $r->status_persetujuan }}"});
                            @endif
                        @endforeach

                        // 5. Real-time Update Logic
                        function updateRadius(oldVal, newVal, id) {
                            document.getElementById('val_' + id).innerText = newVal;
                            listIbadah.forEach(item => { if(item.id === id) item.circle.setRadius(newVal); });
                            syncUI();
                        }

                        function saveRadius(val, id) {
                            let fd = new FormData();
                            fd.append('_token', '{{ csrf_token() }}');
                            fd.append('id', id); 
                            fd.append('radius', val);
                            fetch('{{ route("map.updateRadius") }}', { method: 'POST', body: fd });
                        }

                        function syncUI() {
                            listPenerima.forEach(penerima => {
                                let check = false;
                                listIbadah.forEach(ib => { if(penerima.latlng.distanceTo(ib.latlng) <= ib.circle.getRadius()) check = true; });
                                
                                let fillColor = check ? '#10b981' : '#ef4444'; // emerald-500 : red-500
                                if (penerima.statusPersetujuan !== 'disetujui') {
                                    fillColor = '#f59e0b'; // amber-500
                                }
                                penerima.marker.setStyle({fillColor: fillColor});
                            });
                        }

                        syncUI();
                    </script>
                    <!-- Map Interface End -->

                    @if(Auth::user()->role === 'user')
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <!-- Edit Ibadah Form Modal/Panel -->
                        <div id="panel-edit-ibadah" class="hidden mt-8 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-lg p-6">
                            <div class="flex items-center mb-5 border-b border-gray-100 dark:border-gray-700 pb-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Data Tempat Ibadah</h3>
                            </div>
                            <form action="{{ route('map.edit') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" id="edit-ibadah-id">
                                <input type="hidden" name="type" id="edit-ibadah-type" value="ibadah">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Tempat</label>
                                        <input type="text" name="nama" id="edit-ibadah-nama" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Tempat Ibadah</label>
                                        <select name="jenis_tempat_ibadah" id="edit-ibadah-jenis" class="form-input">
                                            <option value="">- Pilih Jenis -</option>
                                            <option value="Masjid">Masjid</option>
                                            <option value="Gereja Katolik">Gereja Katolik</option>
                                            <option value="Gereja Protestan">Gereja Protestan</option>
                                            <option value="Vihara">Vihara</option>
                                            <option value="Pura">Pura</option>
                                            <option value="Klenteng">Klenteng</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Pengurus</label>
                                        <input type="text" name="nama_pengurus" id="edit-ibadah-nama-pengurus" class="form-input">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kontak Pengurus</label>
                                        <input type="text" name="kontak_pengurus" id="edit-ibadah-kontak-pengurus" class="form-input">
                                    </div>
                                    <div class="col-span-1 md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Lengkap</label>
                                        <input type="text" name="alamat" id="edit-ibadah-alamat" class="form-input" required>
                                    </div>
                                </div>
                                
                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="closeEditIbadah()" class="px-5 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-colors">Batal</button>
                                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium shadow-sm transition-colors">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>

                        <script>
                            function openEditIbadah(id, nama, alamat, radius, jenis, nama_pengurus, kontak_pengurus) {
                                var panel = document.getElementById('panel-edit-ibadah');
                                panel.classList.remove('hidden');
                                document.getElementById('edit-ibadah-id').value = id;
                                document.getElementById('edit-ibadah-nama').value = nama;
                                document.getElementById('edit-ibadah-alamat').value = alamat;
                                document.getElementById('edit-ibadah-jenis').value = jenis || '';
                                document.getElementById('edit-ibadah-nama-pengurus').value = nama_pengurus || '';
                                document.getElementById('edit-ibadah-kontak-pengurus').value = kontak_pengurus || '';
                                panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                            function closeEditIbadah() { document.getElementById('panel-edit-ibadah').classList.add('hidden'); }
                        </script>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
