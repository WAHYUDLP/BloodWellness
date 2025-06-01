<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- CSS Lokal -->
    <link rel="stylesheet" href="{{ asset('css/styleGeneral.css') }}" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet" />
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Script lokal, di-load dengan defer -->
    <script src="{{ asset('js/script.js') }}" defer></script>

    <title>Beranda BloodWellness</title>
</head>

<body class="halaman-beranda">
    <header>
        <div class="nav-container">
            <div class="logo-container">
                <img src="{{ asset('css/aset/logo.png') }}" alt="Logo" class="logo" />
                <span class="brand-text">BloodWellness</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ route('beranda') }}" class="active">Beranda</a></li>
                    <li><a href="{{ route('kalkulator') }}">Kalkulator Kalori</a></li>
                    <li><a href="{{ route('planner') }}">Perencana Makanan</a></li>
                    <li><a href="{{ route('profile') }}">Profil</a></li>
                </ul>
            </nav>
        </div>

        <div class="auth-buttonsKal">
            <a href="#" onclick="confirmLogout()">Keluar</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <!-- Navbar Mobile -->
        <div class="mobile-navbar">
            <ul>
                <li><a href="{{ route('beranda') }}" id="home" class="active"><i class="fas fa-home"></i></a></li>
                <li><a href="{{ route('kalkulator') }}" id="kalkulator"><i class="fas fa-calculator"></i></a></li>
                <li><a href="{{ route('planner') }}" id="makanan"><i class="fas fa-utensils"></i></a></li>
                <li><a href="{{ route('profile') }}" id="profil"><i class="fas fa-user"></i></a></li>
            </ul>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="slider-container">
                <div class="brand-header">
                    <img src="{{ asset('css/aset/logo.png') }}" alt="Logo" class="logo2" />
                    <span class="brand-name">BloodWellness</span>
                </div>

                <div class="slides">
                    <div class="slide">
                        <h2>Temukan pola makan sehat sesuai tipe darah Anda!</h2>
                        <p>Dengan fitur hitung kalori dan meal planner otomatis, BloodWellness membantu Anda
                            merencanakan menu harian lengkap dengan resep yang lezat dan bergizi.</p>
                    </div>
                    <div class="slide">
                        <h2>Sesuaikan makanan dengan kebutuhan tubuh Anda</h2>
                        <p>BloodWellness memberikan rekomendasi makanan yang cocok berdasarkan tipe darah Anda untuk
                            kesehatan yang lebih optimal.</p>
                    </div>
                    <div class="slide">
                        <h2>Raih hidup sehat dengan pilihan makanan terbaik</h2>
                        <p>Gunakan panduan dari BloodWellness untuk mendapatkan energi yang cukup dan tubuh yang lebih
                            sehat setiap hari.</p>
                    </div>
                </div>
                <div class="nav-lines">
                    <div class="line active" onclick="moveSlide(0)"></div>
                    <div class="line" onclick="moveSlide(1)"></div>
                    <div class="line" onclick="moveSlide(2)"></div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 BloodWellness</p>
    </footer>
</body>

</html>
