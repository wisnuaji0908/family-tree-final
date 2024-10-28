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
            background-color: #f8f9fa;
        }

        .container {
            max-width: 700px; /* Lebih besar dari sebelumnya */
            margin-top: 50px;
            background-color: white;
            padding: 40px; /* Menambah padding agar terlihat lebih luas */
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Sedikit memperbesar bayangan */
        }

        .card-header {
            font-weight: 600;
            text-align: center;
            color: #ffffff;
        }

        .form-group input {
            font-size: 1.2rem; /* Lebih besar */
            padding: 12px; /* Padding lebih besar */
            border-radius: 4px;
        }

        .btn-primary {
            font-weight: 500;
            font-size: 1.1rem;
            padding: 12px; /* Menambah padding tombol */
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
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary">
                        <h5 class="mb-0">Please enter your new phone number</h5>
                    </div>
                    <div class="card-body p-5"> <!-- Tambahkan padding di dalam card body -->
                        <form action="{{ route('change-phone-customer') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="13"
                                    class="form-control text-center" id="phone"
                                    name="phone" placeholder="Phone" value="{{ auth()->user()->phone }}" required>
                                @error('phone')
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

