# Automasi Deployment dan Testing dengan Trigger Repository Menggunakan GitHub Actions dan Docker Compose

## 1. Membuat EC2 Instance
- Masuk ke AWS Management Console → EC2 → Launch Instance.

- Pilih Ubuntu 24.04 sebagai AMI, tentukan tipe instance, lalu jalankan.

- Catat public IP dan private IP yang diberikan (misal: 3.86.104.59 / 172.31.82.120).

## 2. Menambahkan Inbound Rules
- Buka Security Group yang terkait dengan instance.

- Tambahkan aturan inbound untuk:

  - Port 22 (SSH)

  - Port 80 (HTTP)

  - Port 8080 (HTTP alternatif).

## 3. Mengakses Server via SSH
Jalankan perintah:
```bash
ssh -i <path-to-SSH-KEY>.pem ubuntu@<public-IP>
```
Pastikan koneksi berhasil dan tampil prompt Ubuntu.

## 4. Memverifikasi Kesiapan Server
Di dalam terminal kelihatan info sistem, sisa storage, dan info IP internal (172.31.82.120).

## 5. Menyiapkan Berkas CI/CD
- Di repository GitHub, buat folder .github/workflows/ dan tambahkan cicd.yaml.

- Buat Dockerfile untuk aplikasi Laravel.

- Buat docker-compose.yml untuk mengorkestrasi container (app, database, dll).

## 6. Menjalankan Proses Build
- Push perubahan ke GitHub → GitHub Actions akan otomatis menjalankan job build:

  - Set up runner

  - Checkout repository

  - Setup Docker Buildx

  - Build Docker image

  - Install dependency dengan composer install

  - Jalankan phpunit

- Pastikan seluruh langkah build sukses tanpa error.

## 7. Melakukan Deploy ke EC2
- Di job deploy pada cicd.yaml, tambahkan langkah:

  - SSH ke EC2

  - Pull Docker image terbaru (docker pull …)

  - Jalankan ulang container dengan 
```bash
docker-compose up -d
```

## 8. Memverifikasi Commit Terbaru
- SSH ke server → cek commit terakhir:
```bash
git -C <path-repo> log -1
```

- Pastikan Commit ID, pesan, dan branch sesuai dengan yang diinginkan.

## 9. Memeriksa Container Docker
- Jalankan
```bash
docker ps
```

- Pastikan container aplikasi (Laravel) dan database (MySQL) berstatus Up dan port sudah ter-ekspos dengan benar.

# Cara Menangani Error

## 1. Gagal Membuat atau Mengakses EC2 Instance

**Masalah Umum:**
- Tidak bisa meluncurkan instance.
- Instance stuck di “pending” atau tidak muncul public IP.

**Solusi:**
- Pastikan region AWS yang digunakan benar.
- Cek kuota EC2 dan izin IAM.
- Pilih jenis instance yang tersedia (misalnya `t2.micro` untuk free tier).

---

## 2. Inbound Rules Tidak Bekerja

**Masalah Umum:**
- Tidak bisa SSH atau akses web dari browser.

**Solusi:**
- Periksa Security Group apakah port 22, 80, dan 8080 sudah ditambahkan.
- Gunakan `0.0.0.0/0` untuk pengujian (ingat untuk mengganti dengan IP publik kamu jika perlu keamanan).

---

## 3. SSH ke Server Gagal

**Masalah Umum:**
- Error: `Permission denied (publickey)` atau `Connection timed out`.

**Solusi:**
- Jalankan perintah:
  ```bash
  chmod 400 <key.pem>

## 4. Server Tidak Menampilkan Informasi Sistem

**Masalah Umum:**
- Setelah login SSH, tidak terlihat informasi sistem, IP, atau sisa storage.

**Solusi:**
- Jalankan perintah manual berikut:
  ```bash
  df -h         # Menampilkan informasi storage
  ip a          # Menampilkan IP address
  uname -a      # Menampilkan informasi sistem operasi

## 5. Berkas CI/CD Tidak Berfungsi

