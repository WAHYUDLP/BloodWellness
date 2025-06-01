<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/styleGeneral.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="{{ asset('js/script.js') }}" defer></script>
    <title>Atur Ulang Kata Sandi BloodWellness</title>
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
            <a href="{{ url('pageLogin') }}">Masuk</a>
            <a href="{{ url('pageRegister') }}">Daftar</a>
        </div>

        <!-- Navbar Mobile -->
          <div class="mobile-navbar">
            <ul>
                <li><a href="{{ url('/reset-password') }}" id="home" class="active"><i class="fas fa-home"></i></a></li>
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

                @if (session('success'))
                    <div class="success-message">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div id="login-error" class="error-message">{{ session('error') }}</div>
                @endif

                <p class="heading">AYO MULAI SEKARANG!</p>
                <h3 class="heading3">Lupa Kata Sandi</h3>
                <p style="text-align: start; width: 100%;">
                    Verifikasi Kata Sandi Anda berhasil, konfirmasi untuk mengatur ulang sandi anda
                </p>

                <form id="lupaPasswordForm" method="POST" action="{{ route('reset_password.submit') }}">
                    @csrf
                    <label for="password">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" required />

                    <label for="confirm">Konfirmasi Kata Sandi</label>
                    <input type="password" id="confirm" name="password_confirmation" required />
                    @if ($errors->any())
                        <div class="error-message">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit">KONFIRMASI</button>
                </form>

            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 BloodWellness</p>
    </footer>
</body>

</html>
