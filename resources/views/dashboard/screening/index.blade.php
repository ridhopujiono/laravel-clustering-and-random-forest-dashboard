@extends('dashboard.template.app')

@section('content')
    <div class="container my-5">
        <div class="text-center mb-5">
            <h1 class="display-6 fw-bold">Skrining & Analisis Klaster Stunting</h1>
            <p class="text-muted">Masukkan data balita untuk mendapatkan prediksi status gizi dan rekomendasi.</p>
        </div>

        <div class="row g-5">
            <!-- Kolom Form Input -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <h4 class="card-header pb-1">Data Pemeriksaan</h4>
                    <p class="text-muted px-4 py-0 mb-0">Isi formulir dengan data balita yang akan diperiksa</p>
                    <hr>
                    <div class="card-body px-4">
                        <form id="screening-form">
                            <!-- Input Jenis Kelamin -->
                            <div class="mb-3">
                                <label class="form-label fw-medium">Jenis Kelamin</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="laki-laki"
                                            value="L" required>
                                        <label class="form-check-label" for="laki-laki">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="perempuan"
                                            value="P" required>
                                        <label class="form-check-label" for="perempuan">Perempuan</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Input Usia -->
                            <div class="mb-3">
                                <label for="age" class="form-label fw-medium">Usia</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="number" class="form-control" id="age" name="usia"
                                        placeholder="Contoh: 24" required min="0" max="72">
                                    <span class="input-group-text">bulan</span>
                                </div>
                            </div>

                            <!-- Input Berat Badan -->
                            <div class="mb-3">
                                <label for="weight" class="form-label fw-medium">Berat Badan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="number" step="0.1" class="form-control" id="weight"
                                        name="berat_badan" placeholder="Contoh: 10.5" required min="1">
                                    <span class="input-group-text">kg</span>
                                </div>
                            </div>

                            <!-- Input Tinggi Badan -->
                            <div class="mb-4">
                                <label for="height" class="form-label fw-medium">Tinggi Badan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-rulers"></i></span>
                                    <input type="number" step="0.1" class="form-control" id="height"
                                        name="tinggi_badan" placeholder="Contoh: 85.5" required min="40">
                                    <span class="input-group-text">cm</span>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <span id="button-text"><i class="bi bi-search me-2"></i>Cek Status Stunting</span>
                                    <span id="button-spinner" class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true" style="display: none;"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kolom Hasil Analisis -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clipboard2-data me-2"></i>Hasil Analisis</h5>
                    </div>
                    <hr>
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <h6 class="text-muted">Prediksi Stunting</h6>
                                <p id="prediksi-label" class="fs-4 fw-bold mb-0">-</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted">Tingkat Keyakinan</h6>
                                <p id="prediksi-proba" class="fs-4 fw-bold mb-0">-</p>
                            </div>
                        </div>
                        <hr>
                        <div class="mt-4">
                            <h5 class="fw-bold">Profil Klaster Anda: <span id="cluster-name" class="text-primary"></span>
                            </h5>
                            <p id="cluster-info" class="text-muted"></p>
                        </div>
                        <div class="mt-4">
                            <h5 class="fw-bold">Rekomendasi Intervensi</h5>
                            <div id="cluster-rekomendasi" class="alert alert-light border-start border-4 border-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('screening-form');
            const resultsCard = document.getElementById('results-card');
            const buttonText = document.getElementById('button-text');
            const buttonSpinner = document.getElementById('button-spinner');

            const clusterKnowledge = {
                1: {
                    name: "Sehat tapi Ramping (Cluster 1) 🟢",
                    info: "Anak dalam kelompok ini memiliki tinggi badan yang baik untuk usianya (tidak stunting), namun berat badannya cenderung kurang untuk tinggi badannya (ramping/wasted).",
                    rekomendasi: "Fokus pada pemenuhan gizi seimbang, terutama asupan kalori dan protein, untuk mencapai berat badan ideal sesuai tinggi badannya. Pertumbuhan tinggi badan sudah baik, pertahankan!",
                    headerClass: 'bg-success',
                    cluster: 1
                },
                0: {
                    name: "Stunting Parah & Gempal (Cluster 0) 🔴",
                    info: "Ini adalah profil paling kompleks. Anak mengalami stunting parah (sangat pendek) namun memiliki berat badan berlebih untuk tingginya yang pendek.",
                    rekomendasi: "Intervensi prioritas tinggi! Perlu konsultasi dengan ahli gizi. Fokus utama adalah mengejar pertumbuhan tinggi badan dengan nutrisi yang tepat, sambil mengontrol berat badan agar tidak menjadi obesitas. Hindari makanan tinggi gula dan lemak.",
                    headerClass: 'bg-danger',
                    cluster: 0
                },
                2: {
                    name: "Stunting Klasik & Kurus (Cluster 2) 🟡",
                    info: "Ini adalah profil stunting yang paling umum, di mana anak memiliki tinggi badan pendek untuk usianya dan juga kurus untuk tinggi badannya.",
                    rekomendasi: "Perlu intervensi gizi yang menyeluruh untuk mengatasi kekurangan gizi akut (berat badan) dan kronis (tinggi badan) secara bersamaan. Pastikan asupan energi, protein, vitamin, dan mineral terpenuhi.",
                    headerClass: 'bg-warning',
                    cluster: 2
                },
                3: {
                    name: "Bayi Berisiko (Cluster 3) 🟠",
                    info: "Kelompok usia termuda yang sudah menunjukkan tanda-tanda awal gagal tumbuh. Tinggi dan berat badannya berada di ambang batas bawah.",
                    rekomendasi: "Intervensi dini sangat krusial! Pastikan ASI eksklusif (jika di bawah 6 bulan) dan MPASI yang adekuat dan bergizi seimbang. Pemantauan pertumbuhan rutin setiap bulan adalah kunci untuk mencegah jatuh ke dalam kondisi stunting yang lebih parah.",
                    headerClass: 'bg-orange',
                    cluster: 3
                }
            };

            const style = document.createElement('style');
            style.innerHTML = `
                .bg-orange { background-color: #fd7e14; color: white; }
                .card-header.bg-orange h5, .card-header.bg-orange i { color: white !important; }
            `;
            document.head.appendChild(style);

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                buttonText.style.display = 'none';
                buttonSpinner.style.display = 'inline-block';

                // Mengambil data dari form
                const formData = {
                    jk: document.querySelector('input[name="gender"]:checked').value,
                    usia: parseFloat(document.getElementById('age').value),
                    berat: parseFloat(document.getElementById('weight').value),
                    tinggi: parseFloat(document.getElementById('height').value)
                };

                // Melakukan pemanggilan API ke server lokal
                fetch('http://localhost:5000/predict', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        displayResults(data);
                    })
                    .finally(() => {
                        // Mengembalikan tombol ke keadaan semula
                        buttonText.style.display = 'inline-block';
                        buttonSpinner.style.display = 'none';
                    });
            });

            function displayResults(data) {
                const clusterData = clusterKnowledge[data.prediksi_cluster];

                // Mengisi data hasil
                document.getElementById('prediksi-label').textContent = data.prediksi_label;
                // document.getElementById('status-gizi-label').textContent = data.status_gizi_label;
                document.getElementById('prediksi-proba').textContent = data.prediksi_proba ||
                '-'; // Tambahkan fallback

                if (clusterData) {
                    document.getElementById('cluster-name').textContent = clusterData.name;
                    document.getElementById('cluster-info').textContent = clusterData.info;
                    document.getElementById('cluster-rekomendasi').innerHTML =
                        `<p class="mb-0">${clusterData.rekomendasi}</p>`;

                    const resultHeader = document.getElementById('result-header');
                    resultHeader.className = 'card-header card-header-result'; // Reset class
                    resultHeader.classList.add(clusterData.headerClass);
                }

                resultsCard.style.display = 'block';
                resultsCard.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    </script>
@endsection
