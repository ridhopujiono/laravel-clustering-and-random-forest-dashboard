@extends('dashboard.template.app')

@section('content')
    {{-- Prasyarat: Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container-fluid py-3">
        <h2 class="mb-4">Dashboard Ringkasan Stunting</h2>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Anak Terdata</div>
                                <div id="kpi-total-anak" class="h5 mb-0 font-weight-bold text-gray-800">Memuat...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Prevalensi Stunting</div>
                                <div id="kpi-prevalensi" class="h5 mb-0 font-weight-bold text-gray-800">Memuat...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Anak Berisiko Stunting</div>
                                <div id="kpi-berisiko" class="h5 mb-0 font-weight-bold text-gray-800">Memuat...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Desa Terpantau</div>
                                <div id="kpi-desa" class="h5 mb-0 font-weight-bold text-gray-800">Memuat...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Distribusi Status Stunting</h6>
                    </div>
                    <div class="card-body"><canvas id="statusChart"></canvas></div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Distribusi Profil Cluster</h6>
                    </div>
                    <div class="card-body"><canvas id="clusterChart"></canvas></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top 5 Desa dengan Konsentrasi Risiko Tertinggi</h6>
                    </div>
                    <div class="card-body"><canvas id="desaChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const apiUrl = 'http://localhost:5000/dashboard-summary';

            fetch(apiUrl)
                .then(response => response.json())
                .then(summaryData => {
                    // 1. Update KPI Cards
                    document.getElementById('kpi-total-anak').innerText = summaryData.kpi.total_anak;
                    document.getElementById('kpi-prevalensi').innerText = summaryData.kpi.prevalensi_stunting + '%';
                    document.getElementById('kpi-berisiko').innerText = summaryData.kpi.jumlah_berisiko;
                    document.getElementById('kpi-desa').innerText = summaryData.kpi.jumlah_desa;

                    // 2. Render Chart Distribusi Status Stunting
                    const statusCtx = document.getElementById('statusChart').getContext('2d');
                    const statusData = summaryData.charts.distribusi_status;
                    new Chart(statusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: Object.keys(statusData),
                            datasets: [{
                                data: Object.values(statusData),
                                backgroundColor: ['#d73027', '#fee090', '#74c476'], // STUNTING, RESIKO, TIDAK STUNTING
                            }]
                        }
                    });

                    // 3. Render Chart Distribusi Cluster
                    const clusterCtx = document.getElementById('clusterChart').getContext('2d');
                    const clusterData = summaryData.charts.distribusi_cluster;
                    new Chart(clusterCtx, {
                        type: 'doughnut',
                        data: {
                            labels: [
                                'C0: Stunting Gempal', 'C1: Sehat',
                                'C2: Stunting Kurus', 'C3: Bayi Berisiko'
                            ],
                            datasets: [{
                                data: Object.values(clusterData),
                                backgroundColor: ['#d73027', '#74c476', '#fdae61', '#fee090'],
                            }]
                        }
                    });

                    // 4. Render Chart Top 5 Desa
                    const desaCtx = document.getElementById('desaChart').getContext('2d');
                    const desaData = summaryData.charts.top_5_desa_risiko;
                    new Chart(desaCtx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(desaData),
                            datasets: [{
                                label: 'Persentase Anak Berisiko (Stunting + Resiko)',
                                data: Object.values(desaData),
                                backgroundColor: '#4e73df',
                            }]
                        },
                        options: {
                            scales: { y: { beginAtZero: true, ticks: { callback: (v) => v + '%' } } },
                            plugins: { legend: { display: false } }
                        }
                    });
                })
                .catch(error => console.error('Gagal memuat data dashboard:', error));
        });
    </script>
@endsection
