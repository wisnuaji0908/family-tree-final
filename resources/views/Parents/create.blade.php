<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Parent Admin</title>
    <!-- Google Font -->
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
        overflow: hidden;
    }

    .container-fluid {
        width: 100%;
        padding: 0;
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

    .btn.bg-gradient-primary {
        background: linear-gradient(45deg, #dc3545, #c82333); /* Merah untuk Cancel */
        color: white;
    }

    .btn.bg-gradient-primary:hover {
        background: linear-gradient(45deg, #c82333, #a71c1f);
    }

    .btn.bg-gradient-success {
        background: linear-gradient(45deg, #007bff, #0056b3); /* Biru untuk Save */
        color: white;
    }

    .btn.bg-gradient-success:hover {
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
    <div class="row justify-content-center">
        <div class="col-12">
            <form action="{{ route('parents.store') }}" method="POST">
                @csrf
                <h1>Create Parent Admin</h1>
                <div class="mb-2">
                    <label for="user_id" class="form-label">User</label>
                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                        <option value="" disabled selected>Select User</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user->id ?>"><?= $user->email ?></option>
                        <?php endforeach; ?>
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="people_id" class="form-label">People</label>
                    <select name="people_id" id="people_id" class="form-select @error('people_id') is-invalid @enderror">
                        <option value="" disabled selected>Select Person</option>
                        <?php foreach ($people as $person): ?>
                            <option value="<?= $person->id ?>"><?= $person->name ?></option>
                        <?php endforeach; ?>
                    </select>
                    @error('people_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="parent" class="form-label">Parent Role</label>
                    <select name="parent" id="parent" class="form-select @error('parent') is-invalid @enderror">
                        <option value="father" @if (old('parent') == 'father') selected @endif>Father</option>
                        <option value="mother" @if (old('parent') == 'mother') selected @endif>Mother</option>
                    </select>
                    @error('parent')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <a href="/parents" class="btn bg-gradient-primary btn-cancel" onclick="return confirm('Are you sure you want to cancel?');">
                        <i class="fas fa-times-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn bg-gradient-success btn-save">
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
