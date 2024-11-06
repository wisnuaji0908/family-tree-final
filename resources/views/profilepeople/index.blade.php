<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>{{ $setting?->app_name ?? config('app.name') }} - Profile People</title>  
    <link rel="icon" href="{{ $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .profile-section {
            background-color: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center content */
        }

        .profile-photo {
            width: 120px; /* Set the size of the photo */
            height: 120px;
            border-radius: 50%; /* Make the photo circular */
            object-fit: cover; /* Maintain aspect ratio */
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .profile-field {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px;
            border-bottom: 1px solid #eaeaea;
        }

        .profile-field span:first-child {
            font-weight: bold;
        }

        .profile-field span {
            color: #333;
        }

        .button-grid {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .btn-box {
            background-color: #51A783;
            color: white;
            padding: 15px;
            border-radius: 50%;
            text-align: center;
            width: 50px;
            height: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-box:hover {
            background-color: #0056b3;
            cursor: pointer;
        }

        .bi {
            font-size: 1.2rem;
        }

        .alert {
            border-radius: 8px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="profile-section">
        <h1>{{ $setting?->app_name ?? config('app.name') }} - Profile</h1>
        
        <!-- Profile Photo -->
        <img src="{{ $people->photo_profile ? asset('storage/' . $people->photo_profile) : asset('pp.png') }}" alt="Profile Photo" class="profile-photo">
        
        @if ($message = session('success'))
            <div class="alert alert-success my-2" role="alert">
                {{ $message }}
            </div>
        @endif
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif
        <div class="profile-field">
            <span>Name:</span>
            <span>{{ $people->name }}</span>
        </div>
        <div class="profile-field">
            <span>Number Phone:</span>
            <span>{{ $user->phone_number ?? 'Not filled' }}</span>
        </div>
        <div class="profile-field">
            <span>Date of Birth:</span>
            <span>{{ $people->birth_date ? \Carbon\Carbon::parse($people->birth_date)->format('d-m-Y') : 'Not filled' }}</span>
        </div>        
        <div class="profile-field">
            <span>Gender:</span>
            <span class="text-capitalize">{{ $people->gender ?? 'Not filled' }}</span>
        </div>
    </div>

    <div class="button-grid">
        <a href="{{ route('backRedirect') }}" class="btn-box" title="Back">
            <i class="bi bi-arrow-left"></i>
        </a>
        <a href=" {{ route('landing.edit.people') }} " type="button" class="btn-box" title="Edit Profile">
            <i class="bi bi-gear"></i>
        </a>
        <a href=" {{ route('landing.change.people') }} " type="button" class="btn-box" title="Change Password">
            <i class="bi bi-key"></i>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
        
        <a href="#" class="btn-box" id="logout-btn" title="Logout">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out from this session.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, log me out!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        });
    </script>
</body>
</html>
