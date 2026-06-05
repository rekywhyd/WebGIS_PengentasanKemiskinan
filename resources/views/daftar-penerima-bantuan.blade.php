<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Penerima Bantuan') }}
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
                        <form action="{{ route('map.edit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <strong>Edit Data Penerima Bantuan:</strong>
                            <div style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap; align-items: center;">
                                <input type="hidden" name="id" id="edit-id">
                                <input type="hidden" name="type" id="edit-type" value="penerima">
                                
                                <input type="text" name="nama" id="edit-nama" placeholder="Nama KK" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc; color: black;" required>
                                <input type="text" name="nik_kepala_keluarga" id="edit-nik" placeholder="NIK" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc; color: black;">
                                <input type="text" name="nomor_kk" id="edit-kk" placeholder="No. KK" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc; color: black;">
                                <input type="number" name="jumlah_tanggungan" id="edit-tanggungan" placeholder="Jml Tanggungan" style="padding: 8px; width: 150px; border-radius: 4px; border: 1px solid #ccc; color: black;" min="0">
                                
                                <select name="id_tempat_ibadah" id="edit-ibadah" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc; color: black;">
                                    <option value="">- Pilih Tempat Ibadah -</option>
                                    @foreach($ibadahs as $ib)
                                        <option value="{{ $ib->id }}">{{ $ib->nama_tempat }}</option>
                                    @endforeach
                                </select>
                                
                                <input type="text" name="alamat" id="edit-alamat" placeholder="Alamat" style="padding: 8px; width: 250px; border-radius: 4px; border: 1px solid #ccc; color: black;" required>
                                
                                <label style="display:flex; align-items:center; gap:5px; color:#333; font-weight:bold; font-size:13px;">
                                    Ganti Foto: <input type="file" name="foto_kondisi_rumah" id="edit-foto" accept="image/*" style="color: black; font-size:12px;">
                                </label>

                                <button type="submit" style="padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 4px;">Simpan</button>
                                <button type="button" onclick="closeEdit()" style="padding: 8px 15px; background: #6c757d; color: white; border: none; border-radius: 4px;">Batal</button>
                            </div>
                        </form>
                    </div>

                    <table class="map-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kepala Keluarga</th>
                                <th>NIK / KK</th>
                                <th>Tanggungan</th>
                                <th>Tempat Ibadah</th>
                                <th>Alamat Detail</th>
                                <th>Koordinat (Lat, Lng)</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penerimas as $r)
                                <tr>
                                    <td>{{ $r->id }}</td>
                                    <td>{{ $r->nama_kepala_keluarga }}</td>
                                    <td>{{ $r->nik_kepala_keluarga ?? '-' }} / {{ $r->nomor_kk ?? '-' }}</td>
                                    <td>{{ $r->jumlah_tanggungan ?? '0' }}</td>
                                    <td>{{ $r->tempat_ibadah->nama_tempat ?? '-' }}</td>
                                    <td>{{ $r->alamat }}</td>
                                    <td>{{ $r->lat }}, {{ $r->lng }}</td>
                                    <td>
                                        @if($r->foto_kondisi_rumah)
                                            <a href="{{ asset('storage/' . $r->foto_kondisi_rumah) }}" target="_blank" style="color: blue; text-decoration: underline;">Lihat Foto</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <button class='btn-edit' onclick='openEdit({{ $r->id }}, "{{ addslashes($r->nama_kepala_keluarga) }}", "{{ addslashes($r->alamat) }}", "{{ addslashes($r->nik_kepala_keluarga) }}", "{{ addslashes($r->nomor_kk) }}", "{{ $r->jumlah_tanggungan }}", "{{ $r->id_tempat_ibadah }}")'>Edit</button>
                                        <a href='{{ route("map.delete", ["type" => "penerima", "id" => $r->id]) }}' class='btn-delete' onclick='return confirm("Hapus data penerima ini?")'>Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <script>
                        function openEdit(id, nama, alamat, nik, kk, tanggungan, ibadah_id) {
                            document.getElementById('panel-edit').style.display = 'block';
                            document.getElementById('edit-id').value = id;
                            document.getElementById('edit-nama').value = nama;
                            document.getElementById('edit-alamat').value = alamat;
                            document.getElementById('edit-nik').value = nik || '';
                            document.getElementById('edit-kk').value = kk || '';
                            document.getElementById('edit-tanggungan').value = tanggungan || '';
                            document.getElementById('edit-ibadah').value = ibadah_id || '';
                            window.scrollTo(0,0);
                        }
                        function closeEdit() { document.getElementById('panel-edit').style.display = 'none'; }
                    </script>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
