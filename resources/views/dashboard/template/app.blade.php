<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Skrining Stunting</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/dist/assets/css/bootstrap.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/dist/assets/vendors/iconly/bold.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/dist/assets/vendors/perfect-scrollbar/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/dist/assets/vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/dist/assets/css/app.css')}}">
    <link rel="shortcut icon" href="{{ asset('assets/dist/assets/images/favicon.svg')}}" type="image/x-icon">

    <!-- Kustomisasi Gaya untuk Tema Teal -->
    <style>
        /* Variabel warna untuk konsistensi */
        :root {
            --teal-600: #0d9488;
            --teal-700: #0f766e;
            --teal-100: #ccfbf1;
            --teal-50: #f0fdfa;
        }

        /* Mengubah warna latar belakang item sidebar yang aktif */
        .sidebar-wrapper .sidebar-item.active>.sidebar-link {
            background-color: var(--teal-600);
        }

        .sidebar-wrapper .sidebar-item.active>.sidebar-link:hover {
            background-color: var(--teal-700);
        }

        /* Mengubah warna ikon statistik menjadi teal */
        .stats-icon.purple, .stats-icon.blue, .stats-icon.green, .stats-icon.red {
            background-color: var(--teal-100);
        }

        .stats-icon.purple i, .stats-icon.blue i, .stats-icon.green i, .stats-icon.red i {
            color: var(--teal-600);
        }

        /* Mengubah warna tombol */
        .btn-light-primary {
            background-color: var(--teal-100);
            color: var(--teal-700);
            border-color: var(--teal-100);
        }

        .btn-light-primary:hover, .btn-light-primary:focus {
             background-color: var(--teal-600);
             color: white;
             border-color: var(--teal-600);
        }

        /* Mengubah warna tautan */
        a {
            color: var(--teal-600);
        }

        a:hover {
            color: var(--teal-700);
        }

        /* Mengubah warna teks pada footer */
        .footer .text-danger i {
            color: var(--teal-600) !important;
        }

        /* Mengubah warna isian SVG untuk chart kecil */
        .text-primary {
            fill: var(--teal-600);
        }
        .text-success {
            fill: #16a34a; /* Green */
        }
        .text-danger {
            fill: #dc2626; /* Red */
        }
        .sidebar-wrapper .menu .sidebar-item.active .sidebar-link {
            background-color: var(--teal-600);
        }
        .bg-primary{
            background-color: var(--teal-600) !important;
        }
    </style>
</head>

<body>
    <div id="app">
        @include('dashboard.template.sidebar')
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            @yield('content')
            @include('dashboard.template.footer')
        </div>
    </div>
    <script src="{{ asset('assets/dist/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{ asset('assets/dist/assets/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{ asset('assets/dist/assets/vendors/apexcharts/apexcharts.js')}}"></script>
    <script src="{{ asset('assets/dist/assets/js/pages/dashboard.js')}}"></script>

    <script src="{{ asset('assets/dist/assets/js/main.js')}}"></script>
</body>

</html>