- **Masalah Umum**:
  - Workflow GitHub Actions tidak berjalan saat melakukan push ke repository.

- **Solusi**:
  - Pastikan struktur file sudah benar:
    ```
    .github/
      workflows/
        cicd.yaml
    ```
  - Tambahkan trigger event di dalam `cicd.yaml`:
    ```yaml
    on:
      push:
        branches:
          - main
    ```
  - Pastikan format file YAML valid (gunakan YAML Linter).
  - Gunakan spasi, bukan tab, untuk indentasi.

## 6. Job Build Gagal di GitHub Actions

- **Masalah Umum**:
  - Proses build gagal saat menjalankan:
    - `composer install`
    - `phpunit`
    - Docker build image

- **Solusi**:
  - Periksa log error melalui tab **Actions** di GitHub.
  - Tambahkan cache composer di file `cicd.yaml` untuk mempercepat dan menstabilkan build:
    ```yaml
    - uses: actions/cache@v3
      with:
        path: vendor
        key: composer-${{ hashFiles('**/composer.lock') }}
        restore-keys: composer-
    ```
  - Pastikan file `.env`, `composer.json`, dan `composer.lock` tersedia dan benar.
  - Jalankan `phpunit` secara lokal terlebih dahulu untuk memastikan semua test berhasil.

## 7. Deploy ke EC2 Gagal

- **Masalah Umum**:
  - GitHub Actions gagal melakukan SSH ke EC2.
  - Perintah `docker-compose` tidak dijalankan di server.

- **Solusi**:
  - Simpan private key ke **GitHub Secrets** dengan nama `EC2_SSH_KEY`.
  - Tambahkan langkah deploy ke dalam `cicd.yaml`:
    ```yaml
    - name: Deploy to EC2
      run: |
        echo "$PRIVATE_KEY" > private_key.pem
        chmod 600 private_key.pem
        ssh -o StrictHostKeyChecking=no -i private_key.pem ubuntu@<EC2_IP> "
          cd /path/to/app &&
          docker-compose pull &&
          docker-compose up -d
        "
      env:
        PRIVATE_KEY: ${{ secrets.EC2_SSH_KEY }}
    ```
  - Pastikan public key sudah ditambahkan ke file `~/.ssh/authorized_keys` di dalam server EC2.

## 8. Commit Terakhir Tidak Sesuai

- **Masalah Umum**:
  - Commit di server EC2 tidak sesuai dengan commit terbaru di GitHub.

- **Solusi**:
  - Cek commit terakhir di server dengan perintah:
    ```bash
    git -C /path/to/app log -1
    ```
  - Jika commit belum sesuai, jalankan:
    ```bash
    git -C /path/to/app pull origin main
    ```
  - Pastikan langkah deploy di GitHub Actions berhasil dan sudah mengeksekusi `git pull` atau proses build image terbaru.

## 9. Container Tidak Jalan / Error

- **Masalah Umum**:
  - Container tidak muncul saat menjalankan `docker ps`.
  - Container berstatus `Exited` atau tidak dapat diakses dari browser.

- **Solusi**:
  - Lihat log container untuk mengetahui penyebab error:
    ```bash
    docker logs <container_id>
    ```
  - Pastikan konfigurasi di file `.env` sudah benar, terutama:
    - `DB_HOST`
    - `DB_PASSWORD`
    - `APP_KEY`
  - Jalankan perintah berikut di dalam container untuk memperbaiki konfigurasi:
    ```bash
    docker-compose exec app php artisan migrate
    docker-compose exec app php artisan config:cache
    ```
  - Periksa port yang digunakan di `docker-compose.yml`, dan pastikan port tersebut sudah ditambahkan ke Inbound Rules EC2.

# Kesimpulan 
Dokumentasi ini dirancang untuk membantu proses automasi deployment dan testing aplikasi menggunakan GitHub Actions dan Docker Compose secara terintegrasi dengan server EC2. Dengan memastikan untuk memeriksa setiap langkah secara bertahap melalui log GitHub Actions dan output server.
