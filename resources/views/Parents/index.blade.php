<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parents List Admin</title>
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
        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
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
                        <h5 class="mb-0" style="font-size: 20px;">Parents List</h5>
                        <a href="{{ route('parents.create') }}" class="btn btn-add">
                            [+] Add Parent
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-0 mt-3">
                            <table class="table align-items-center mb-0" id="datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Person</th>
                                        <th>Parent Name</th>
                                        <th>Parent Role</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($parents as $index => $parent)
                                        <tr>
                                            <td>{{ sprintf('%d.', $index + 1) }}</td>
                                            <td>{{ $parent->people->name ?? 'N/A' }}</td> 
                                            <td>{{ $parent->userParent->name }}</td> 
                                            <td>{{ ucfirst($parent->parent) }}</td> 
                                            <td class="text-center action-buttons">
                                                <a href="{{ route('parents.edit', $parent->id) }}" class="btn btn-sm btn-edit me-2">Edit</a>
                                                <form action="{{ route('parents.destroy', $parent->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
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
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $parents->links('pagination::bootstrap-4') }}
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
