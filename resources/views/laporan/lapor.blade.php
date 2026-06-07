<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="mx-auto w-16 h-16 bg-indigo-100 dark:bg-indigo-900/50 rounded-full flex items-center justify-center mb-4 border-4 border-white dark:border-gray-800 shadow-sm">
            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        </div>
        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Laporan Warga</h2>
        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 mt-1">Untuk: {{ $tempatIbadah->nama_tempat }}</p>
        <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm max-w-sm mx-auto">
            Bantu kami menemukan warga sekitar yang membutuhkan. Masukkan data calon penerima bantuan di bawah ini.
        </p>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 p-4 rounded-xl flex items-start shadow-sm" role="alert">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-sm font-medium">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('lapor.store', $tempatIbadah->kode_lapor) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Nama Calon -->
        <div class="relative">
            <label for="nama_calon" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Kepala Keluarga <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <input id="nama_calon" type="text" name="nama_calon" value="{{ old('nama_calon') }}" required autofocus
                    class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors py-3"
                    placeholder="Contoh: Budi Santoso">
            </div>
            <x-input-error :messages="$errors->get('nama_calon')" class="mt-2" />
        </div>

        <!-- Jumlah Tanggungan -->
        <div class="relative">
            <label for="jumlah_tanggungan" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Jumlah Tanggungan <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <input id="jumlah_tanggungan" type="number" name="jumlah_tanggungan" min="0" value="{{ old('jumlah_tanggungan') }}" required
                    class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors py-3"
                    placeholder="Jumlah anggota keluarga">
            </div>
            <x-input-error :messages="$errors->get('jumlah_tanggungan')" class="mt-2" />
        </div>

        <!-- Alamat -->
        <div class="relative">
            <label for="alamat" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <textarea id="alamat" name="alamat" rows="2" required
                    class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors py-3 px-4"
                    placeholder="Masukkan alamat lengkap RT/RW, Dusun, Desa..."></textarea>
            </div>
            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
        </div>

        <!-- Deskripsi -->
        <div class="relative">
            <label for="deskripsi" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi Kondisi Warga <span class="text-gray-400 font-normal">(Opsional)</span></label>
            <div class="relative">
                <textarea id="deskripsi" name="deskripsi" rows="3"
                    class="block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors py-3 px-4"
                    placeholder="Ceritakan sedikit tentang kondisi ekonomi atau kehidupan warga ini..."></textarea>
            </div>
            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
        </div>

        <!-- Foto Upload (Custom) -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Foto Kondisi / Rumah <span class="text-gray-400 font-normal">(Opsional namun sangat disarankan)</span></label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group relative cursor-pointer" onclick="document.getElementById('foto').click()">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                        <span class="relative cursor-pointer bg-transparent rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                            <span>Upload foto</span>
                            <input id="foto" name="foto" type="file" class="sr-only" accept="image/*" onchange="updateFileName(this)">
                        </span>
                        <p class="pl-1">atau tarik & lepas ke sini</p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-500">
                        PNG, JPG, JPEG maks 2MB
                    </p>
                </div>
            </div>
            <p id="file-name" class="mt-2 text-sm text-indigo-600 dark:text-indigo-400 hidden font-medium text-center"></p>
            <x-input-error :messages="$errors->get('foto')" class="mt-2" />
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
                Kirim Laporan
            </button>
        </div>
    </form>

    <script>
        function updateFileName(input) {
            const fileNameDisplay = document.getElementById('file-name');
            if (input.files && input.files.length > 0) {
                fileNameDisplay.textContent = 'File terpilih: ' + input.files[0].name;
                fileNameDisplay.classList.remove('hidden');
            } else {
                fileNameDisplay.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>
