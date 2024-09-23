<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Parent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style tetap sama */
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1 class="mb-0 text-center">Add New Parent</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('parents.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="people_id" class="form-label">Person</label>
                        <select name="people_id" id="people_id" class="form-select" required>
                            @foreach ($people as $person)
                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('parent_id')
                        <p>
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent</label>
                        <select name="parent_id" id="parent_id" class="form-select">
                            @foreach ($people as $person)
                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('parent_id')
                        <p>
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="mb-3">
                        <label for="parent" class="form-label">Parent Role</label>
                        <select name="parent" id="parent" class="form-select" required>
                            <option value="father">Father</option>
                            <option value="mother">Mother</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('parents.index') }}" class="btn btn-secondary">Cancel</a>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
