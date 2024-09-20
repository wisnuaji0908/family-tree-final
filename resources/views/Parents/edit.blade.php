<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Parent</title>
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
        }

        .btn.bg-gradient-danger {
            background: linear-gradient(45deg, #dc3545, #c82333); /* Warna merah */
            color: white;
        }

        .btn.bg-gradient-danger:hover {
            background: linear-gradient(45deg, #c82333, #bd2130); /* Warna merah lebih gelap */
        }

        .btn.bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #0056b3); /* Warna biru */
            color: white;
        }

        .btn.bg-gradient-primary:hover {
            background: linear-gradient(45deg, #0056b3, #003f7f); /* Warna biru lebih gelap */
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
        <form action="{{ route('parents.update', $parent->id) }}" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <h1 class="text-center">Edit Parent</h1>
            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $parent->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="people_id" class="form-label">Parent Name</label>
                <select name="people_id" id="people_id" class="form-select">
                    <option value="">Select Parent</option>
                    @foreach ($people as $person)
                        <option value="{{ $person->id }}" {{ $parent->people_id == $person->id ? 'selected' : '' }}>
                            {{ $person->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="parent" class="form-label">Parent Role</label>
                <select name="parent" id="parent" class="form-select">
                    <option value="father" {{ $parent->parent == 'father' ? 'selected' : '' }}>Father</option>
                    <option value="mother" {{ $parent->parent == 'mother' ? 'selected' : '' }}>Mother</option>
                </select>
            </div>

            <div class="text-end">
                <a href="{{ route('parents.index') }}" class="btn bg-gradient-danger" onclick="return confirm('Are you sure you want to cancel? Unsaved changes will be lost.');">
                    Cancel
                </a>
                <button type="submit" class="btn bg-gradient-primary">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>