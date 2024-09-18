<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
        }

        .container-fluid {
            padding: 20px; 
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #6ed6b9; 
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 20px;
        }

        .btn {
            border-radius: 10px;
            padding: 10px 20px; 
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            
        }

        .btn.btn-primary {
            background-color: #6ed6b9; 
            color: white;
        }

        .btn.bg-gradient-success {
            background: linear-gradient(45deg, #8dd8a1, #6ed6b9); 
            color: white;
        }

        .btn.bg-gradient-danger {
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            text-align: left;
            padding: 12px; 
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #f8f9fa;
            color: #495057;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        .text-center {
            text-align: center;
        }

        .btn-primary {
        background-color: #6ed6b9; 
        color: white;
        border: none; 
        padding: 10px 20px; 
        border-radius: 10px;
        font-weight: 600; 
        transition: background-color 0.3s ease, transform 0.3s ease; 
        display: inline-flex;
        align-items: center;
        }

        .btn-primary:hover {
            background-color: #5ab1a2; 
            transform: translateY(-2px);
        }

        .btn-primary:focus {
            outline: none; 
            box-shadow: 0 0 5px rgba(110, 214, 185, 0.5); 
        }

    </style>
</head>
<body>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header pb-0">
                    <h1>People Admin</h1>
                    <a href="{{ route('admin.create') }}" class="btn btn-primary"> [+] Add People</a>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="datatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Gender</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Place of Birth</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Birth Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Death Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
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
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    {{ $i + 1 . ' . ' }}
                                                </div>
                                            </td>
                                            <td>
                                                {{ $data->name }}
                                            </td>
                                            <td>
                                                {{ $data->gender }}
                                            </td>
                                            <td>
                                                {{ $data->place_birth }}
                                            </td>
                                            <td>
                                                {{ $data->birth_date }}
                                            </td>
                                            <td>
                                                @if(empty($data->death_date))
                                                    <span class="text-danger" style="color: red;">Death Date Not Provided</span>
                                                @else
                                                    {{ $data->death_date }}
                                                @endif
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('admin.edit', $data->id) }}" class="me-2">
                                                        <span class="btn btn-sm bg-gradient-success">Edit</span>
                                                    </a>
                                                    <form action="{{ route('admin.destroy', $data->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm bg-gradient-danger" onclick="return confirm('Are you sure you want to delete this person?')">Delete</button>
                                                    </form>
                                                </div>
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

</body>
</html>
