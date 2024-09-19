<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit People</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
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
        }

        .container-fluid {
            width: 100%;
            padding: 0 15px;
            display: flex;
            justify-content: center; 
            align-items: center; 
        }

        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            width: 100%;
            padding: 30px;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        }

        .btn {
            border-radius: 6px;
            padding: 12px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 12px;
            font-size: 0.95rem;
            color: white; /* Set default text color to white */
        }

        .btn.bg-gradient-primary {
            background-color: #007bff; /* Blue color for save button */
        }

        .btn.bg-gradient-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .btn.bg-gradient-danger {
            background-color: #dc3545; /* Red color for cancel button */
        }

        .btn.bg-gradient-danger:hover {
            background-color: #c82333; /* Darker red on hover */
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            display: inline-block;
            font-size: 1rem;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            color: #dc3545;
        }

        /* Hover effect for input */
        .form-control:hover, .form-select:hover {
            border-color: #007bff;
        }

        /* Smooth transition for all elements */
        * {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="card">
        <form action="{{ route('people.update', $person->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h1 class="text-center">Edit People</h1>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="mb-2">
                <label for="name" class="form-label">Name</label>
                <input type="text" 
                    id="name" name="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    placeholder="Enter Name" 
                    value="{{ old('name', $person->name ?? '') }}" />
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                    <option value="male" @if ($person->gender == 'male' || old('gender') == 'male') selected @endif>Male</option>
                    <option value="female" @if ($person->gender == 'female' || old('gender') == 'female') selected @endif>Female</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="place_birth" class="form-label">Place Birth</label>
                <input type="text" class="form-control @error('place_birth') is-invalid @enderror" 
                       id="place_birth" name="place_birth" 
                       placeholder="Place of Birth" 
                       value="{{ old('place_birth', $person->place_birth) }}">
                @error('place_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="birth_date" class="form-label">Birth Date</label>
                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                    id="birth_date" name="birth_date" 
                    value="{{ old('birth_date', $person->birth_date) }}" 
                    max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                @error('birth_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="death_date" class="form-label">Death Date</label>
                <input type="date" class="form-control @error('death_date') is-invalid @enderror" 
                    id="death_date" name="death_date" 
                    value="{{ $person->death_date ? \Carbon\Carbon::parse($person->death_date)->format('Y-m-d') : '' }}" 
                    max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                @error('death_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="text-end">
                <a href="{{ route('people.index') }}" class="btn bg-gradient-danger">
                    <i class="fas fa-times-circle"></i> Cancel
                </a>
                <button type="submit" class="btn bg-gradient-primary">
                    <i class="fas fa-check-circle"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>
    <script>
        document.querySelector('.btn.bg-gradient-danger').addEventListener('click', function(event) {
            event.preventDefault();
            var userConfirmed = confirm('Are you sure you want to cancel? Unsaved changes will be lost.');
            
            if (userConfirmed) {
                window.location.href = this.href;
            }
        });
    </script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </body>
</html>
