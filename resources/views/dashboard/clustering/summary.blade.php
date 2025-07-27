@extends('dashboard.template.app')

@section('content')
    {{-- Menambahkan library untuk tabel interaktif (DataTables) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <div class="container-fluid py-3">
        <div class="text-center mb-5">
            <h2>Ringkasan Analisis Cluster</h2>
            <p class="text-muted">Gambaran umum dari hasil pengelompokan (clustering) data anak.</p>
        </div>

        {{-- 1. KARAKTERISTIK RATA-RATA PER CLUSTER --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">1. Karakteristik Rata-rata per Cluster</h5>
            </div>
            <div class="card-body">
                <p>Tabel ini menunjukkan nilai rata-rata dari fitur utama untuk setiap cluster yang terbentuk.</p>
                <div class="table-responsive">
                    <table id="table-karakteristik" class="table table-bordered table-striped" style="width:100%"></table>
                </div>
            </div>
        </div>

        {{-- 2. DISTRIBUSI STATUS STUNTING --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">2. Distribusi Status Stunting per Cluster</h5>
            </div>
            <div class="card-body">
                <p>Tabel ini menunjukkan komposisi status stunting (dalam persen) di dalam setiap cluster.</p>
                <div class="table-responsive">
                    <table id="table-distribusi" class="table table-bordered table-striped" style="width:100%"></table>
                </div>
            </div>
        </div>

        {{-- 3. PROFIL DOMINAN PER DESA --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">3. Profil Dominan per Desa</h5>
            </div>
            <div class="card-body">
                <p>Tabel ini menunjukkan jumlah anak di setiap cluster per desa, beserta profil cluster yang paling dominan.</p>
                 <div class="table-responsive">
                    <table id="table-dominan" class="table table-bordered table-striped" style="width:100%"></table>
                </div>
            </div>
        </div>

        {{-- 4. PERSENTASE KOMPOSISI CLUSTER PER DESA --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">4. Persentase Komposisi Cluster per Desa (%)</h5>
            </div>
            <div class="card-body">
                <p>Tabel ini menampilkan "DNA" atau komposisi lengkap dari profil cluster di setiap desa.</p>
                 <div class="table-responsive">
                    <table id="table-persentase" class="table table-bordered table-striped" style="width:100%"></table>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Fungsi untuk memformat angka menjadi 2 desimal
        function formatNumber(num) {
            return parseFloat(num).toFixed(2);
        }

        $(document).ready(function() {
            const apiUrl = 'http://localhost:5000/clustering-dashboard';

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    // --- RENDER TABEL 1: KARAKTERISTIK ---
                    const karakteristikData = data.karakteristik_cluster.map(item => ({
                        ...item,
                        usia: formatNumber(item.usia),
                        berat: formatNumber(item.berat),
                        tinggi: formatNumber(item.tinggi),
                        zs_bb_u: formatNumber(item.zs_bb_u),
                        zs_tb_u: formatNumber(item.zs_tb_u),
                        zs_bb_tb: formatNumber(item.zs_bb_tb),
                    }));
                    $('#table-karakteristik').DataTable({
                        data: karakteristikData,
                        columns: [
                            { data: 'cluster', title: 'Cluster' },
                            { data: 'usia', title: 'Usia (Bulan)' },
                            { data: 'berat', title: 'Berat (kg)' },
                            { data: 'tinggi', title: 'Tinggi (cm)' },
                            { data: 'zs_bb_u', title: 'ZS BB/U' },
                            { data: 'zs_tb_u', title: 'ZS TB/U' },
                            { data: 'zs_bb_tb', title: 'ZS BB/TB' },
                        ]
                    });

                    // --- RENDER TABEL 2: DISTRIBUSI ---
                    const distribusiData = data.distribusi_stunting.map(item => ({
                        cluster: item.cluster,
                        RESIKO_STUNTING: formatNumber(item['RESIKO STUNTING'] * 100) + '%',
                        STUNTING: formatNumber(item['STUNTING'] * 100) + '%',
                        TIDAK_STUNTING: formatNumber(item['TIDAK STUNTING'] * 100) + '%',
                    }));
                     $('#table-distribusi').DataTable({
                        data: distribusiData,
                        columns: [
                            { data: 'cluster', title: 'Cluster' },
                            { data: 'RESIKO_STUNTING', title: 'Resiko Stunting' },
                            { data: 'STUNTING', title: 'Stunting' },
                            { data: 'TIDAK_STUNTING', title: 'Tidak Stunting' },
                        ]
                    });

                    // --- RENDER TABEL 3: PROFIL DOMINAN ---
                    $('#table-dominan').DataTable({
                        data: data.profil_dominan_desa,
                        columns: [
                            { data: 'desa_kel', title: 'Desa/Kelurahan' },
                            { data: '0', title: 'Jml C0' },
                            { data: '1', title: 'Jml C1' },
                            { data: '2', title: 'Jml C2' },
                            { data: '3', title: 'Jml C3' },
                            { data: 'profil_dominan', title: 'Profil Dominan' },
                        ]
                    });

                    // --- RENDER TABEL 4: PERSENTASE KOMPOSISI ---
                    const persentaseData = data.persentase_komposisi_desa.map(item => ({
                        ...item,
                        persen_cluster_0: formatNumber(item.persen_cluster_0) + '%',
                        persen_cluster_1: formatNumber(item.persen_cluster_1) + '%',
                        persen_cluster_2: formatNumber(item.persen_cluster_2) + '%',
                        persen_cluster_3: formatNumber(item.persen_cluster_3) + '%',
                    }));
                     $('#table-persentase').DataTable({
                        data: persentaseData,
                        columns: [
                            { data: 'desa_kel', title: 'Desa/Kelurahan' },
                            { data: 'persen_cluster_0', title: '% Cluster 0' },
                            { data: 'persen_cluster_1', title: '% Cluster 1' },
                            { data: 'persen_cluster_2', title: '% Cluster 2' },
                            { data: 'persen_cluster_3', title: '% Cluster 3' },
                        ]
                    });

                })
                .catch(error => console.error('Error memuat data dashboard:', error));
        });
    </script>

@endsection
