<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lupa Kata Sandi - BloodWellness</title>
    <link rel="stylesheet" href="{{ asset('css/styleGeneral.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="{{ asset(path: 'js/script.js') }}" defer></script>

</head>

<body class="bodyLogin">

    <header>
        <div class="nav-container">
            <div class="logo-container">
                <img src="{{ asset('css/aset/logo.png') }}" alt="Logo" class="logo" />
                <span class="brand-text">BloodWellness</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ url('/login') }}" class="active">Beranda</a></li>
                    <li><a href="{{ route('kalkulator') }}">Kalkulator Kalori</a></li>
                    <li><a href="{{ route('planner') }}">Perencana Makanan</a></li>
                    <li><a href="{{ route('profile') }}">Profil</a></li>
                </ul>
            </nav>
        </div>

        <div class="auth-buttonsKal">
            <a href="{{ url('/login') }}">Masuk</a>
            <a href="{{ url('/register') }}">Daftar</a>
        </div>

        <!-- Navbar Mobile -->
        <div class="mobile-navbar">
            <ul>
                <li><a href="{{ url('/login') }}" id="home" class="active"><i class="fas fa-home"></i></a></li>
                <li><a href="{{ route('kalkulator') }}" id="kalkulator"><i class="fas fa-calculator"></i></a>
                </li>
                <li><a href="{{ route('planner') }}" id="makanan"><i class="fas fa-utensils"></i></a></li>
                <li><a href="{{ route('profile') }}" id="profil"><i class="fas fa-user"></i></a></li>
            </ul>
        </div>
    </header>

    <main>
        <div class="container">

            <section id="form-login" class="register-form">

                {{-- Pesan sukses --}}
                @if (session('success'))
                    <div class="success-message">{{ session('success') }}</div>
                @endif

                <h3 class="heading3">Lupa Kata Sandi</h3>
                <p style="text-align: start; width: 100%;">
                    Masukkan email Anda yang terdaftar.
                </p>






                <form id="lupaPasswordForm" method="POST" action="{{ route('lupa-password.sendOtp') }}">
                    @csrf
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    {{-- Pesan error validasi --}}
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <button type="submit">ATUR ULANG KATA SANDI</button>

                </form>

            </section>

        </div>
    </main>

    <footer>
        <p>&copy; 2025 BloodWellness</p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('.error-message').forEach(el => {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.style.display = 'none', 500);
                });
            }, 3000);
        });
    </script>

</body>

</html>
