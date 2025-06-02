//Slider start
let currentSlide = 0;

function moveSlide(index) {
  currentSlide = index;
  const slideWidth = $(".slide").outerWidth();
  $(".slides").css("transform", "translateX(" + -slideWidth * index + "px)");

  $(".line").removeClass("active");
  $(".line").eq(index).addClass("active");
}

$(document).ready(function () {
  let slideCount = $(".slide").length;

  setInterval(function () {
    currentSlide = (currentSlide + 1) % slideCount;
    moveSlide(currentSlide);
  }, 5000); // 5 detik
});
// Slider end

// Eror sukses hilang
setTimeout(() => {
  const message = document.querySelector(".error-message, .success-message");
  if (message) {
    console.log(message); // Untuk debugging
    message.style.display = "none";
  }
}, 5000);

//Konfirmasi logout
function confirmLogout() {
  if (confirm("Apakah Anda yakin ingin keluar?")) {
    document.getElementById('logout-form').submit();
  }
}

// Ambil semua link navbar mobile
const links = document.querySelectorAll(".mobile-navbar a");

// Ambil path URL saat ini (misalnya: kalkulator.php)
const currentPage = window.location.pathname.split("/").pop();

// Loop setiap link dan cek apakah href-nya cocok
links.forEach((link) => {
  const href = link.getAttribute("href").split("/").pop();

  // Hapus class 'active' dari semua link
  link.classList.remove("active");

  // Kalau href cocok dengan halaman sekarang, tambahkan class 'active'
  if (href === currentPage) {
    link.classList.add("active");
  }
});

// Scroll ke hasil jika URL mengandung #hasil-kalori
window.addEventListener("DOMContentLoaded", () => {
  if (window.location.hash === "#hasil-kalori") {
    const target = document.getElementById("hasil-kalori");
    if (target) {
      target.scrollIntoView({ behavior: "smooth" });
    }
  }
});

document.getElementById("loginForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value;
  const remember = document.querySelector("input[name='remember']").checked;
  fetch("/login", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
      "X-CSRF-TOKEN": token
    },
    body: new URLSearchParams({
      email: email,
      password: password,
      remember: remember ? 1 : 0,
    }),
  })

    .then(response => response.json())
    .then(data => {
      const errorDiv = document.getElementById("login-error");
      const successDiv = document.getElementById("login-success");

      if (data.success) {
        // Menampilkan pesan sukses
        successDiv.style.display = "block";
        successDiv.textContent = "Login sukses!";
        setTimeout(() => {
          successDiv.style.display = "none";
        }, 3000); // Sembunyikan setelah 3 detik

        window.location.href = "beranda.php"; // redirect jika login sukses
      } else {
        // Menampilkan pesan error
        errorDiv.style.display = "block";
        errorDiv.textContent = data.message || "Login gagal. Periksa kembali email dan password.";
        setTimeout(() => {
          errorDiv.style.display = "none";
        }, 3000); // Sembunyikan setelah 3 detik
      }
    })

    .catch((error) => {
      const errorDiv = document.getElementById("login-error");
      errorDiv.style.display = "block";
      errorDiv.textContent = "server error ";
      setTimeout(() => {
        errorDiv.style.display = "none";
      }, 3000); // Sembunyikan setelah 3 detik
    });
});