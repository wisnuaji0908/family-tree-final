<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $setting?->app_name ?? config('app.name') }} - Change Password</title>  
    <link rel="icon" href="{{ $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 500px;
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        .form-group .input-group-text {
            cursor: pointer;
        }

        button {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Change Password</h3>
        <form action="{{ route('update.password') }}" method="POST">
            @csrf
            <div class="form-group mb-4">
                <label for="current_password">Current Password</label>
                <div class="input-group">
                    <input type="password" id="current_password" name="current_password" class="form-control" >
                    <span class="input-group-text" onclick="togglePassword('current_password', 'toggleCurrentPasswordIcon')">
                        <i class="fas fa-eye-slash" id="toggleCurrentPasswordIcon"></i>
                    </span>
                </div>
                @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mb-4">
                <label for="password">New Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" >
                    <span class="input-group-text" onclick="togglePassword('password', 'togglePasswordIcon')">
                        <i class="fas fa-eye-slash" id="togglePasswordIcon"></i>
                    </span>
                </div>
                @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group mb-4">
                <label for="password_confirmation">Confirm New Password</label>
                <div class="input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" >
                    <span class="input-group-text" onclick="togglePassword('password_confirmation', 'togglePasswordConfirmationIcon')">
                        <i class="fas fa-eye-slash" id="togglePasswordConfirmationIcon"></i>
                    </span>
                </div>
                @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Save changes</button>
        </form>
    </div>

    <script>
        function togglePassword(fieldId, iconId) {
            var passwordField = document.getElementById(fieldId);
            var toggleIcon = document.getElementById(iconId);
            
            // Toggle the password field type
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YWBdKqSMPvld8e8kEHTaVypvxl3lXtDSelwE4k20DdBOx2h74R6TvM7Sdz12tbY" crossorigin="anonymous"></script>
</body>
</html>
