<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Couple List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-header {
            background-color: #6ed6b9; 
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 30px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-custom {
            background-color: #6ed6b9;
            color: white;
            border: 3px solid white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
            color: white;
        }
        .btn-custom-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Couple List</h2>
                <a href="{{ route('couple.create') }}" class="btn btn-custom">Add New Couple</a>
            </div>

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Person</th>
                                <th>Partner</th>
                                <th>Married Date</th>
                                <th>Divorce Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($couple as $couple)
                                <tr>
                                    <td>{{ $couple->people->name }}</td>
                                    <td>{{ $couple->partner->name }}</td>
                                    <td>{{ $couple->married_date }}</td>
                                    <td>{{ $couple->divorce_date }}</td>
                                    <td>
                                        <a href="{{ route('couple.edit', $couple->id) }}" class="btn btn-warning btn-custom-sm">Edit</a>
                                        <form action="{{ route('couple.destroy', $couple->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-custom-sm" onclick="return confirm('Are you sure you want to delete this person?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
