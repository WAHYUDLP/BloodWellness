{{-- resources/views/pages/planner.blade.php --}}
@php
    use Illuminate\Support\Facades\Auth;

    if (!Auth::check()) {
        header('Location: ' . route('login'));
        exit();
    }
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perencana Makanan Harian</title>
    <link rel="stylesheet" href="{{ asset('css/styleGeneral.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body class="halaman-beranda halaman-planner">
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
                    <li><a href="{{ route('planner.create') }}" class="active">Perencana Makanan</a></li>
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
        <div class="planner-intro">
            <h1>Perencana Makanan Harian</h1>
            <p class="subjudul">Ingin menjaga pola makan yang sehat dan seimbang? BloodWellness membantu Anda menyusun
                menu makanan sesuai dengan kebutuhan kalori dan golongan darah Anda.</p>
        </div>

        <div class="planner-container">
            <h2>Pilih golongan darah Anda</h2>

            <form action="{{ route('planner.store') }}" method="POST" id="plannerForm">
                @csrf
                <div class="blood-group-wrapper">
                    @foreach (['A', 'B', 'AB', 'O'] as $g)
                        <label class="blood-group {{ session('blood_group') === $g ? 'active' : '' }}">
                            {{ $g }}
                            <input type="radio" name="blood_group" value="{{ $g }}"
                                {{ session('blood_group') === $g ? 'checked' : '' }}>
                        </label>
                    @endforeach
                </div>

                <div class="form-row">
                    <div class="calories-wrapper">
                        <label for="calories">Jumlah kalori harian:</label>
                        @php
                            $calories = session('calories'); // ambil dari session jika tersedia
                        @endphp
                        <input type="number" id="calories" name="calories"
                            value="{{ old('calories', $calories ?? '') }}" min="0" required>

                    </div>

                    <ul class="macros" data-default-group="{{ session('blood_group') ?? 'O' }}">
                        <li>Minimal <span id="carb">…</span> gr Karbohidrat</li>
                        <li>Minimal <span id="fat">…</span> gr Lemak</li>
                        <li>Minimal <span id="protein">…</span> gr Protein</li>
                    </ul>
                </div>

                <button type="submit" class="btn-buat">BUAT</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 BloodWellness</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const macrosMap = {
                'O': {
                    carb: 25,
                    fat: 25,
                    prot: 50
                },
                'A': {
                    carb: 50,
                    fat: 25,
                    prot: 25
                },
                'B': {
                    carb: 37.5,
                    fat: 25,
                    prot: 37.5
                },
                'AB': {
                    carb: 40,
                    fat: 25,
                    prot: 35
                }
            };

            function calcGrams(cal, pct, calPerGram) {
                return Math.round((cal * pct / 100) / calPerGram);
            }

            const caloriesInput = document.getElementById('calories');
            const carbSpan = document.getElementById('carb');
            const fatSpan = document.getElementById('fat');
            const proteinSpan = document.getElementById('protein');
            const macrosList = document.querySelector('.macros');

            function getSelectedGroup() {
                const sel = document.querySelector('input[name="blood_group"]:checked');
                return sel ? sel.value : macrosList.dataset.defaultGroup;
            }

            function updateMacros() {
                const c = parseFloat(caloriesInput.value) || 0;
                const grp = getSelectedGroup();
                const {
                    carb,
                    fat,
                    prot
                } = macrosMap[grp] || macrosMap['O'];
                carbSpan.textContent = calcGrams(c, carb, 4);
                fatSpan.textContent = calcGrams(c, fat, 9);
                proteinSpan.textContent = calcGrams(c, prot, 4);
            }

            document.querySelectorAll('.blood-group').forEach(el => {
                el.addEventListener('click', () => {
                    document.querySelectorAll('.blood-group').forEach(g => g.classList.remove(
                        'active'));
                    el.classList.add('active');
                    el.querySelector('input').checked = true;
                    updateMacros();
                });
            });

            caloriesInput.addEventListener('input', updateMacros);
            updateMacros();
        });

        function confirmLogout() {
            return confirm('Apakah Anda yakin ingin keluar?');
        }
    </script>

    <div class="mobile-navbar">
        <ul>
            <li><a href="{{ route('beranda') }}" id="home"><i class="fas fa-home"></i></a></li>
            <li><a href="{{ route('kalkulator') }}" id="kalkulator"><i class="fas fa-calculator"></i></a></li>
            <li><a href="{{ route('planner.create') }}" href="{{ route('planner') }}" id="makanan" class="active"><i
                        class="fas fa-utensils"></i></a></li>
            <li><a href="{{ route('profile') }}" id="profil"><i class="fas fa-user"></i></a></li>
        </ul>
    </div>
</body>

</html>
