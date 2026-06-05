<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Tempat Ibadah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <style>
                        .map-table { width: 100%; border-collapse: collapse; background: white; color: black; margin-top: 20px;}
                        .map-table th, .map-table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
                        .map-table th { background: #007bff; color: white; }
                        
                        .btn-edit { background: #ffc107; color: #000; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 13px; }
                        .btn-delete { background: #dc3545; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 13px; }
                        .edit-form { background: #fff3cd; padding: 15px; border-radius: 12px; display: none; margin-bottom: 15px; border: 1px solid #ffeeba; color: black; }
                    </style>

                    <div id="panel-edit" class="edit-form">
                        <form action="{{ route('map.edit') }}" method="POST">
                            @csrf
                            <strong>Edit Data Tempat Ibadah:</strong>
                            <div style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap; align-items: center;">
                                <input type="hidden" name="id" id="edit-id">
                                <input type="hidden" name="type" id="edit-type" value="ibadah">
                                <input type="text" name="nama" id="edit-nama" placeholder="Nama Tempat" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc; color: black;" required>
                                
                                <select name="jenis_tempat_ibadah" id="edit-jenis" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc; color: black;">
                                    <option value="">- Pilih Jenis -</option>
                                    <option value="Masjid">Masjid</option>
                                    <option value="Gereja Katolik">Gereja Katolik</option>
                                    <option value="Gereja Protestan">Gereja Protestan</option>
                                    <option value="Vihara">Vihara</option>
                                    <option value="Pura">Pura</option>
                                    <option value="Klenteng">Klenteng</option>
                                </select>
                                
                                <input type="text" name="kontak_person" id="edit-kontak" placeholder="Kontak Person" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc; color: black;">
                                <input type="text" name="alamat" id="edit-alamat" placeholder="Alamat" style="padding: 8px; width: 350px; border-radius: 4px; border: 1px solid #ccc; color: black;" required>
                                <input type="number" name="radius_meter" id="edit-radius" placeholder="Radius (Meter)" style="padding: 8px; width: 130px; border-radius: 4px; border: 1px solid #ccc; color: black;" min="0" required>
                                <button type="submit" style="padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 4px;">Simpan</button>
                                <button type="button" onclick="closeEdit()" style="padding: 8px 15px; background: #6c757d; color: white; border: none; border-radius: 4px;">Batal</button>
                            </div>
                        </form>
                    </div>

                    <table class="map-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Tempat Ibadah</th>
                                <th>Jenis</th>
                                <th>Kontak Person</th>
                                <th>Alamat Detail</th>
                                <th>Radius Layan (Meter)</th>
                                <th>Koordinat (Lat, Lng)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ibadahs as $ib)
                                <tr>
                                    <td>{{ $ib->id }}</td>
                                    <td>{{ $ib->nama_tempat }}</td>
                                    <td>{{ $ib->jenis_tempat_ibadah ?? '-' }}</td>
                                    <td>{{ $ib->kontak_person ?? '-' }}</td>
                                    <td>{{ $ib->alamat }}</td>
                                    <td>{{ $ib->radius_meter }}</td>
                                    <td>{{ $ib->lat }}, {{ $ib->lng }}</td>
                                    <td>
                                        <button class='btn-edit' onclick='openEdit({{ $ib->id }}, "{{ addslashes($ib->nama_tempat) }}", "{{ addslashes($ib->alamat) }}", {{ $ib->radius_meter }}, "{{ addslashes($ib->jenis_tempat_ibadah) }}", "{{ addslashes($ib->kontak_person) }}")'>Edit</button>
                                        <a href='{{ route("map.delete", ["type" => "ibadah", "id" => $ib->id]) }}' class='btn-delete' onclick='return confirm("Hapus data tempat ibadah ini?")'>Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <script>
                        function openEdit(id, nama, alamat, radius, jenis, kontak) {
                            document.getElementById('panel-edit').style.display = 'block';
                            document.getElementById('edit-id').value = id;
                            document.getElementById('edit-nama').value = nama;
                            document.getElementById('edit-alamat').value = alamat;
                            document.getElementById('edit-radius').value = radius;
                            document.getElementById('edit-jenis').value = jenis;
                            document.getElementById('edit-kontak').value = kontak;
                            window.scrollTo(0,0);
                        }
                        function closeEdit() { document.getElementById('panel-edit').style.display = 'none'; }
                    </script>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
