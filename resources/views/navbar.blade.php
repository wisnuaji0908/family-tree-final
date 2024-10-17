<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .nav-link {
            color: black;
            text-decoration: none;
            padding: 10px 90px;
            border-radius: 12px; 
            transition: background-color 0.3s, color 0.3s;
            margin: 0 10px; 
        }

        .nav-link:hover {
            background-color: #51A783; 
            color: white; 
        } 

        .btn-danger {
            background-color: #dc3545; 
            border: none;
            padding: 10px 20px;
            border-radius: 12px; 
            font-size: 16px; 
            margin-left: 10px; 
            transition: background-color 0.3s; 
            margin: 0 10px; 
        }

        .btn-danger:hover {
            background-color: #c82333; 
        }

        .nav-item {
            display: flex; 
            align-items: center; 
        }
        .logo-img {
          width: 80px;
          height: auto !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand ps-3" href="#">
            @if(isset($setting) && $setting?->app_logo)
                <img src="{{ asset('storage/' . $setting?->app_logo) }}" alt="Logo" class="logo-img">
            @else
                <img src="{{ asset('logo_ft.png') }}" alt="Default Logo" class="logo-img">
            @endif
        </a>
        <a class="navbar-brand" style="margin-left: 10px;">{{ $setting?->app_name ?? 'Family Tree' }}</a>
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
                    <a href="{{ route('setting.index') }}" class="nav-link" aria-label="Settings">
                        Settings 
                        <img src="{{ asset('settings.png') }}" alt="Settings" style="width: 20px; height: 20px;">
                    </a>
                </li>
                <li class="nav-item">
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="button" id="logoutButton" class="btn btn-danger" aria-label="Logout">Logout</button>
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
