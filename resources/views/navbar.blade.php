<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Tree</title>
    <style>
        .nav-link {
            color: black; /* Warna teks normal menjadi hitam */
            text-decoration: none; /* Hapus garis bawah */
            padding: 10px 90px; /* Padding untuk memperbesar area klik */
            border-radius: 12px; /* Lekukan pada area klik */
            transition: background-color 0.3s, color 0.3s; /* Transisi untuk efek hover */
            margin: 0 10px; /* Jarak horizontal antara link */
        }

        .nav-link:hover {
            background-color: #51A783; /* Warna saat hover */
            color: white; /* Ubah warna teks saat hover menjadi putih */
        } 

        .btn-danger {
            background-color: #dc3545; /* Warna merah untuk tombol Logout */
            border: none; /* Hapus border */
            padding: 10px 20px; /* Padding yang sama dengan tombol lainnya */
            border-radius: 12px; /* Menambahkan lekukan pada tombol Logout */
            font-size: 16px; /* Ukuran font konsisten */
            margin-left: 10px; /* Jarak antara tombol Logout dan tombol lainnya */
            transition: background-color 0.3s; /* Transisi warna saat hover */
            margin: 0 10px; /* Jarak horizontal antara link */
        }

        .btn-danger:hover {
            background-color: #c82333; /* Warna saat hover untuk tombol Logout */
        }

        .nav-item {
            display: flex; /* Membuat item navbar menjadi fleksibel */
            align-items: center; /* Menjaga agar isi item sejajar secara vertikal */
        }
    </style>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand ps-3">
      <img src="{{ asset('logo_ft.png') }}" alt="Logo" style="width: 100px; height: auto;">
    </a>
    <a class="navbar-brand" style="margin-left: 10px;">Family Tree</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a href="{{ route('admin.index') }}" class="nav-link">People</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('parent.index') }}" class="nav-link">Parent</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('couple.index') }}" class="nav-link">Couple</a>
        </li>
        <li class="nav-item">
          <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="button" id="logoutButton" class="btn btn-danger">Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      const logoutButton = document.querySelector('#logoutButton');
      const logoutForm = document.querySelector('#logoutForm');
      if (logoutButton) {
          logoutButton.addEventListener('click', function(event) {
              const confirmation = confirm('Are you sure you want to logout?');
              if (confirmation) {
                  logoutForm.submit(); 
              }
          });
      }
  });
</script>
</body>
</html>
</head>
<body>
