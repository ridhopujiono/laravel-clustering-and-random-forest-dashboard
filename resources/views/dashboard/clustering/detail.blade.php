@extends('dashboard.template.app')

@section('content')
    {{-- Prasyarat: Leaflet CSS & Chart.js --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        #map {
            height: 60vh;
            width: 100%;
            border-radius: .5rem;
        }

        .legend {
            padding: 10px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            line-height: 24px;
            color: #555;
        }

        .legend h4 {
            margin: 0 0 5px;
            font-size: 16px;
            font-weight: bold;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend i {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            margin-right: 8px;
        }
    </style>

    <div class="container-fluid py-3">
        {{-- Judul halaman akan diisi oleh JavaScript --}}
        <div id="page-header" class="text-center mb-4">
            <h2 id="page-title">Memuat Analisis Cluster...</h2>
            <p id="page-subtitle" class="text-muted"></p>
        </div>

        <div class="row">
            {{-- Kolom untuk Peta --}}
            <div class="col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            {{-- Kolom untuk Kartu Ringkasan --}}
            <div class="col-lg-4 mb-4">
                <div id="summary-card" class="card h-100">
                    {{-- Konten kartu ringkasan akan diisi oleh JavaScript --}}
                </div>
            </div>
        </div>

        <div class="row mt-3">
            {{-- Kolom untuk Grafik Perbandingan Desa --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0" id="chart-title">Perbandingan Konsentrasi per Desa</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="desaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. DEFINISI DATA & PENGATURAN
            const apiUrl = 'http://localhost:5000/map-data';

            const clusterInfo = {
                0: {
                    title: "Stunting Parah & Gempal",
                    subtitle: "Profil anak yang sangat pendek namun berat badannya berlebih untuk tingginya.",
                    color: "#d73027", // Merah
                    dataKey: "persen_cluster_0",
                    rawCountKey: "0", // Kunci untuk jumlah mentah
                    recommendation: "Intervensi prioritas tinggi. Fokus pada stimulasi tinggi badan (protein & mikronutrien) sambil mengontrol asupan kalori agar tidak obesitas."
                },
                1: {
                    title: "Kelompok Sehat",
                    subtitle: "Profil anak dengan pertumbuhan tinggi badan paling ideal.",
                    color: "#74c476", // Hijau
                    dataKey: "persen_cluster_1",
                    rawCountKey: "1",
                    recommendation: "Lanjutkan pemantauan rutin dan program edukasi gizi untuk mempertahankan status gizi baik."
                },
                2: {
                    title: "Stunting Klasik & Kurus",
                    subtitle: "Profil stunting paling umum, di mana anak pendek dan juga kurus.",
                    color: "#fdae61", // Oranye
                    dataKey: "persen_cluster_2",
                    rawCountKey: "2",
                    recommendation: "Membutuhkan intervensi gizi menyeluruh untuk mengejar ketertinggalan berat dan tinggi badan secara bersamaan."
                },
                3: {
                    title: "Kelompok Bayi Berisiko",
                    subtitle: "Kelompok usia termuda yang sudah menunjukkan tanda-tanda awal gagal tumbuh.",
                    color: "#fee090", // Kuning
                    dataKey: "persen_cluster_3",
                    rawCountKey: "3",
                    recommendation: "Intervensi dini adalah kunci. Fokus pada edukasi gizi di 1000 Hari Pertama Kehidupan (HPK) dan pemantauan ketat."
                }
            };

            // 2. BACA QUERY PARAMETER & INISIALISASI
            const urlParams = new URLSearchParams(window.location.search);
            const selectedCluster = urlParams.get('cluster') || '0';
            const currentClusterInfo = clusterInfo[selectedCluster];

            document.getElementById('page-title').innerText =
                `Analisis Mendalam: Cluster ${selectedCluster} (${currentClusterInfo.title})`;
            document.getElementById('page-subtitle').innerText = currentClusterInfo.subtitle;
            document.getElementById('chart-title').innerText =
                `Perbandingan Konsentrasi Cluster ${selectedCluster} per Desa`;

            const map = L.map('map').setView([-7.008251, 110.4778411], 13);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
            }).addTo(map);

            function getColor(percentage) {
                if (percentage > 50) return '#d73027';
                if (percentage > 35) return '#fdae61';
                if (percentage > 20) return '#fee090';
                return '#74c476';
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

            // 3. AMBIL DATA DARI API DAN RENDER HALAMAN
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    renderSummaryCard(currentClusterInfo);
                    renderMap(data, currentClusterInfo);
                    renderChart(data, currentClusterInfo);
                })
                .catch(error => {
                    console.error('Error mengambil data dari API:', error);
                    alert('Gagal memuat data. Pastikan API Anda sedang berjalan.');
                });

            // 4. FUNGSI-FUNGSI UNTUK RENDER
            function renderSummaryCard(info) {
                const card = document.getElementById('summary-card');
                card.innerHTML = `
                    <div class="card-header text-white" style="background-color: ${info.color};">
                        <h4 class="mb-0">Ringkasan Cluster ${selectedCluster}</h4>
                    </div>
                    <div class="card-body mt-3">
                        <h5 class="card-title">${info.title}</h5>
                        <p class="card-text">${info.subtitle}</p>
                        <hr>
                        <h6>Rekomendasi Intervensi:</h6>
                        <p>${info.recommendation}</p>
                    </div>
                `;
            }

            function renderMap(apiData, clusterInfo) {
                apiData.forEach(desa => {
                    if (desa.geojson) {
                        const percentage = desa[clusterInfo.dataKey] || 0;
                        const rawCount = desa[clusterInfo.rawCountKey] || 0;

                        // --- PERUBAHAN DI SINI ---
                        // Hitung total anak di desa tersebut dengan menjumlahkan semua cluster
                        const totalAnak = (desa['0'] || 0) + (desa['1'] || 0) + (desa['2'] || 0) + (desa[
                            '3'] || 0);

                        const geoJsonLayer = L.geoJSON(desa.geojson, {
                            style: getStyle(percentage)
                        });

                        // Update konten popup untuk menyertakan total anak
                        const popupContent = `
                            <h5>${desa.desa_kel}</h5>
                            <hr>
                            <b>Konsentrasi Cluster ${selectedCluster}:<br>${percentage}%</b> (${rawCount} dari ${totalAnak} anak)
                        `;
                                                // --- PERUBAHAN SELESAI ---

                        geoJsonLayer.bindPopup(popupContent);
                        geoJsonLayer.addTo(map);
                    }
                });

                const legend = L.control({
                    position: 'bottomright'
                });
                legend.onAdd = function(map) {
                    const div = L.DomUtil.create('div', 'legend');
                    const grades = [0, 20, 35, 50];
                    div.innerHTML = `<h4>Konsentrasi Cluster ${selectedCluster}</h4>`;
                    for (let i = 0; i < grades.length; i++) {
                        const item = document.createElement('div');
                        item.className = 'legend-item';
                        item.innerHTML =
                            `<i style="background:${getColor(grades[i] + 1)}"></i> ` +
                            `<span>${grades[i]}${grades[i + 1] ? '&ndash;' + grades[i + 1] + '%' : '+%'}</span>`;
                        div.appendChild(item);
                    }
                    return div;
                };
                legend.addTo(map);
            }

            function renderChart(apiData, clusterInfo) {
                const ctx = document.getElementById('desaChart').getContext('2d');

                const labels = apiData.map(d => d.desa_kel);
                const chartData = apiData.map(d => d[clusterInfo.dataKey] || 0);
                const rawCounts = apiData.map(d => d[clusterInfo.rawCountKey] || 0);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: `Persentase Cluster ${selectedCluster}`,
                            data: chartData,
                            backgroundColor: clusterInfo.color
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value + "%"
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const index = context.dataIndex;
                                        const percentage = context.parsed.x;
                                        const count = rawCounts[index];
                                        return ` ${percentage.toFixed(2)}% (${count} anak)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
