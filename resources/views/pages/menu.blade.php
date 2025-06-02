{{-- resources/views/pageMenu.blade.php --}}

@if (session('scrollToMenu'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuSection = document.getElementById("menuMam");
            if (menuSection) {
                menuSection.scrollIntoView({
                    behavior: "smooth"
                });
            }
        });
    </script>
@endif


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Rencana Makan Hari Ini</title>
    <link rel="stylesheet" href="{{ asset('css/styleGeneral.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body class="halaman-menu">
    <header>
        <div class="nav-container">
            <div class="logo-container">
                <img src="{{ asset('css/aset/logo.png') }}" alt="Logo" class="logo">
                <span class="brand-text">BloodWellness</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ route('beranda') }}">Beranda</a></li>
                    <li><a href="{{ route('kalkulator') }}">Kalkulator Kalori</a></li>
                    <li><a href="{{ route('menu.index') }}" class="active">Perencana Makanan</a></li>
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
    </header>

    <main>
        <section class="planner-intro">
            <h1>Perencana Makanan Harian</h1>
            <p class="subjudul">
                Ingin menjaga pola makan yang sehat dan seimbang? BloodWellness membantu Anda
                menyusun menu makanan sesuai kebutuhan kalori dan golongan darah Anda.
            </p>
        </section>

        <div class="planner-container">
            <h2>Pilih golongan darah Anda</h2>
            <div class="blood-group-wrapper">
                @foreach (['A', 'B', 'AB', 'O'] as $g)
                    <div class="blood-group {{ $g === $group ? 'active' : '' }}">{{ $g }}</div>
                @endforeach
            </div>
            <div class="form-row">
                <div class="calories-wrapper">
                    <label>Jumlah kalori harian:</label>
                    <input type="number" value="{{ $calories }}" disabled>
                </div>
                <ul class="macros">
                    <li>Minimal {{ $grams['carb'] }} gr Karbohidrat</li>
                    <li>Minimal {{ $grams['fat'] }} gr Lemak</li>
                    <li>Minimal {{ $grams['prot'] }} gr Protein</li>
                </ul>
            </div>
            <a href="{{ route('menu.reset') }}" class="btn-reset">ATUR ULANG</a>
        </div>

        <div class="meal-plan" id="menuMam">
            <div class="planner-intro">
                <h1>Rencana Makan Hari Ini</h1>
                <p style="color: white; font-weight: bold;">🕒 {{ $calories }} kalori / hari</p>
            </div>

            @php
                $refreshPhase = request()->query('refresh');
                $group = session('blood_group'); // atau kamu definisikan dari controller
            @endphp

            @foreach ($phases as $phase)
                @php
                    $safePhase = str_replace(' ', '-', $phase);
                    $phaseMenus = $menus[$phase] ?? collect();
                @endphp

                <section class="meal-phase" id="{{ $safePhase }}">
                    <div class="meal-phase-header">
                        <h3>{{ $phase }}</h3>
                        <a class="generate-icon" href="?refresh={{ urlencode($phase) }}#{{ $safePhase }}"
                            title="Regenerate">&#x21bb;</a>
                    </div>

                    <div class="meal-cards">
                        @foreach ($phaseMenus as $m)
                            <a href="{{ route('recipe.show', ['id' => $m->id]) }}" class="meal-card-link">
                                <div class="meal-card">
                                    <img src="{{ asset('images/' . $m->image) }}" alt="{{ $m->name }}">
                                    <h4>{{ $m->name }}</h4>
                                    <p class="cal">{{ $m->calories }} kalori</p>
                                    <ul>
                                        @foreach (explode("\n", $m->ingredients) as $ing)
                                            @php $ing = trim($ing); @endphp
                                            @if ($ing !== '')
                                                <li>{{ $ing }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach


        </div>
    </main>

    <footer>&copy; 2025 BloodWellness</footer>

    <div class="mobile-navbar">
        <ul>
            <li><a href="{{ route('beranda') }}" id="home"><i class="fas fa-home"></i></a></li>
            <li><a href="{{ route('kalkulator') }}" id="kalkulator"><i class="fas fa-calculator"></i></a></li>
            <li><a href="{{ route('menu.index') }}" id="makanan" class="active"><i class="fas fa-utensils"></i></a>
            </li>
            <li><a href="{{ route('profile') }}" id="profil"><i class="fas fa-user"></i></a></li>
        </ul>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.location.hash) {
                const hash = window.location.hash;
                // Escape karakter khusus di hash supaya bisa querySelector
                const escapedHash = hash.replace(/\+/g, '\\+');

                // Hapus hash dari URL supaya browser tidak auto scroll langsung pada load berikutnya
                history.replaceState(null, null, window.location.pathname + window.location.search);

                // Scroll manual dengan smooth setelah delay supaya DOM siap
                setTimeout(() => {
                    const element = document.querySelector(escapedHash);
                    if (element) {
                        element.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }, 400);
            }
        });
    </script>




</body>

</html>
