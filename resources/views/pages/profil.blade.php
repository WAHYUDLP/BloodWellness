<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil</title>

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
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />

    <style>
        body {
            background-color: #6E8E59;
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal.hidden {
            display: none;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            width: 90%;
            max-width: 400px;
            border-radius: 8px;
            position: relative;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
        }

        .btn-submit {
            margin-top: 15px;
            background-color: #6E8E59;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <div class="nav-container">
            <div class="logo-container">
                <img src="{{ asset('css/aset/logo.png') }}" alt="Logo" class="logo" />
                <span class="brand-text">BloodWellness</span>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ url('beranda') }}">Beranda</a></li>
                    <li><a href="{{ route('kalkulator') }}">Kalkulator Kalori</a></li>
                    <li><a href="{{ route('planner') }}">Perencana Makanan</a></li>
                    <li><a href="{{ route('profile') }}" class="active">Profil</a></li>
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
                <li><a href="{{ route('beranda') }}" id="home"><i class="fas fa-home"></i></a></li>
                <li><a href="{{ route('kalkulator') }}" id="kalkulator"><i class="fas fa-calculator"></i></a></li>
                <li><a href="{{ route('planner') }}" id="makanan"><i class="fas fa-utensils"></i></a></li>
                <li><a href="{{ route('profile') }}" id="profil" class="active"><i class="fas fa-user"></i></a></li>
            </ul>
        </div>
    </header>

    <main>
        <div class="profile-container" style="background-color: #CAE0BC;">
            <div class="banner">
                <img src="{{ asset('css/aset/background.webp') }}" alt="Fresh vegetables and fruits" />
            </div>

            <div class="profile-header">
                <div class="avatar">
                    <img id="profileImage"
                        src="{{ $user->photo_url ?? 'https://images.pexels.com/photos/733872/pexels-photo-733872.jpeg' }}"
                        alt="Profile picture" />
                </div>

                <div class="profile-info">
                    <h1>{{ $user->name }}</h1>
                    <p class="email">{{ $user->email }}</p>
                </div>

                <div class="profile-actions">
                    <form action="{{ route('akun.hapus') }}" method="POST"
                        onsubmit="return confirm('Apakah kamu yakin ingin menghapus akun ini? Tindakan ini tidak bisa dibatalkan!')">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" />
                        <button class="btn-delete" type="submit">Hapus Akun</button>
                    </form>

                    <a href="{{ route('profile.edit') }}" class="btn-edit">Edit</a>
                </div>
            </div>

            <div class="profile-form">
                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="fullName">Nama Lengkap</label>
                        <input type="text" readonly id="fullName" value="{{ $user->name }}" />
                    </div>

                    <div class="form-group full-width">
                        <label for="nickname">Nama Panggilan</label>
                        <input type="text" readonly id="nama_panggilan" name="nama_panggilan"
                            value="{{ explode(' ', $user->name)[0] }}" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="gender">Jenis kelamin</label>
                        <input type="text" readonly id="jenis_kelamin" value="{{ $user->jenis_kelamin ?? '-' }}" />
                    </div>

                    <div class="form-group full-width">
                        <label for="country">Negara</label>
                        <input type="text" readonly value="{{ $user->negara ?? '-' }}" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="language">Bahasa</label>
                        <input type="text" readonly value="{{ $user->bahasa ?? '-' }}" />
                    </div>
                </div>

                <div class="email-section">
                    <h2>Akun email</h2>
                    <div class="email-list">
                        {{-- Tampilkan email utama --}}
                        <div class="email-item">
                            <div class="email-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                    <path d="M22 7l-10 7L2 7"></path>
                                </svg>
                            </div>
                            <span class="email-address">{{ Auth::user()->email }}</span> {{-- atau $user->email --}}
                        </div>

                        {{-- Tampilkan email tambahan --}}
                        @foreach ($emailsTambahan as $emailItem)
                            <div class="email-item">
                                <div class="email-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="M22 7l-10 7L2 7"></path>
                                    </svg>
                                </div>
                                <span class="email-address">{{ $emailItem->email }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <div id="modalTambahEmail" class="modal hidden">
            <div class="modal-content">
                <span class="close-button" id="closeModal">&times;</span>
                <h2>Tambah Email</h2>
                <form id="formTambahEmail">
                    <label for="emailBaru">Email Baru</label>
                    <input type="email" id="emailBaru" name="emailBaru" required />
                    <button type="submit" class="btn-submit">Simpan</button>
                </form>
                <div id="message" style="color:red; margin-top:10px;"></div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modalTambahEmail');
            const openBtn = document.querySelector('.btn-add-email');
            const closeBtn = document.getElementById('closeModal');
            const message = document.getElementById('message');

            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                message.textContent = '';
            });

            closeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });

            document.getElementById('formTambahEmail').addEventListener('submit', function(e) {
                e.preventDefault();
                const email = document.getElementById('emailBaru').value;

                fetch('{{ url('email.tambah') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            emailBaru: email,
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            alert('Email berhasil ditambahkan!');
                            modal.classList.add('hidden');
                            window.location.reload();
                            this.reset();
                        } else {
                            message.textContent = data.message || 'Gagal menyimpan email.';
                        }
                    })
                    .catch((error) => {
                        message.textContent = 'Terjadi kesalahan server.';
                        console.error(error);
                    });
            });
        });
    </script>

    <footer>
        <p style="    font-family: 'Zen Kaku Gothic Antique';">&copy; 2025 BloodWellness</p>
    </footer>
</body>

</html>
