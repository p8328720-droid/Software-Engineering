<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiRUKA - Sistem Informasi Rusak Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #FFF4E6 0%, #FFE4CC 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            flex: 1;
        }
        
        /* Hero Section */
        .hero {
            padding: 60px 0 40px 0;
            text-align: center;
        }
        
        .hero-icon {
            font-size: 4.5rem;
            color: #FF6B35;
            margin-bottom: 20px;
        }
        
        .hero h1 {
            color: #FF6B35;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .hero .lead {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .hero .subtitle {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        /* Portal Cards */
        .portal-card {
            background: white;
            border-radius: 20px;
            padding: 30px 25px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            text-decoration: none;
            color: inherit;
            display: block;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .portal-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .portal-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
        }
        
        .portal-card h4 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2C3E50;
        }
        
        .portal-card .description {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .btn-portal {
            background: linear-gradient(135deg, #FF6B35 0%, #E85A24 100%);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            display: inline-block;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .btn-portal:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,53,0.4);
            color: white;
        }
        
        .register-link {
            margin-top: 15px;
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .register-link a {
            color: #FF6B35;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        /* Footer */
        .footer {
            background: white;
            margin-top: 60px;
            padding: 20px 0;
            text-align: center;
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        
        .footer p {
            margin-bottom: 0;
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        .footer small {
            font-size: 0.75rem;
            color: #adb5bd;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero {
                padding: 40px 0 30px 0;
            }
            .hero h1 {
                font-size: 2rem;
            }
            .hero-icon {
                font-size: 3rem;
            }
            .portal-card {
                padding: 20px;
            }
            .portal-icon {
                font-size: 2.5rem;
            }
            .portal-card h4 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hero Section -->
        <div class="hero">
            <div class="hero-icon">
                <i class="fas fa-tools"></i>
            </div>
            <h1>SiRUKA</h1>
            <p class="lead">Sistem Informasi Rusak Kampus</p>
            <p class="subtitle">Laporkan kerusakan fasilitas dengan mudah dan cepat</p>
        </div>
        
        <!-- Portal Cards Row -->
        <div class="row g-4">
            <!-- Portal Mahasiswa -->
            <div class="col-md-4">
                <div class="portal-card">
                    <div class="portal-icon">
                        <i class="fas fa-user-graduate" style="color: #28a745;"></i>
                    </div>
                    <h4>Portal Mahasiswa</h4>
                    <p class="description">
                        Laporkan kerusakan fasilitas kampus, lacak status laporan, dan dapatkan update terbaru.
                    </p>
                    <a href="{{ route('mahasiswa.login') }}" class="btn-portal">
                        <i class="fas fa-sign-in-alt me-2"></i> Login Mahasiswa
                    </a>
                    <div class="register-link">
                        Belum punya akun? <a href="{{ route('mahasiswa.register') }}">Daftar di sini</a>
                    </div>
                </div>
            </div>

            <!-- Portal Teknisi -->
            <div class="col-md-4">
                <div class="portal-card">
                    <div class="portal-icon">
                        <i class="fas fa-wrench" style="color: #ffc107;"></i>
                    </div>
                    <h4>Portal Teknisi</h4>
                    <p class="description">
                        Kelola tugas perbaikan, update status laporan, dan koordinasikan dengan tim.
                    </p>
                    <a href="{{ route('teknisi.login') }}" class="btn-portal">
                        <i class="fas fa-sign-in-alt me-2"></i> Login Teknisi
                    </a>
                    <div class="register-link">
                        Belum punya akun? <a href="{{ route('teknisi.register') }}">Daftar di sini</a>
                    </div>
                </div>
            </div>

            <!-- Portal Admin -->
            <div class="col-md-4">
                <div class="portal-card">
                    <div class="portal-icon">
                        <i class="fas fa-user-cog" style="color: #dc3545;"></i>
                    </div>
                    <h4>Portal Admin</h4>
                    <p class="description">
                        Kelola user, fasilitas, pantau kinerja sistem, dan lihat audit trail.
                    </p>
                    <a href="{{ route('admin.login') }}" class="btn-portal">
                        <i class="fas fa-sign-in-alt me-2"></i> Login Admin
                    </a>
                    <div class="register-link">
                        Belum punya akun? <a href="{{ route('admin.register') }}">Daftar di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} SiRUKA - Sistem Informasi Rusak Kampus</p>
            <small>Laporkan kerusakan fasilitas dengan mudah dan cepat</small>
        </div>
    </footer>
</body>
</html>