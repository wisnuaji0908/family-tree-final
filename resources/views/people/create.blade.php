<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting?->app_name ?? config('app.name') }} - Create People</title>  
    <link rel="icon" href="{{ $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
    body {
        background-color: #f5f7fa;
        font-family: 'Poppins', sans-serif;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0;
        margin: 0;
        overflow: hidden;
        font-size: 0.9rem; 
    }

    form {
        background-color: white;
        border-radius: 12px;
        padding: 30px; 
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        max-width: 700px;
        width: 100%;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 8px; 
        display: inline-block;
    }

    .form-control, .form-select {
        font-size: 0.85rem; 
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #ced4da;
    }

    .btn {
        font-size: 0.90rem; 
        border-radius: 6px;
        padding: 12px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-right: 12px;
    }

    .btn.bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
    }

    .btn.bg-gradient-primary:hover {
        background: linear-gradient(45deg, #0056b3, #003f7f);
    }

    .btn.bg-gradient-danger {
        background: linear-gradient(45deg, #dc3545, #c82333);
        color: white;
    }

    .btn.bg-gradient-danger:hover {
        background: linear-gradient(45deg, #c82333, #bd2130);
    }

    /* Hover effect for input */
    .form-control:hover, .form-select:hover {
        border-color: #007bff;
    }

    * {
        transition: all 0.3s ease;
    }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <form action="{{ route('people.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <h1 class="text-center">Create People</h1>
                <div class="mb-2">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" 
                        id="name" name="name" 
                        class="form-control @error('name') is-invalid @enderror" 
                        placeholder="Enter Name" aria-label="name" 
                        value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select @error('gender') is-invalid @enderror" 
                            id="gender" name="gender" aria-label="Gender">
                        <option value="" disabled selected>Select Gender</option>
                        <option value="male" @if (old('gender') == 'male') selected @endif>Male</option>
                        <option value="female" @if (old('gender') == 'female') selected @endif>Female</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="place_birth" class="form-label">Place Birth</label>
                    <input type="text" 
                           class="form-control @error('place_birth') is-invalid @enderror" 
                           placeholder="Place of Birth" 
                           id="place_birth" name="place_birth" 
                           value="{{ old('place_birth') }}">
                    @error('place_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="birth_date" class="form-label">Birth Date (Optional)</label>
                    <input type="date" 
                        class="form-control @error('birth_date') is-invalid @enderror" 
                        id="birth_date" name="birth_date" 
                        value="{{ old('birth_date') }}">
                    @error('birth_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const today = new Date().toISOString().split("T")[0];
                        document.getElementById("birth_date").setAttribute("max", today);
                    });
                </script>
                <div class="mb-3">
                    <label for="death_date" class="form-label">Death Date</label>
                    <input type="date" 
                        class="form-control @error('death_date') is-invalid @enderror" 
                        id="death_date" name="death_date" 
                        value="{{ old('death_date') }}">
                    @error('death_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const today = new Date().toISOString().split("T")[0];
                        document.getElementById("death_date").setAttribute("max", today);
                    });
                </script>
                <div class="text-end">
                <a href="{{ route('people.index')}}" class="btn bg-gradient-danger btn-danger" onclick="return confirm('Are you sure you want to cancel?');">
                    <i class="fas fa-times-circle"></i> Cancel
                </a>
                <button type="submit" class="btn bg-gradient-primary btn-save">
                    <i class="fas fa-check-circle"></i> Save
                </button>
            </div>
                <script src="https://kit.fontawesome.com/a076d05399.js"></script>
            </form>
        </div>
    </div>
</div>
</body>
</html>
