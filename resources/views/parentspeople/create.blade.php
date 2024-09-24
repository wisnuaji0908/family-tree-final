<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Parent</title>
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
            <form action="{{ route('parentspeople.store') }}" method="POST">
                @csrf
                <h1 class="text-center">Create Parent</h1>

                <div class="mb-3">
                    <label for="people_id" class="form-label">Person</label>
                    <select name="people_id" id="people_id" class="form-select" required>
                        @foreach ($people as $person)
                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                        @endforeach
                    </select>
                    @error('people_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="parent_id" class="form-label">Parent</label>
                    <select name="parent_id" id="parent_id" class="form-select">
                        @foreach ($people as $person)
                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="parent" class="form-label">Parent Role</label>
                    <select name="parent" id="parent" class="form-select" required>
                        <option value="father">Father</option>
                        <option value="mother">Mother</option>
                    </select>
                    @error('parent')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <a href="{{ route('parentspeople.index')}}" class="btn bg-gradient-danger btn-cancel" onclick="return confirm('Are you sure you want to cancel?');">
                        <i class="fas fa-times-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn bg-gradient-primary btn-save">
                        <i class="fas fa-check-circle"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
