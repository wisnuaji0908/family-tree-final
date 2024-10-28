<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting?->app_name ?? config('app.name') }} - People Admin</title>
    <link rel="icon" href="{{ $setting && $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
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
            margin: 0 auto;
            border-collapse: collapse;
            font-size: 15px;
            width: 95%;
        }
        th, td {
            text-align: left;
            padding: 12px;
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
        .search-container {
            margin-bottom: 20px;
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
                        <h5 class="mb-0" style="font-size: 20px;">People List</h5>
                        <a href="{{ route('admin.create') }}" class="btn btn-add">[+] Add People</a>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.index') }}" class="mb-3">
                            <div class="input-group mb-3" style="width: 600px; margin: 0 auto;">
                                <input type="text" name="query" class="form-control" placeholder="Search..." value="{{ request()->input('query') }}" style="border-radius: 10px 0 0 10px;">
                                <button class="btn btn-outline-success" type="submit" style="border-radius: 0 10px 10px 0; background-color: #51A783; color: white;">Search</button>
                            </div>
                        </form>
                        @if ($message = Session::get('data_added'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ $message }} Table data successfully added.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="table-responsive p-0 mt-3">
                            <table class="table align-items-center mb-0" id="datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Place of Birth</th>
                                        <th>Birth Date</th>
                                        <th>Death Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($people->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center">No data available.</td>
                                        </tr>
                                    @else
                                        @foreach ($people as $i => $data)
                                            <tr>
                                                <td>{{ ($people->currentPage() - 1) * $people->perPage() + $i + 1 }}.</td>
                                                <td>{{ $data->name }}</td>
                                                <td>{{ $data->gender }}</td>
                                                <td>{{ $data->place_birth }}</td>
                                                <td>{{ $data->birth_date }}</td>
                                                <td>
                                                    @if(empty($data->death_date))
                                                        <span class="text-danger">Death Date Not Provided</span>
                                                    @else
                                                        {{ $data->death_date }}
                                                    @endif
                                                </td>
                                                <td class="text-center action-buttons">
                                                    @if(Auth::user()->role === 'admin' && $data->user_id === Auth::user()->id)
                                                        <a href="{{ route('admin.edit', $data->id) }}" class="btn btn-sm btn-edit me-2">Edit</a>
                                                        <form action="{{ route('admin.destroy', $data->id) }}" method="POST" class="d-inline">
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
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $people->links('pagination::bootstrap-4') }}
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





