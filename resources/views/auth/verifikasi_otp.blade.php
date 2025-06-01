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
    <meta charset="UTF-8">
    <title>Verifikasi OTP - BloodWellness</title>
    <link rel="stylesheet" href="{{ asset(path: 'css/styleGeneral.css') }}">
    <style>
        .otp-input {
            width: 40px;
            font-size: 20px;
            text-align: center;
            margin: 0 5px;
        }
    </style>
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
                <li><a href="{{ url('verifikasi-otp') }}" id="home" class="active"><i class="fas fa-home"></i></a></li>
                <li><a href="{{ route('kalkulator') }}" id="kalkulator"><i class="fas fa-calculator"></i></a>
                </li>
                <li><a href="{{ route('planner') }}" id="makanan"><i class="fas fa-utensils"></i></a></li>
                <li><a href="{{ route('profile') }}" id="profil"><i class="fas fa-user"></i></a></li>
            </ul>
        </div>
    </header>
    <main>
        <div class="container">
            <section class="register-form">
                <h3>Verifikasi Kode OTP</h3>
                <p>Masukkan 6 digit kode yang dikirim ke email Anda</p>

                <form action="{{ route('verifikasi_otp.process') }}" method="POST" id="otpForm">
                    @csrf
                    <div style="display: flex; justify-content: center;">
                        @for ($i = 0; $i < 6; $i++)
                            <input type="text" name="otp[]" maxlength="1" class="otp-input" required
                                pattern="[0-9]">
                        @endfor
                    </div>
                    <br>
                    @if (session('error'))
                        <div class="error-message">{{ session('error') }}</div>
                    @endif
                    <button type="submit">VERIFIKASI</button>

                    <p style="margin-top: 15px;">
                        Tidak menerima kode?
                        <a href="{{ route('otp.resend') }}" style="color: #007bff; text-decoration: underline;">Kirim
                            Ulang OTP</a>
                    </p>


                </form>
            </section>
        </div>
    </main>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        inputs.forEach((input, i) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && i < inputs.length - 1) {
                    inputs[i + 1].focus();
                }
            });
        });
    </script>
</body>

</html>
