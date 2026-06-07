<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Daftar Laporan Warga') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-8 p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl border border-indigo-100 dark:border-indigo-800 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-300">Tautan Pelaporan Warga</h3>
                            <p class="text-sm text-indigo-700 dark:text-indigo-400 mt-1">Gunakan tautan ini atau jadikan QR Code agar warga dapat melapor langsung ke wilayah Anda.</p>
                        </div>
                        <div class="flex w-full md:w-auto items-center gap-2">
                            <input type="text" readonly value="{{ url('/lapor/' . $tempatIbadah->kode_lapor) }}" id="link-lapor" class="w-full md:w-80 bg-white dark:bg-gray-800 text-sm border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <button onclick="copyLink()" class="flex-shrink-0 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Salin
                            </button>
                        </div>
                    </div>

                    <script>
                        function copyLink() {
                            var copyText = document.getElementById("link-lapor");
                            copyText.select();
                            copyText.setSelectionRange(0, 99999); // For mobile devices
                            navigator.clipboard.writeText(copyText.value);
                            alert("Tautan berhasil disalin: " + copyText.value);
                        }
                    </script>
                    @if($laporans->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada laporan baru</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Semua laporan warga telah diproses atau belum ada yang melaporkan.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Tanggal Lapor</th>
                                        <th scope="col" class="px-6 py-3">Nama Calon Penerima</th>
                                        <th scope="col" class="px-6 py-3">Tanggungan</th>
                                        <th scope="col" class="px-6 py-3">Alamat</th>
                                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                                        <th scope="col" class="px-6 py-3">Foto Bukti</th>
                                        <th scope="col" class="px-6 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($laporans as $laporan)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-6 py-4">{{ $laporan->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $laporan->nama_calon }}</td>
                                            <td class="px-6 py-4">{{ $laporan->jumlah_tanggungan }} Orang</td>
                                            <td class="px-6 py-4">
                                                @if($laporan->alamat)
                                                    <span class="text-gray-700 dark:text-gray-300">{{ $laporan->alamat }}</span>
                                                @else
                                                    <span class="text-gray-400 italic">Tidak ada alamat</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($laporan->deskripsi)
                                                    <span class="text-gray-700 dark:text-gray-300">{{ Str::limit($laporan->deskripsi, 50) }}</span>
                                                @else
                                                    <span class="text-gray-400 italic">Tidak ada deskripsi</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($laporan->foto)
                                                    <a href="{{ asset('storage/uploads/' . $laporan->foto) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Foto</a>
                                                @else
                                                    <span class="text-gray-400 italic">Tidak ada foto</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="{{ route('dashboard', ['add_laporan' => $laporan->id]) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                                    Tambah Data Baru dari Laporan Ini
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
