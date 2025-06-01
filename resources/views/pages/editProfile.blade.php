<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Profil - BloodWellness</title>
    <link rel="stylesheet" href="{{ asset('css/styleGeneral.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+Antique:wght@300;400;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

        .edit-photo-button {
            position: absolute;
            bottom: 0;
            right: 0;
            z-index: 999;
            background-color: transparent;
            border: none;
            border-radius: 50%;
            color: white;
            padding: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .avatar {
            position: relative;
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
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/kalkulator') }}">Kalkulator Kalori</a></li>
                    <li><a href="{{ url('/planner') }}">Perencana Makanan</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="active">Profil</a></li>
                </ul>
            </nav>
        </div>
        {{-- <div class="auth-buttonsKal">
            <a href="#" onclick="confirmLogout()">Keluar</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div> --}}
        <!-- Navbar Mobile -->
        <div class="mobile-navbar">
            <ul>
                <li><a href="{{ route('beranda') }}" id="home"><i class="fas fa-home"></i></a></li>
                <li><a href="{{ route('kalkulator') }}" id="kalkulator"><i class="fas fa-calculator"></i></a></li>
                <li><a href="{{ route('planner') }}" id="makanan"><i class="fas fa-utensils"></i></a></li>
                <li><a href="{{ route('profile.edit') }}" id="profil" class="active"><i class="fas fa-user"></i></a>
                </li>
            </ul>
        </div>
    </header>

    <main>
        <div class="profile-container" style="background-color: #CAE0BC;">
            {{-- @if (session('success'))
                <div style="padding: 10px; background: #4caf50; color: white; margin-bottom: 15px;">
                    {{ session('success') }}
                </div>
            @endif --}}

            @if (session('success'))
                <div id="successMessage"
                    style="
        padding: 12px 16px;
        background-color: #4CAF50;
        color: white;
        border-radius: 6px;
        margin-bottom: 20px;
        font-weight: 400;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: opacity 0.5s ease;
    ">
                    {{ session('success') }}
                </div>

                <script>
                    setTimeout(() => {
                        const message = document.getElementById('successMessage');
                        if (message) {
                            message.style.opacity = '0';
                            setTimeout(() => message.remove(), 500); // hapus dari DOM setelah transisi selesai
                        }
                    }, 3000);
                </script>
            @endif
            <form action="{{ route('profile.update') }}" method="post" id="profileForm">
                @csrf

                <div class="banner">
                    <img src="{{ asset('css/aset/background.webp') }}" alt="Fresh vegetables and fruits" />
                </div>
                <div class="profile-header">
                    <div class="avatar">
                        <img id="profileImage" src="{{ Auth::user()->photo_url ?? asset('default.png') }}"
                            alt="Foto Profil" width="150">
                        <button class="edit-photo-button" id="openPhotoModal" type="button" title="Ubah Foto"
                            style="z-index: 9999; color: black;">
                            <!-- Ikon Edit Pena SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-edit">
                                <path d="M11 4h9M4 20h16M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                            </svg>

                        </button>
                    </div>


                    <div class="profile-info">
                        <h1>{{ $user->name }}</h1>
                        <p class="email">{{ $user->email }}</p>
                    </div>

                    <div class="profile-actions">
                        <button type="submit" class="btn-edit">Simpan</button>
                    </div>
                </div>

                <div class="profile-form">
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="fullName">Nama Lengkap</label>
                            <input type="text" id="fullName" name="name"
                                value="{{ old('name', $user->name) }}" />
                            @error('name')
                                <div style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="nickname">Nama Panggilan</label>
                            <input type="text" id="nama_panggilan" name="nickname"
                                value="{{ old('nickname', explode(' ', $user->name)[0]) }}" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="gender">Jenis kelamin</label>
                            <div class="select-wrapper">
                                <select id="gender" name="jenis_kelamin">
                                    <option value="Laki-laki"
                                        {{ old('jenis_kelamin', $user->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="Perempuan"
                                        {{ old('jenis_kelamin', $user->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                    <option value="Lainnya"
                                        {{ old('jenis_kelamin', $user->jenis_kelamin) === 'Lainnya' ? 'selected' : '' }}>
                                        Lainnya</option>
                                </select>
                            </div>
                            @error('jenis_kelamin')
                                <div style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="country">Negara</label>
                            <div class="select-wrapper">
                                <select id="country" name="negara">
                                    <option value="Indonesia"
                                        {{ old('negara', $user->negara) === 'Indonesia' ? 'selected' : '' }}>
                                        Indonesia</option>
                                    <option value="Malaysia"
                                        {{ old('negara', $user->negara) === 'Malaysia' ? 'selected' : '' }}>Malaysia
                                    </option>
                                    <option value="Singapura"
                                        {{ old('negara', $user->negara) === 'Singapura' ? 'selected' : '' }}>
                                        Singapura</option>
                                    <option value="Lainnya"
                                        {{ old('negara', $user->negara) === 'Lainnya' ? 'selected' : '' }}>Lainnya
                                    </option>
                                </select>
                            </div>
                            @error('negara')
                                <div style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
            </form>
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="language">Bahasa</label>
                    <input type="text" id="bahasa" name="bahasa"
                        value="{{ old('bahasa', $user->bahasa ?? '-') }}" />
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
                        <span class="email-address">{{ Auth::user()->email }}</span> {{-- email utama tanpa tombol hapus --}}
                    </div>

                    {{-- Tampilkan email tambahan dengan tombol hapus --}}
                    @foreach ($emailsTambahan as $emailItem)
                        <div class="email-item"
                            style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center;">
                                <div class="email-icon" style="margin-right: 10px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="M22 7l-10 7L2 7"></path>
                                    </svg>
                                </div>
                                <span class="email-address">{{ $emailItem->email }}</span>
                            </div>

                            <form action="{{ route('profile.email.delete', ['id' => $emailItem->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="background-color: transparent; border: none; color: #790C29; font-size: 20px; cursor: pointer;">
                                    &minus;
                                </button>
                            </form>
                        </div>
                    @endforeach


                    <button class="btn-add-email" type="button">+ tambah akun email</button>
                </div>
            </div>

            </form>
            <!-- Form terpisah untuk hapus email tambahan -->


        </div>

        </div>
    </main>



    <!-- Modal Upload Foto -->
    <div id="modalUploadFoto" class="modal hidden">
        <div class="modal-content">
            <span class="close-button" id="closeUploadModal">&times;</span>
            <h2>Ubah Foto Profil</h2>
            <form id="formUploadFoto" enctype="multipart/form-data">
                @csrf
                <input type="file" name="photo" id="photoInput" accept="image/*" required>
                <button type="submit" class="btn-submit">Unggah</button>
            </form>
            <p id="uploadMessage" style="color:red; margin-top: 10px;"></p>
        </div>
    </div>


    <!-- Modal Tambah Email -->

    <!-- Modal -->
    <div id="modalTambahEmail" class="modal hidden">
        <div class="modal-content">
            <span class="close-button" id="closeModal">&times;</span>
            <h2>Tambah Email Baru</h2>
            <form id="formTambahEmail">
                <input type="email" id="emailBaru" name="emailBaru" required>
                <button type="submit" class="btn-submit">Simpan</button>
            </form>
            <p id="message" style="color:red; margin-top: 10px;"></p>
        </div>
    </div>>

    <script>
        // Upload Foto
        const photoModal = document.getElementById('modalUploadFoto');
        const openPhotoBtn = document.getElementById('openPhotoModal');
        const closeUploadModal = document.getElementById('closeUploadModal');
        const uploadForm = document.getElementById('formUploadFoto');
        const uploadMessage = document.getElementById('uploadMessage');

        openPhotoBtn.addEventListener('click', () => {
            photoModal.classList.remove('hidden');
            uploadMessage.textContent = '';
        });

        closeUploadModal.addEventListener('click', () => {
            photoModal.classList.add('hidden');
        });

        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(uploadForm);

            fetch("{{ route('upload.photo') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("Foto berhasil diperbarui!");
                        photoModal.classList.add('hidden');
                        document.getElementById('profileImage').src = data.new_url + '?t=' + new Date()
                            .getTime();
                    } else {
                        uploadMessage.textContent = data.message || "Upload gagal.";
                    }
                })
                .catch(error => {
                    uploadMessage.textContent = "Kesalahan server.";
                    console.error(error);
                });
        });

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

                fetch("{{ url('/email/tambah') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            emailBaru: email
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert("Email berhasil ditambahkan!");
                            modal.classList.add('hidden');
                            window.location.reload();
                        } else {
                            message.textContent = data.message || "Gagal menyimpan email.";
                        }
                    })
                    .catch(error => {
                        message.textContent = "Terjadi kesalahan server.";
                        console.error(error);
                    });
            });
        });
    </script>

</body>

</html>
