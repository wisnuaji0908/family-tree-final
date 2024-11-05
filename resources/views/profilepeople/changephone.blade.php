<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $setting?->app_name ?? config('app.name') }} - Change Phone Number</title>  
    <link rel="icon" href="{{ $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        .card-header {
            font-weight: 600;
            font-size: 1.25rem;
            text-align: center;
            color: #4a4a4a;
            background-color: #28a745;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }

        .form-group input {
            font-size: 1.1rem;
            padding: 12px;
            border: 1px solid #d1d7dd;
            border-radius: 6px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .btn-primary {
            font-size: 1.1rem;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .invalid-feedback {
            font-size: 0.875rem;
            color: #dc3545;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <main class="container">
        <div class="card border-0">
            <div class="card-header">
                <h5 class="mb-0 text-black">Enter Your New Phone Number</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('change-phone-people') }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="13"
                            class="form-control text-center" id="phone"
                            name="phone" placeholder="Phone Number" value="{{ auth()->user()->phone }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YWBdKqSMPvld8e8kEHTaVypvxl3lXtDSelwE4k20DdBOx2h74R6TvM7Sdz12tbY" crossorigin="anonymous"></script>
</body>
</html>
