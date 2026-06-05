<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Pemetaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Map Interface Start -->
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <style>
                        #map { height: 450px; border-radius: 12px; margin-bottom: 20px; border: 2px solid #333; z-index: 1; }
                        .panel { background: #f4f7f6; padding: 20px; border-radius: 12px; margin-bottom: 15px; color: black;}
                        .edit-form { background: #fff3cd; padding: 15px; border-radius: 12px; display: none; margin-bottom: 15px; border: 1px solid #ffeeba; color: black; }
                        
                        .map-table { width: 100%; border-collapse: collapse; background: white; color: black; }
                        .map-table th, .map-table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
                        .map-table th { background: #007bff; color: white; }
                        
                        .btn-edit { background: #ffc107; color: #000; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 13px; }
                        .btn-delete { background: #dc3545; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 13px; }
                        .leaflet-popup-content { text-align: center; color: black; }
                        .popup-slider { width: 100%; }
                        
                        /* Ubah kursor peta menjadi crosshair untuk menandakan mode tambah titik */
                        .mode-tambah .leaflet-container { cursor: crosshair !important; }
                    </style>

                    <div class="panel">
                        <strong style="display: block; margin-bottom: 10px;">Tambah Titik Baru:</strong>
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <input type="text" id="nama" placeholder="Masukkan Nama..." style="padding: 10px; border-radius: 6px; border: 1px solid #ccc; color: black; flex-grow: 1; max-width: 300px;">
                            <input type="hidden" id="mode" value="">
                            
                            <button type="button" id="btn-mode-ibadah" onclick="setMode('ibadah')" style="padding: 10px 15px; background: #e2e8f0; color: #333; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s;">
                                🏛️ Tempat Ibadah
                            </button>
                            <button type="button" id="btn-mode-penerima" onclick="setMode('penerima')" style="padding: 10px 15px; background: #e2e8f0; color: #333; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s;">
                                🏠 Penerima Bantuan
                            </button>
                        </div>
                        
                        <!-- Extra Fields for Ibadah -->
                        <div id="fields-ibadah" style="display:none; margin-top:10px; gap: 10px; flex-wrap: wrap;">
                            <select id="jenis_tempat_ibadah" style="padding: 10px; border-radius: 6px; border: 1px solid #ccc; color: black;">
                                <option value="">- Pilih Jenis Tempat Ibadah -</option>
                                <option value="Masjid">Masjid</option>
                                <option value="Gereja Katolik">Gereja Katolik</option>
                                <option value="Gereja Protestan">Gereja Protestan</option>
                                <option value="Vihara">Vihara</option>
                                <option value="Pura">Pura</option>
                                <option value="Klenteng">Klenteng</option>
                            </select>
                            <input type="text" id="kontak_person" placeholder="Kontak Person..." style="padding: 10px; border-radius: 6px; border: 1px solid #ccc; color: black;">
                        </div>

                        <!-- Extra Fields for Penerima -->
                        <div id="fields-penerima" style="display:none; margin-top:10px; gap: 10px; flex-wrap: wrap;">
                            <input type="text" id="nik_kepala_keluarga" placeholder="NIK Kepala Keluarga..." style="padding: 10px; border-radius: 6px; border: 1px solid #ccc; color: black;">
                            <input type="text" id="nomor_kk" placeholder="Nomor KK..." style="padding: 10px; border-radius: 6px; border: 1px solid #ccc; color: black;">
                            <input type="number" id="jumlah_tanggungan" placeholder="Jml Tanggungan..." style="padding: 10px; border-radius: 6px; border: 1px solid #ccc; color: black; width: 150px;" min="0">
                            <select id="id_tempat_ibadah" style="padding: 10px; border-radius: 6px; border: 1px solid #ccc; color: black;">
                                <option value="">- Pilih Tempat Ibadah -</option>
                                @foreach($ibadahs as $ib)
                                    <option value="{{ $ib->id }}">{{ $ib->nama_tempat }}</option>
                                @endforeach
                            </select>
                            <label style="display:flex; align-items:center; gap:5px; color:#333; font-weight:bold;">
                                Foto Rumah: <input type="file" id="foto_kondisi_rumah" accept="image/*" style="color: black;">
                            </label>
                        </div>

                        <small style="color: #666; display: block; margin-top: 10px; font-style: italic;">👉 Pilih jenis titik, isi kolom yang tersedia, lalu <b>klik pada peta</b> untuk menyimpan lokasinya.</small>
                    </div>

                    <script>
                        function setMode(mode) {
                            var currentMode = document.getElementById('mode').value;
                            var mapEl = document.getElementById('map');
                            
                            // Toggle off if clicking the same mode
                            if (currentMode === mode) {
                                document.getElementById('mode').value = '';
                                document.getElementById('btn-mode-ibadah').style.background = '#e2e8f0';
                                document.getElementById('btn-mode-ibadah').style.color = '#333';
                                document.getElementById('btn-mode-penerima').style.background = '#e2e8f0';
                                document.getElementById('btn-mode-penerima').style.color = '#333';
                                document.getElementById('fields-ibadah').style.display = 'none';
                                document.getElementById('fields-penerima').style.display = 'none';
                                mapEl.classList.remove('mode-tambah');
                                return;
                            }
                            
                            document.getElementById('mode').value = mode;
                            mapEl.classList.add('mode-tambah');
                            
                            if(mode === 'ibadah') {
                                document.getElementById('btn-mode-ibadah').style.background = '#007bff';
                                document.getElementById('btn-mode-ibadah').style.color = 'white';
                                document.getElementById('btn-mode-penerima').style.background = '#e2e8f0';
                                document.getElementById('btn-mode-penerima').style.color = '#333';
                                document.getElementById('fields-ibadah').style.display = 'flex';
                                document.getElementById('fields-penerima').style.display = 'none';
                            } else {
                                document.getElementById('btn-mode-penerima').style.background = '#007bff';
                                document.getElementById('btn-mode-penerima').style.color = 'white';
                                document.getElementById('btn-mode-ibadah').style.background = '#e2e8f0';
                                document.getElementById('btn-mode-ibadah').style.color = '#333';
                                document.getElementById('fields-ibadah').style.display = 'none';
                                document.getElementById('fields-penerima').style.display = 'flex';
                            }
                        }
                    </script>

                    <div id="panel-edit" class="edit-form">
                        <form action="{{ route('map.edit') }}" method="POST">
                            @csrf
                            <strong>Edit Data:</strong>
                            <input type="hidden" name="id" id="edit-id">
                            <input type="hidden" name="type" id="edit-type">
                            <input type="text" name="nama" id="edit-nama" placeholder="Nama" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc; color: black;">
                            <input type="text" name="alamat" id="edit-alamat" placeholder="Alamat" style="padding: 8px; width: 400px; border-radius: 4px; border: 1px solid #ccc; color: black;">
                            <button type="submit" style="padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 4px;">Simpan</button>
                            <button type="button" onclick="closeEdit()" style="padding: 8px 15px; background: #6c757d; color: white; border: none; border-radius: 4px;">Batal</button>
                        </form>
                    </div>

                    <div id="map"></div>



                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script>
                        var map = L.map('map').setView([-0.02, 109.34], 14);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                        var listIbadah = [];
                        var listPenerima = [];

                        var iconIbadah = L.divIcon({
                            className: '',
                            html: `<div style="background:#007bff; color:#fff; border-radius:50%; width:30px; height:30px; display:flex; align-items:center; justify-content:center; border:2px solid #fff;">🏛️</div>`,
                            iconSize: [30, 30]
                        });

                        // 1. Fungsi Edit UI
                        function openEdit(id, type, nama, alamat) {
                            document.getElementById('panel-edit').style.display = 'block';
                            document.getElementById('edit-id').value = id;
                            document.getElementById('edit-type').value = type;
                            document.getElementById('edit-nama').value = nama;
                            document.getElementById('edit-alamat').value = alamat;
                            window.scrollTo(0,0);
                        }
                        function closeEdit() { document.getElementById('panel-edit').style.display = 'none'; }

                        // 2. Reverse Geocoding & Save
                        map.on('click', async function(e) {
                            const mode = document.getElementById('mode').value;
                            if(!mode) return; // Mode biasa, jangan lakukan apa-apa
                            
                            const nama = document.getElementById('nama').value;
                            if(!nama) return alert("Isi nama dulu!");
                            
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

                            if (mode === 'ibadah') {
                                fd.append('jenis_tempat_ibadah', document.getElementById('jenis_tempat_ibadah').value);
                                fd.append('kontak_person', document.getElementById('kontak_person').value);
                            } else {
                                fd.append('nik_kepala_keluarga', document.getElementById('nik_kepala_keluarga').value);
                                fd.append('nomor_kk', document.getElementById('nomor_kk').value);
                                fd.append('jumlah_tanggungan', document.getElementById('jumlah_tanggungan').value);
                                fd.append('id_tempat_ibadah', document.getElementById('id_tempat_ibadah').value);
                                
                                const fotoInput = document.getElementById('foto_kondisi_rumah');
                                if (fotoInput.files.length > 0) {
                                    fd.append('foto_kondisi_rumah', fotoInput.files[0]);
                                }
                            }
                            
                            fetch('{{ route("map.save") }}', { method: 'POST', body: fd }).then(() => location.reload());
                        });

                        // 3. Load Data Ibadah
                        @foreach($ibadahs as $ib)
                        (function(){
                            var rAwal = {{ $ib->radius_meter }};
                            var circle = L.circle([{{ $ib->lat }}, {{ $ib->lng }}], {
                                radius: rAwal, color: 'blue', fillOpacity: 0.1
                            }).addTo(map);

                            var marker = L.marker([{{ $ib->lat }}, {{ $ib->lng }}], {icon: iconIbadah}).addTo(map);
                            listIbadah.push({id: {{ $ib->id }}, nama: "{{ addslashes($ib->nama_tempat) }}", circle: circle, latlng: L.latLng({{ $ib->lat }}, {{ $ib->lng }})});

                            marker.bindPopup(`
                                <b>{{ addslashes($ib->nama_tempat) }}</b><br>
                                Radius: <span id="val_{{ $ib->id }}">${rAwal}</span> m<br>
                                <input type="range" class="popup-slider" min="100" max="3000" value="${rAwal}" oninput="updateRadius(${rAwal}, this.value, {{ $ib->id }})" onchange="saveRadius(this.value, {{ $ib->id }})">
                                <button onclick="window.location.href='{{ route('map.delete', ['type' => 'ibadah', 'id' => $ib->id]) }}'" style="background:red; color:white; border:none; padding:5px; width:100%; cursor:pointer; margin-top:5px;">Hapus</button>
                            `);
                        })();
                        @endforeach

                        // 4. Load Data Penerima Bantuan
                        @foreach($penerimas as $r)
                            var rm = L.circleMarker([{{ $r->lat }}, {{ $r->lng }}], {radius: 8, color: '#fff', fillOpacity: 1, weight: 2}).addTo(map);
                            listPenerima.push({marker: rm, latlng: L.latLng({{ $r->lat }}, {{ $r->lng }})});
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
                            var baris = document.querySelectorAll('.baris-penerima');
                            baris.forEach(row => {
                                var rLoc = L.latLng(row.getAttribute('data-lat'), row.getAttribute('data-lng'));
                                var covered = [];
                                listIbadah.forEach(ib => { if(rLoc.distanceTo(ib.latlng) <= ib.circle.getRadius()) covered.push(ib.nama); });
                                
                                row.querySelector('.status-cell').innerHTML = covered.length > 0 ? 
                                    `<span style='color:green'>✔ ${covered.join(", ")}</span>` : 
                                    `<span style='color:red'>✘ Luar Jangkauan</span>`;
                            });

                            listPenerima.forEach(penerima => {
                                let check = false;
                                listIbadah.forEach(ib => { if(penerima.latlng.distanceTo(ib.latlng) <= ib.circle.getRadius()) check = true; });
                                penerima.marker.setStyle({fillColor: check ? 'green' : 'red'});
                            });
                        }

                        syncUI();
                    </script>
                    <!-- Map Interface End -->

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
