<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Custom CSS */
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
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
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-add {
            background-color: #007bff; 
            border: none;
            padding: 13px 30px; 
            font-size: 14px; 
            font-weight: 600; 
            color: white;
            border-radius: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.3); 
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px; 
        }
        .btn-edit {
            background-color: #007bff; 
            color: white;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .table {
            margin: 0 20px;
            max-width: 95%; 
            margin-left: auto;
            margin-right: auto; 
            border-collapse: collapse;
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
    </style>
</head>
<body>

<!-- Include Navbar -->
@include('navbar')

<div class="container-fluid py-0"> 
    <div class="row">
        <div class="col-12">
            <div class="card my-2">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="font-size: 20px;">People List</h5>
                    <div class="text-end">
                        <a href="{{ route('people.create') }}" class="btn btn-add">
                            <span class="btn-add-icon"></span> [+] Add People
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($message = Session::get('added'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ $message }} Data berhasil ditambahkan.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($message = Session::get('data_added'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ $message }} Data tabel berhasil ditambahkan.
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
                                            <td>{{ sprintf('%d.', $i + 1) }}</td>
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
                                            <td class="text-center">
                                                <a href="{{ route('people.edit', $data->id) }}" class="btn btn-sm btn-edit">Edit</a>
                                                <form action="{{ route('people.destroy', $data->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
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
