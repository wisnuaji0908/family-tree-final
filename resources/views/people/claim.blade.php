<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim People</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    {{-- Bootstrap 5 --}}
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
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
    }

    .btn.bg-gradient-primary:hover {
        background: linear-gradient(45deg, #0056b3, #003f7f);
    }

    .btn.bg-gradient-success {
        background: linear-gradient(45deg, #28a745, #218838);
        color: white;
    }

    .btn.bg-gradient-success:hover {
        background: linear-gradient(45deg, #218838, #1e7e34);
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
            <form action="{{ route('people.claim.process') }}" method="POST">
                @csrf
                <h2 class="text-center">Claim Account</h2>
                <p class="text-center" style="font-size: 10px;">Please claim your account first.</p>
                @if ($errors->has('error'))
                    <div class="alert alert-danger">
                        {{ $errors->first('error') }}
                    </div>
                @endif
                <div class="form-floating mb-3">
                    <select class="form-select {{ $errors->has('person_id') ? 'is-invalid' : '' }}" id="floatingSelect" aria-label="Floating label select example" name="person_id">
                      <option selected disabled>Select Person</option>
                      @foreach ($people as $person)
                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                        @endforeach
                    </select>
                    @error('person_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <label for="floatingSelect">Choose One</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="date" class="form-control {{ $errors->has('birth_date') ? 'is-invalid' : '' }}" id="floatingInput" name="birth_date" value="{{ old('birth_date', request('birth_date')) }}">
                    <label for="floatingInput">Birth Date</label>
                @error('birth_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control {{ $errors->has('place_birth') ? 'is-invalid' : '' }}" placeholder="Enter Your Birth Place" id="floatingTextarea" style="height: 150px;" name="place_birth">{{ old('place_birth', request('place_birth')) }}</textarea>
                    <label for="floatingTextarea">Birth Place</label>
                @error('place_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                </div>
                <div class="d-grid gap-2">
                    <input type="submit" value="Claim" class="btn btn-primary" style="width: 100%">
                </div>                  
            </form>
        </div>
    </div>
</div>
</body>
</html>
