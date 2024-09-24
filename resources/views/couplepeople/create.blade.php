<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Couple People</title>
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

    .form-control:hover, .form-select:hover {
        border-color: #007bff;
    }

    * {
        transition: all 0.3s ease;
    }

    h1 {
    text-align: center;
    margin-bottom: 20px;
    }

    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="form-container">
                <h1 class="text-center">Create Couple</h1>
                <p class="text-center" style="font-size: 1rem; margin-bottom: 20px;">Silakan isi informasi pasangan di bawah ini:</p> <!-- Tambahkan teks di sini dengan gaya -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('peoplecouple.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="people_id" class="form-label">Person</label>
                        <select name="people_id" id="people_id" class="form-select" required>
                            @foreach($people as $person)
                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="couple_id" class="form-label">Partner</label>
                        <select name="couple_id" id="couple_id" class="form-select" required>
                            @foreach($people as $person)
                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="married_date" class="form-label">Married Date</label>
                        <input type="date" name="married_date" id="married_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="divorce_date" class="form-label">Divorce Date (Optional)</label>
                        <input type="date" name="divorce_date" id="divorce_date" class="form-control">
                    </div>
                    <div class="text-end">
                        <a href="{{ route('peoplecouple.index') }}" class="btn bg-gradient-danger btn-cancel" onclick="return confirm('Are you sure you want to cancel?');">
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
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('married_date').setAttribute('max', today);
    document.getElementById('divorce_date').setAttribute('max', today);
</script>

</body>
</html>
