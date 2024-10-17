<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting?->app_name ?? config('app.name') }} - Couple Admin</title> 
    <link rel="icon" href="{{ $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        /* Custom CSS */
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
            font-size: 15px; 
        }
        .container-fluid {
            padding: 0; 
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            margin-top: 5px;
        }
        .card-header {
            background-color: #51A783;
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 15px; 
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px; 
        }
        .btn-add {
            background-color: #0056b3; 
            border: none;
            padding: 10px 35px; 
            font-size: 15px; 
            font-weight: 600; 
            color: white;
            border-radius: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.3); 
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px; 
        }
        .btn-edit {
            background-color: #f0ad4e; 
            color: white;
            font-size: 15px; 
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            font-size: 15px;
        }
        .table {
            margin: 0 20px;
            max-width: 95%; 
            margin-left: auto;
            margin-right: auto; 
            border-collapse: collapse;
            font-size: 15px;
        }
        th, td {
            text-align: left;
            padding: 10px; 
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #51A783;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e2f0e8;
        }
        .text-danger {
            font-weight: bold;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 15px; 
        }

    </style>
</head>
<body>
    <!-- Include Navbar -->
    @include('navbar')

    <div class="container-fluid py-0"> 
        <div class="row">
            <div class="col-12">
                <div class="card my-2">
                    <div class="card-header pb-0">
                        <h5 class="mb-0" style="font-size: 20px;">Couple List</h5> 
                        <a href="{{ route('couple.create') }}" class="btn btn-add">
                            <span class="btn-add-icon"></span> [+] Add Couple
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ $message }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="card-body px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th> 
                                            <th>Person</th>
                                            <th>Partner</th>
                                            <th>Married Date</th>
                                            <th>Divorce Date</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($couple as $index => $coupleData)
                                            <tr>
                                                <td>{{ ($couple->currentPage() - 1) * $couple->perPage() + $index + 1 }}.</td>
                                                <td>{{ $coupleData->people->name }}</td>
                                                <td>{{ $coupleData->partner->name }}</td>
                                                <td>{{ $coupleData->married_date }}</td>
                                                <td class="{{ $coupleData->divorce_date ? '' : 'text-danger' }}">
                                                    {{ $coupleData->divorce_date ?? 'Divorce Date Not Provided' }}
                                                </td>
                                                <td class="text-center action-buttons">
                                                @if(auth()->user()->id === $coupleData->user_id)
                                                    <a href="{{ route('couple.edit', $coupleData->id) }}" class="btn btn-sm btn-edit me-2">Edit</a>
                                                    <form action="{{ route('couple.destroy', $coupleData->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">No actions available</span>
                                                @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <div class="d-flex justify-content-center mt-4">
        {{ $couple->links('pagination::bootstrap-4') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
