<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Couple</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-header {
            background-color: #6ed6b9; 
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 30px;
        }
        .btn-custom {
            background-color: #6ed6b9;
            color: white;
        }
        .btn-custom:hover {
            background-color: #003f7f;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Create Couple</h2>
            </div>
            <div class="card-body">
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

                <form action="{{ route('couple.store') }}" method="POST">
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
                    <button type="submit" class="btn btn-custom">Save</button>
                    <a href="{{ route('couple.index')}}" class="btn btn-danger">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
