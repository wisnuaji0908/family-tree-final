<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>{{ $setting?->app_name ?? config('app.name') }} - Update Profile</title>  
    <link rel="icon" href="{{ $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .centered-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .content {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        h3 {
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            color: #343a40;
        }
        label {
            font-weight: 500;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .btn {
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: 500;
        }
        .btn-warning {
            border-radius: 5px;
            padding: 8px 15px;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .btn-warning:hover {
            background-color: #e0a800;
            text-decoration: none;
        }
        .profile-photo-preview {
            display: block;
            margin: 10px auto;
            border-radius: 50%;
            height: 80px;
            width: 80px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <div class="centered-container">
        <div class="content">
            <h3 class="mb-4">Update Profile</h3>
            <form action="{{ route('update.profile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group text-center">
                    <!-- Profile Photo Preview -->
                    @if(isset($people) && $people->photo_profile)
                    <img src="{{ asset('storage/' . $people->photo_profile) }}" alt="Profile Photo" class="profile-photo-preview">
                    @else
                        <img src="{{ asset('pp.jpeg') }}" alt="PP" class="profile-photo-preview">
                    @endif
                </div>

                <div class="form-group">
                    <label for="photo">Upload Profile Photo</label>
                    <input type="file" id="photo" name="photo" class="form-control">
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $people->name ?? '' }}" autocomplete="off" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="place_birth">Place Birth</label>
                    <input type="text" id="place_birth" name="place_birth" class="form-control" value="{{ $people->place_birth ?? '' }}" autocomplete="off" required>
                    @error('place_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <div class="position-relative">
                        <input type="number" id="phone" name="phone" class="form-control" value="{{ $user->phone_number ?? '' }}" readonly>
                        <a href="{{ route('change-phone') }}" class="btn btn-warning position-absolute top-50 end-0 translate-middle-y">Change Phone Number</a>
                    </div>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="born">Date of Birth</label>
                    <input type="date" id="born" name="born" class="form-control" value="{{ $people->birth_date ?? '' }}" max="{{ date('Y-m-d', strtotime('-1 day')) }}" required>
                    @error('born')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" class="form-select" required>
                        <option value="" {{ old('gender', $customer->gender ?? '') == '' ? 'selected' : '' }}>Select your Gender</option>
                        <option value="male" {{ old('gender', $customer->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $customer->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                

                <button type="submit" class="btn btn-primary btn-block">Save changes</button>
            </form>
        </div>
    </div>
</body>
</html>
