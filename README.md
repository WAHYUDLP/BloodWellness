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
