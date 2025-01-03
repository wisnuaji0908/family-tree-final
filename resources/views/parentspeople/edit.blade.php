<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting?->app_name ?? config('app.name') }} - Edit Parents</title>
    <link rel="icon" href="{{ $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">
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
        }

        .btn.bg-gradient-danger {
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white;
        }

        .btn.bg-gradient-danger:hover {
            background: linear-gradient(45deg, #c82333, #bd2130);
        }

        .btn.bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
        }

        .btn.bg-gradient-primary:hover {
            background: linear-gradient(45deg, #0056b3, #003f7f);
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
    <div class="card">
        <form action="{{ route('parentspeople.update', $parent->id) }}" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <h1 class="text-center">Edit Parent</h1>
            <div class="card-body">
                <form action="{{ route('parentspeople.update', $parent->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="people_id" class="form-label">Person</label>
                        <select name="people_id" id="people_id" class="form-select" required>
                            <option value="">Select Person</option>
                            @foreach ($people as $person)
                                <option value="{{ $person->id }}" {{ $parent->people_id == $person->id ? 'selected' : '' }}>
                                    {{ $person->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent</label>
                        <select name="parent_id" id="parent_id" class="form-select" required>
                            <option value="" selected disabled>Pilih Parent</option>
                            @foreach ($people as $person)
                                <option value="{{ $person->id }}" data-gender="{{ $person->gender }}">
                                    {{ $person->name }} ({{ ucfirst($person->gender) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="parent" class="form-label">Parent Role</label>
                        <select name="parent" id="parent" class="form-select" required>
                            <option value="" disabled selected>Pilih Role</option>
                        </select>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('parentspeople.index') }}" class="btn bg-gradient-danger" onclick="return confirm('Are you sure you want to cancel? Unsaved changes will be lost.');">
                            <i class="fas fa-times-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-check-circle"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </form>
            @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
            @endif
    </div>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
    document.getElementById('parent_id').addEventListener('change', function () {
        const gender = this.options[this.selectedIndex].dataset.gender;
        const roleSelect = document.getElementById('parent');
        roleSelect.innerHTML = ''; // Reset options

        if (gender === 'male') {
            roleSelect.innerHTML = '<option value="father">Father</option>';
        } else if (gender === 'female') {
            roleSelect.innerHTML = '<option value="mother">Mother</option>';
        }
    });
</script>
</body>
</html>
