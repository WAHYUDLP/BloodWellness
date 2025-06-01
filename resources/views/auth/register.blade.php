<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun BloodWellness</title>
    <link rel="stylesheet" href="{{ asset('css/styleGeneral.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body class="bodyLogin">

    <header>
        <div class="nav-container">
            <div class="logo-container">
                <img src="{{ asset('css/aset/logo.png') }}" alt="Logo" class="logo">
                <span class="brand-text">BloodWellness</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ url('register') }}" class="active">Beranda</a></li>
                    <li><a href="{{ route('kalkulator') }}">Kalkulator Kalori</a></li>
                    <li><a href="{{ route('planner') }}">Perencana Makanan</a></li>
                    <li><a href="{{ route('profile') }}">Profil</a></li>
                </ul>
            </nav>
        </div>
        <div class="auth-buttonsKal">
            <a href="{{ url('/login') }}">Masuk</a>
            <a href="{{ route('register') }}">Daftar</a>
        </div>
    </header>

    <div class="mobile-navbar">
        <ul>
            <li><a href="{{ url('/register') }}" id="home" class="active"><i class="fas fa-home"></i></a></li>
            <li><a href="{{ route('kalkulator') }}" id="kalkulator"><i class="fas fa-calculator"></i></a></li>
            <li><a href="{{ route('planner') }}" id="makanan"><i class="fas fa-utensils"></i></a></li>
            <li><a href="{{ route('profile') }}" id="profil"><i class="fas fa-user"></i></a></li>
        </ul>
    </div>

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
                        <p>Gunakan panduan dari BloodWellness untuk mendapatkan energi yang cukup dan tubuh yang
                            lebih sehat setiap hari.</p>
                    </div>
                </div>
                <div class="nav-lines">
                    <div class="line active" onclick="moveSlide(0)"></div>
                    <div class="line" onclick="moveSlide(1)"></div>
                    <div class="line" onclick="moveSlide(2)"></div>
                </div>
            </div>

            <section id="form-register" class="register-form">
                <p class="heading">AYO MULAI SEKARANG!</p>
                <h3 class="heading3">Buat Akun</h3>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <label for="name">Nama Anda</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}">

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}">

                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" required>

                    <label for="confirm-password">Konfirmasi Kata Sandi</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                    <div class="pass">
                        <p>* Password minimal 6 karakter.</p>
                    </div>

                    @if ($errors->any())
                        <div class="error-message">
                            <ul>
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <button type="submit">DAFTAR</button>

                    <div class="auth-options">
                        <div class="google-auth">
                            <p>Atau</p>
                            <button type="button">
                                <!-- icon google -->
                                Daftar dengan akun Google
                            </button>
                        </div>
                        <p>Sudah punya akun? <a href="{{ url('/login') }}">MASUK DI SINI</a></p>
                    </div>
                </form>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 BloodWellness</p>
    </footer>
</body>

</html>
