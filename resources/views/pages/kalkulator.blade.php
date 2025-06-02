{{-- resources/views/kalkulator.blade.php --}}
@php
    use Illuminate\Support\Facades\Auth;

    $isLoggedIn = Auth::check();

    $gender = old('gender', session('gender'));
    $age = old('age', session('age'));
    $weight = old('weight', session('weight'));
    $height = old('height', session('height'));
    $body_fat = old('body_fat', session('body_fat'));
    $activity = old('activity', session('activity'));

    $hasil_kalori = session('hasil_kalori');
    $kalori = session('kalori');
    $diet = session('diet');

    $tombol_label = session()->has('kalori') ? 'ATUR ULANG' : 'HITUNG';
    $tombol_route = session()->has('kalori') ? route('kalkulator.reset') : route('kalkulator.hitung');
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kalkulator Kalori Harian</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/styleGeneral.css') }}">
    <script src="{{ asset('js/script.js') }}" defer></script>

</head>

<body class="bodyKal">
    {{-- Navbar --}}
    <header>
        <div class="nav-container">
            <div class="logo-container">
                <img src="{{ asset('css/aset/logo.png') }}" alt="Logo" class="logo">
                <span class="brand-text">BloodWellness</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ $isLoggedIn ? route('beranda') : route('login') }}">Beranda</a></li>
                    <li><a href="{{ route('kalkulator') }}" class="active">Kalkulator Kalori</a></li>
                    <li><a href="{{ route('planner') }}">Perencana Makanan</a></li>
                    <li><a href="{{ route('profile') }}">Profil</a></li>
                </ul>
            </nav>
        </div>




        <div class="auth-buttonsKal">
            @auth
                <a href="#" onclick="confirmLogout()">Keluar</a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}">Daftar</a>
            @endauth
        </div>
    </header>

    {{-- Navbar Mobile --}}
    <div class="mobile-navbar">
        <ul>
            <li><a href="{{ $isLoggedIn ? route('beranda') : route('login') }}"><i class="fas fa-home"></i></a></li>
            <li><a href="{{ route('kalkulator') }}" class="active"><i class="fas fa-calculator"></i></a></li>
            <li><a href="{{ route('planner') }}" id="makanan"><i class="fas fa-utensils"></i></a></li>
            <li><a href="{{ route('profile') }}" id="profil"><i class="fas fa-user"></i></a></li>
        </ul>
    </div>

    <main>
        <div class="text-center py-10 pt-36">
            <h1 class="text-3xl font-semibold text-white">Kalkulator Kalori Harian</h1>
            <p class="max-w-xl mx-auto text-sm text-white mt-3">Gunakan alat ini untuk mengetahui kebutuhan kalori
                harian Anda berdasarkan data tubuh dan tingkat aktivitas.</p>
        </div>

        <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-3 gap-6">
            {{-- Form --}}
            <div class="md:col-span-2 bg-[#CAE0BC] rounded-lg shadow p-6">
                <form method="POST" action="{{ $tombol_route }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block mb-1">Jenis Kelamin</label>
                        <select name="gender" class="w-full border rounded px-3 py-2" required>
                            <option disabled {{ $gender == '' ? 'selected' : '' }}>-- pilih opsi --</option>
                            <option value="Pria" {{ $gender == 'Pria' ? 'selected' : '' }}>Pria</option>
                            <option value="Wanita" {{ $gender == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Umur</label>
                        <input type="number" name="age" class="w-full border rounded px-3 py-2" required
                            value="{{ $age }}" />
                    </div>
                    <div>
                        <label class="block mb-1">Berat Badan</label>
                        <div class="flex items-center">
                            <input type="number" name="weight" class="w-full border rounded px-3 py-2" required
                                value="{{ $weight }}" />
                            <span class="ml-2">kg</span>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1">Tinggi Badan</label>
                        <div class="flex items-center">
                            <input type="number" name="height" class="w-full border rounded px-3 py-2" required
                                value="{{ $height }}" />
                            <span class="ml-2">cm</span>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1">Lemak Tubuh</label>
                        <select name="body_fat" class="w-full border rounded px-3 py-2">
                            <option disabled {{ $body_fat == '' ? 'selected' : '' }}>-- pilih opsi --</option>
                            <option value="Tidak tahu" {{ $body_fat == 'Tidak tahu' ? 'selected' : '' }}>Tidak tahu
                            </option>
                            <option value="Kurang dari 18%" {{ $body_fat == 'Kurang dari 18%' ? 'selected' : '' }}>
                                Kurang dari 18%</option>
                            <option value="18% - 25%" {{ $body_fat == '18% - 25%' ? 'selected' : '' }}>18% - 25%
                            </option>
                            <option value="Lebih dari 25%" {{ $body_fat == 'Lebih dari 25%' ? 'selected' : '' }}>Lebih
                                dari 25%</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Level Aktivitas</label>
                        <select name="activity" class="w-full border rounded px-3 py-2" required>
                            <option disabled {{ $activity == '' ? 'selected' : '' }}>-- pilih opsi --</option>
                            <option value="Sangat Rendah" {{ $activity == 'Sangat Rendah' ? 'selected' : '' }}>Sangat
                                Rendah</option>
                            <option value="Ringan" {{ $activity == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                            <option value="Sedang" {{ $activity == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Tinggi" {{ $activity == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="Sangat Tinggi" {{ $activity == 'Sangat Tinggi' ? 'selected' : '' }}>Sangat
                                Tinggi</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full bg-[#EAFAEA] hover:bg-[#CAE0BC] text-black py-2 px-4 rounded font-semibold transition duration-200 transform hover:-translate-y-0.5">
                        {{ $tombol_label }}
                    </button>
                </form>
            </div>

            <!-- Info Lemak Tubuh -->
            <div class="bg-[#790C29] text-white rounded-lg p-6 text-sm">
                <h2 class="font-semibold mb-2">Lemak Tubuh</h2>
                <p>Persentase lemak tubuh adalah jumlah total massa lemak dibagi dengan total massa tubuh. Umumnya
                    antara 18–24% untuk pria, 25–31% untuk wanita. Jika tidak tahu, jangan khawatir, pilih saja opsi
                    "Tidak tahu".</p>

                <!-- Gambar pertama (muncul di mobile dan desktop) -->
                <div class="mt-4">
                    <img src="{{ asset('css/aset/kalkulator.webp') }}" alt="Kalori" class="w-full h-auto rounded-lg">
                </div>

                <!-- Gambar kedua (hanya muncul di desktop) -->
                <div class="mt-4 hidden lg:block">
                    <img src="{{ asset('css/aset/kalkulator2.webp') }}" alt="Kalori 2"
                        class="w-full h-auto rounded-lg">
                </div>
            </div>
        </div>
        @if (session()->has('kalori'))
            @php
                $diet = session('diet');
                $kalori = session('kalori');
                $selected_cal = $kalori[$diet];
                session(['calories' => $selected_cal]); // Tambahan ini

                $labels = [
                    'hilangkan_lemak' => 'Hilangkan Lemak',
                    'pertahankan' => 'Pertahankan berat badan',
                    'bangun_massa_otot' => 'Membangun massa otot',
                ];
            @endphp

            <div id="hasil-kalori"
                class="pt-8 p-6 rounded shadow text-center flex flex-col justify-center items-center">
                <h2 class="text-xl font-bold mb-2 text-white">Pilih diet yang Anda mau</h2>

                <!-- Tombol Pilihan Diet -->
                <form method="POST" action="{{ route('pilih.diet') }}"
                    class="flex flex-wrap gap-3 justify-center mt-4 p-4 rounded" style="background-color: #EAFAEA;">
                    @csrf
                    @foreach ($kalori as $key => $val)
                        <button type="submit" name="diet" value="{{ $key }}"
                            class="px-4 py-2 rounded font-semibold transition-all 
                        {{ $diet === $key ? 'bg-[#790C29] text-white' : 'bg-[#CAE0BC] text-black hover:bg-[#b5d2a4]' }}">
                            {{ $labels[$key] }}
                        </button>
                    @endforeach
                </form>

                <!-- Hasil Kalori -->
                <div class="mt-6">
                    <p class="text-2xl font-bold text-white">{{ $selected_cal }} Kalori/hari</p>
                    <ul class="text-left mt-4 text-sm list-disc list-inside text-white">
                        <li>Minimal {{ round(($selected_cal * 0.55) / 4) }} gr Karbohidrat</li>
                        <li>Minimal {{ round(($selected_cal * 0.25) / 9) }} gr Lemak</li>
                        <li>Minimal {{ round(($selected_cal * 0.2) / 4) }} gr Protein</li>
                    </ul>
                </div>



                <!-- Tombol Rencanakan Makanmu -->
                <div class="mt-6">
                    <a href="{{ route('planner.create') }}" style="background-color: #CAE0BC;"
                        class="text-black px-6 py-2 rounded hover:bg-green-700 transition-all">
                        Rencanakan Makananmu
                    </a>
                </div>
            </div>
        @endif



        <!-- Scroll ke hasil jika ada hasil -->
        @if (session()->has('kalori') && session()->has('diet'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const hasilSection = document.getElementById("hasil-kalori");
                    if (hasilSection) {
                        hasilSection.scrollIntoView({
                            behavior: "smooth"
                        });
                    }
                });
            </script>
        @endif
        <!-- FAQ & Info Tambahan -->
        <div class="max-w-6xl mx-auto mt-10 px-4 space-y-8">
            <details class="bg-[#EAFAEA] rounded p-4">
                <summary class="font-semibold cursor-pointer">Bagaimana kalkulator kalori bekerja?</summary>
                <p class="mt-2 text-sm">
                    Kalkulator kalori bekerja dengan menghitung kebutuhan energi harian tubuh berdasarkan data pribadi
                    seperti jenis kelamin, usia, berat badan, tinggi badan, tingkat aktivitas fisik, dan komposisi lemak
                    tubuh (jika diketahui). Proses dimulai dengan menghitung BMR (Basal Metabolic Rate) — jumlah kalori
                    yang dibutuhkan tubuh untuk menjalankan fungsi dasar saat istirahat. Kemudian, nilai BMR ini
                    dikalikan dengan faktor aktivitas untuk memperoleh TDEE (Total Daily Energy Expenditure), yaitu
                    total kalori yang dibakar dalam sehari berdasarkan gaya hidup. Terakhir, kalkulator menyesuaikan
                    hasil tersebut sesuai dengan tujuan diet pengguna: menurunkan berat badan, mempertahankan berat
                    badan, atau menambah massa otot.
                </p>
            </details>

            <details class="bg-[#EAFAEA] rounded p-4">
                <summary class="font-semibold cursor-pointer">Bagaimana pemilihan level aktivitas?</summary>
                <ul class="list-disc ml-6 mt-2 text-sm space-y-1">
                    <li><strong>Sangat rendah:</strong> Duduk sepanjang hari (kantor)</li>
                    <li><strong>Ringan:</strong> Banyak berdiri (perawat/guru)</li>
                    <li><strong>Sedang:</strong> Berolahraga rutin 2–3x per minggu</li>
                    <li><strong>Tinggi:</strong> Olahraga intens 4–5x per minggu</li>
                    <li><strong>Sangat Tinggi:</strong> Pekerja fisik berat</li>
                </ul>
            </details>

            <details class="bg-[#EAFAEA] rounded p-4">
                <summary class="font-semibold cursor-pointer">Apa itu TDEE?</summary>
                <p class="mt-2 text-sm">
                    TDEE atau <strong>Total Daily Energy Expenditure</strong> adalah jumlah total energi (kalori) yang
                    dibakar tubuh dalam sehari, termasuk aktivitas metabolisme dasar, aktivitas fisik, dan proses
                    pencernaan makanan. TDEE diperoleh dengan mengalikan nilai BMR dengan faktor aktivitas yang
                    mencerminkan seberapa aktif seseorang. Contohnya, seseorang dengan aktivitas fisik sedang akan
                    memiliki faktor sekitar 1.55, sedangkan atlet atau pekerja berat bisa mencapai 1.9. TDEE sangat
                    penting sebagai dasar untuk merancang rencana diet karena mencerminkan kebutuhan kalori aktual tubuh
                    dalam satu hari penuh.
                </p>
            </details>

            <details class="bg-[#EAFAEA] rounded p-4">
                <summary class="font-semibold cursor-pointer">Apa itu BMR dan bagaimana menghitungnya?</summary>
                <p class="mt-2 text-sm">
                    <strong>BMR (Basal Metabolic Rate)</strong> adalah jumlah kalori minimum yang dibutuhkan tubuh untuk
                    mempertahankan fungsi dasar seperti bernapas, detak jantung, dan menjaga suhu tubuh saat istirahat.
                    Kalkulator ini menggunakan dua metode tergantung pada apakah pengguna mengetahui persentase lemak
                    tubuhnya:
                </p>
                <ul class="list-disc ml-6 mt-2 text-sm space-y-1">
                    <li><strong>Katch-McArdle</strong> (jika lemak tubuh diketahui):<br>
                        BMR = 370 + (21.6 × LBM),<br>
                        di mana LBM (massa tubuh tanpa lemak) = Berat × (1 - % Lemak Tubuh)
                    </li>
                    <li><strong>Mifflin-St Jeor</strong> (jika lemak tubuh tidak diketahui):<br>
                        Untuk pria: BMR = 10 × berat (kg) + 6.25 × tinggi (cm) - 5 × usia (tahun) + 5<br>
                        Untuk wanita: BMR = 10 × berat (kg) + 6.25 × tinggi (cm) - 5 × usia (tahun) - 161
                    </li>
                </ul>
            </details>

            <details class="bg-[#EAFAEA] rounded p-4">
                <summary class="font-semibold cursor-pointer">Bagaimana tujuan saya mempengaruhi rekomendasi kalori?
                </summary>
                <p class="mt-2 text-sm">Setelah mengetahui TDEE, kebutuhan kalori akan disesuaikan berdasarkan tujuan
                    diet:</p>
                <ul class="list-disc ml-6 mt-2 text-sm space-y-1">
                    <li><strong>Menurunkan berat badan (defisit kalori):</strong> Mengurangi sekitar 500 kalori dari
                        TDEE agar tubuh membakar lemak sebagai energi.</li>
                    <li><strong>Mempertahankan berat badan:</strong> Asupan kalori disamakan dengan TDEE, menjaga berat
                        tetap stabil.</li>
                    <li><strong>Meningkatkan massa otot (surplus kalori):</strong> Menambahkan sekitar 500 kalori di
                        atas TDEE untuk mendukung pembentukan otot, terutama bila disertai olahraga intens.</li>
                </ul>
            </details>
        </div>

    </main>

    <footer>
        <p>&copy; 2025 BloodWellness</p>
    </footer>

</body>

</html>
