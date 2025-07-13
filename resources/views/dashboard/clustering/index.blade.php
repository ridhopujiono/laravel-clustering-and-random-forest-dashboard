@extends('dashboard.template.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* Memberi tinggi pada container peta */
        #map {
            height: 90vh;
            width: 100%;
        }

        /* Styling untuk legenda peta */
        .legend {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            line-height: 18px;
            color: #555;
        }

        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.9;
        }
    </style>

    <div class="container-fluid py-3">
        <h2 class="text-center">Peta Visualisasi Profil Risiko Stunting</h2>
        <p class="text-center text-muted">Warna menunjukkan konsentrasi Cluster 2 (Stunting Klasik & Kurus)</p>

        <div id="map"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Ganti seluruh isi tag <script> Anda dengan ini

        // Inisialisasi peta
        const map = L.map('map').setView([-7.008251,110.4778411], 14);

        // Menambahkan basemap
        L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);

        // URL API
        const apiUrl = 'http://localhost:5000/map-data';

        // --- PERUBAHAN LOGIKA WARNA DI SINI ---
        function getColor(percentage) {
            if (percentage > 50) return '#d73027'; // Merah
            if (percentage > 35) return '#fdae61'; // Oranye
            if (percentage > 20) return '#fee090'; // Kuning
            return '#74c476'; // Hijau
        }

        function getStyle(percentage) {
            return {
                fillColor: getColor(percentage),
                weight: 1.5,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.8
            };
        }
        // --- PERUBAHAN SELESAI ---

        // Mengambil data dari API
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                console.log("Data berhasil diterima dari API:", data);

                data.forEach(desa => {
                    if (desa.geojson) {
                        const persenCluster2 = desa.persen_cluster_2 || 0;
                        const geoJsonLayer = L.geoJSON(desa.geojson, {
                            style: getStyle(persenCluster2)
                        });

                        const popupContent = `
                    <h5>${desa.desa_kel}</h5>
                    <hr>
                    <b>Profil Dominan:</b> Cluster ${desa.profil_dominan}<br>
                    <b>Konsentrasi Cluster 2:</b> ${persenCluster2}%
                    <br><br>
                    <u>Komposisi Cluster:</u><br>
                    - C0 (Stunting Gempal): ${desa.persen_cluster_0}%<br>
                    - C1 (Sehat): ${desa.persen_cluster_1}%<br>
                    - C2 (Stunting Kurus): ${desa.persen_cluster_2}%<br>
                    - C3 (Bayi Berisiko): ${desa.persen_cluster_3}%<br>
                `;
                        geoJsonLayer.bindPopup(popupContent);
                        geoJsonLayer.addTo(map);
                    }
                });

                // --- PERUBAHAN PADA LEGENDA ---
                const legend = L.control({
                    position: 'bottomright'
                });
                legend.onAdd = function(map) {
                    const div = L.DomUtil.create('div', 'info legend');
                    // Ganti 'grades' sesuai dengan level baru Anda
                    const grades = [0, 20, 35, 50];
                    const labels = ['<b>Konsentrasi Cluster 2</b>'];

                    for (let i = 0; i < grades.length; i++) {
                        const from = grades[i];
                        const to = grades[i + 1];
                        labels.push(
                            `<i style="background:${getColor(from + 1)}"></i> ` +
                            from + (to ? '&ndash;' + to + '%' : '+%')
                        );
                    }
                    div.innerHTML = labels.join('<br>');
                    return div;
                };
                legend.addTo(map);
                // --- LEGENDA SELESAI DIPERBAIKI ---
            })
            .catch(error => {
                console.error('Error mengambil data dari API:', error);
                alert('Gagal memuat data peta. Pastikan API Anda sedang berjalan.');
            });
    </script>
@endsection
