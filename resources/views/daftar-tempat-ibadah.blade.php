<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Tempat Ibadah') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Description Card -->
            <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl overflow-hidden shadow-xl sm:rounded-3xl p-6 md:p-8 border border-slate-200/50 dark:border-slate-800/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Data Tempat Ibadah & Penyalur</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            Kelola data tempat ibadah yang menjadi mitra penyalur bantuan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Edit Form Panel -->
            <div id="panel-edit" class="hidden bg-amber-50/80 dark:bg-amber-900/20 backdrop-blur-md border border-amber-200/50 dark:border-amber-700/50 rounded-3xl p-6 md:p-8 shadow-lg transition-all duration-300">
                <div class="flex items-center mb-6 border-b border-amber-200/50 dark:border-amber-700/50 pb-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 mr-4 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2-2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold font-outfit text-slate-900 dark:text-white tracking-tight">Edit Data Tempat Ibadah</h3>
                </div>
                <form action="{{ route('map.edit') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">
                    <input type="hidden" name="type" id="edit-type" value="ibadah">
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Tempat</label>
                        <input type="text" name="nama" id="edit-nama" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Jenis</label>
                        <select name="jenis_tempat_ibadah" id="edit-jenis" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50">
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
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Pengurus</label>
                        <input type="text" name="nama_pengurus" id="edit-nama-pengurus" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Kontak Pengurus</label>
                        <input type="text" name="kontak_pengurus" id="edit-kontak-pengurus" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Radius (Meter)</label>
                        <input type="number" name="radius_meter" id="edit-radius" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50" min="0" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Alamat</label>
                        <input type="text" name="alamat" id="edit-alamat" class="form-input rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800/50" required>
                    </div>
                    
                    <div class="col-span-1 md:col-span-2 flex justify-end space-x-4 mt-6 pt-4 border-t border-amber-200/50 dark:border-amber-700/50">
                        <button type="button" onclick="closeEdit()" class="px-6 py-2.5 bg-white/80 dark:bg-slate-700/80 backdrop-blur-sm border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-full hover:bg-slate-50 dark:hover:bg-slate-600 font-medium transition-all shadow-sm">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white rounded-full font-medium shadow-lg shadow-amber-500/30 hover:-translate-y-0.5 transition-all">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @forelse($ibadahs as $index => $ib)
                    <div class="bg-white/60 dark:bg-slate-800/60 backdrop-blur-md border border-slate-200/80 dark:border-slate-700/80 rounded-3xl shadow-lg shadow-slate-200/50 dark:shadow-slate-900/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden group">
                        <div class="bg-indigo-50/80 dark:bg-indigo-900/30 px-6 py-5 border-b border-indigo-100 dark:border-indigo-800/30 flex justify-between items-start gap-4 transition-colors">
                            <div class="flex-grow">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-800 dark:text-indigo-300 py-1 px-2.5 rounded-lg">#{{ $index + 1 }}</span>
                                    <h4 class="font-bold font-outfit text-slate-900 dark:text-white text-xl">{{ $ib->nama_tempat }}</h4>
                                </div>
                                <div class="text-sm font-medium"><span class="inline-block px-3 py-1 bg-white/60 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm">{{ $ib->jenis_tempat_ibadah ?? 'Lainnya' }}</span></div>
                            </div>
                        </div>
                        
                        <div class="p-6 flex-grow text-sm space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div class="flex flex-col">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium mb-1">Nama Pengurus</span>
                                    <span class="text-slate-900 dark:text-slate-200 font-semibold">{{ $ib->nama_pengurus ?? '-' }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium mb-1">Kontak Pengurus</span>
                                    <span class="text-slate-900 dark:text-slate-200 font-semibold">{{ $ib->kontak_pengurus ?? '-' }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium mb-1">Radius Pelayanan</span>
                                    <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $ib->radius_meter }} m</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium mb-1">Koordinat</span>
                                    <span class="text-slate-900 dark:text-slate-200 font-medium">{{ $ib->lat }}, {{ $ib->lng }}</span>
                                </div>
                            </div>
                            
                            <div class="border-t border-slate-100 dark:border-slate-700/50 pt-4">
                                <span class="text-slate-500 dark:text-slate-400 font-medium mb-1 block">Alamat</span>
                                <span class="text-slate-900 dark:text-slate-200 leading-relaxed block">{{ $ib->alamat }}</span>
                            </div>

                            @if(Auth::user()->role !== 'user')
                            <div class="border-t border-slate-100 dark:border-slate-700/50 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="flex flex-col">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium mb-1">Email (Akun Penyalur)</span>
                                    <span class="text-slate-900 dark:text-slate-200 font-mono text-xs bg-slate-100 dark:bg-slate-900/50 py-1.5 px-3 rounded-lg w-max border border-slate-200 dark:border-slate-800">
                                        @if(isset($adminUsers[$ib->id]))
                                            {{ $adminUsers[$ib->id]->email }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-slate-500 dark:text-slate-400 font-medium mb-1">Password</span>
                                    @if(isset($adminUsers[$ib->id]))
                                        @if(\Illuminate\Support\Facades\Hash::check('password123', $adminUsers[$ib->id]->password))
                                            <span class="text-slate-900 dark:text-slate-200 font-mono text-xs bg-slate-100 dark:bg-slate-900/50 py-1.5 px-3 rounded-lg w-max border border-slate-200 dark:border-slate-800">password123</span>
                                        @else
                                            <span class="text-emerald-700 dark:text-emerald-400 text-xs bg-emerald-50 dark:bg-emerald-900/30 py-1.5 px-3 rounded-lg w-max flex items-center gap-1.5 font-bold border border-emerald-200 dark:border-emerald-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                                Telah Diubah (Rahasia)
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-slate-900 dark:text-slate-200 font-mono text-xs bg-slate-100 dark:bg-slate-900/50 py-1.5 px-3 rounded-lg w-max border border-slate-200 dark:border-slate-800">-</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-200/50 dark:border-slate-700/50 flex justify-end gap-3 flex-wrap">
                            <button class='inline-flex items-center px-4 py-2 border border-amber-200 dark:border-amber-700 text-sm font-semibold rounded-xl text-amber-800 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/30 hover:bg-amber-100 dark:hover:bg-amber-900/50 focus:outline-none transition-all shadow-sm hover:-translate-y-0.5' title='Edit' onclick='openEdit({{ $ib->id }}, "{{ addslashes($ib->nama_tempat) }}", "{{ addslashes($ib->alamat) }}", {{ $ib->radius_meter }}, "{{ addslashes($ib->jenis_tempat_ibadah) }}", "{{ addslashes($ib->nama_pengurus) }}", "{{ addslashes($ib->kontak_pengurus) }}")'>
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </button>
                            
                            @if(Auth::user()->role !== 'user')
                            <a href='{{ route("map.delete", ["type" => "ibadah", "id" => $ib->id]) }}' class='inline-flex items-center px-4 py-2 border border-rose-200 dark:border-rose-700 text-sm font-semibold rounded-xl text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-900/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 focus:outline-none transition-all shadow-sm hover:-translate-y-0.5' title='Hapus' onclick='return confirm("Hapus data ini?")'>
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 lg:col-span-2 py-16 text-center bg-white/40 dark:bg-slate-800/40 backdrop-blur-sm rounded-3xl border border-slate-200/50 dark:border-slate-700/50 shadow-sm">
                        <div class="w-20 h-20 mx-auto bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 font-bold text-lg">Data tempat ibadah tidak ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <script>
                function openEdit(id, nama, alamat, radius, jenis, nama_pengurus, kontak_pengurus) {
                    var panel = document.getElementById('panel-edit');
                    panel.classList.remove('hidden');
                    
                    document.getElementById('edit-id').value = id;
                    document.getElementById('edit-nama').value = nama;
                    document.getElementById('edit-alamat').value = alamat;
                    document.getElementById('edit-radius').value = radius;
                    document.getElementById('edit-jenis').value = jenis;
                    document.getElementById('edit-nama-pengurus').value = nama_pengurus || '';
                    document.getElementById('edit-kontak-pengurus').value = kontak_pengurus || '';
                    
                    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                function closeEdit() { 
                    document.getElementById('panel-edit').classList.add('hidden'); 
                }
            </script>
        </div>
    </div>
</x-app-layout>
