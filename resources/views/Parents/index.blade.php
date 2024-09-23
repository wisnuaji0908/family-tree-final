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
        /* Custom CSS from People Admin */
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

        <div class="card">
            <div class="card-header">
                <h1 class="text-center">Parents List</h1>
                <a href="{{ route('parents.create') }}" class="btn btn-primary mb-3">Add New Parent</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>NO</th> 
                            <th>Person</th>
                            <th>Parent Name</th>
                            <th>Parent Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parents as $index => $parent)
                            <tr>
                            <td>{{ $index + 1 }}</td> 
                            <td>{{ $parent->people->name ?? 'N/A' }}</td> 
                            <td>{{ $parent->userParent->name }}</td> 
                            <td>{{ ucfirst($parent->parent) }}</td> 
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('parents.edit', $parent->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('parents.destroy', $parent->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this parent?');">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
