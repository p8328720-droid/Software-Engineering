{{-- layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiRUKA - @yield('title', 'Sistem Informasi Rusak Kampus')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-orange: #FF6B35;
            --primary-orange-dark: #E85A24;
            --primary-orange-light: #FF8C42;
            --light-orange: #FFF4E6;
            --dark-gray: #2C3E50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #FFF4E6 0%, #FFE4CC 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Navbar (dipakai oleh partial navbar) ── */
        .navbar {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .navbar-brand i {
            margin-right: 10px;
            font-size: 1.6rem;
        }

        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* ── Shared components ── */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            background: white;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-light) 100%);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
        }

        .btn-outline-primary {
            border-radius: 50px;
            color: var(--primary-orange);
            border-color: var(--primary-orange);
        }

        .btn-outline-primary:hover {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
        }

        .table thead th {
            background: var(--light-orange);
            color: var(--primary-orange-dark);
            font-weight: 600;
            border: none;
            padding: 12px;
        }

        .table tbody tr:hover {
            background: var(--light-orange);
        }

        .text-orange {
            color: var(--primary-orange);
        }

        .footer {
            background: white;
            margin-top: auto;
            padding: 1.5rem 0;
            text-align: center;
            color: #6c757d;
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }

            .card-body {
                padding: 1rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- Navbar — include hanya jika layout/page yang extends ini membutuhkannya --}}
    @stack('navbar')

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} SiRUKA - Sistem Informasi Rusak Kampus</p>
            <small class="text-muted">Laporkan kerusakan fasilitas dengan mudah dan cepat</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        setTimeout(function () {
            document.querySelectorAll('.alert').forEach(function (alert) {
                new bootstrap.Alert(alert).close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>