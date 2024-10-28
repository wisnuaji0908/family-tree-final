<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validate OTP - {{ $setting?->app_name ?? config('app.name') }}</title>
    <link rel="icon" href="{{ $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 500px;
            margin-top: 100px;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            font-weight: 600;
            text-align: center;
            color: #ffffff;
            background-color: #007bff;
        }

        .form-group input {
            font-size: 1.1rem;
            padding: 10px;
            border-radius: 4px;
        }

        .btn-primary {
            font-weight: 500;
            font-size: 1rem;
            padding: 10px;
        }

        .invalid-feedback {
            font-size: 0.875rem;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h6 class="mb-0 text-center">Please enter the OTP code sent to 
                            <strong>{{ substr(auth()->user()->phone, 0, 2) . '*' . substr(auth()->user()->phone, -2) }}</strong>
                        </h6>                      
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('validate-otp-phone') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');" minlength="6" maxlength="6"
                                    class="form-control text-center @error('otp') is-invalid @enderror" id="otp"
                                    name="otp" placeholder="OTP Code" required>
                                @error('otp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YWBdKqSMPvld8e8kEHTaVypvxl3lXtDSelwE4k20DdBOx2h74R6TvM7Sdz12tbY" crossorigin="anonymous"></script>
</body>
</html>
