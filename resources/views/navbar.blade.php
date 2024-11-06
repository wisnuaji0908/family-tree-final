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
        .profile-image {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
    </style>
</head>
<body>
    @php
        $user = auth()->user();
        $people = \App\Models\People::where('user_id', $user->id)->first();
    @endphp
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand ps-3" href="#">
            @if(isset($setting) && $setting?->app_logo)
                <img src="{{ asset('storage/' . $setting?->app_logo) }}" alt="Logo" class="logo-img">
            @else
                <img src="{{ asset('pp.jpeg') }}" alt="Default Logo" class="logo-img">
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
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User Menu">
                        Settings
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a href="{{ route('setting.index') }}" class="dropdown-item" aria-label="Settings">
                                App Setting
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" class="dropdown-item" id="logoutLink" aria-label="Logout">Logout</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('landing.profile') }}" class="nav-link" aria-label="Profile">
                        <img src="{{ isset($people) && $people->photo_profile ? asset('storage/' . $people->photo_profile) : asset('pdi.png') }}" class="rounded-circle profile-image" alt="Profile" />
                    </a>
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
