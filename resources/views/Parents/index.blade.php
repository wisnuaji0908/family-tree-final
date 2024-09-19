<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parents List</title>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            padding: 20px; 
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .card-header {
            background-color: #6ed6b9; 
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 15px;
        }

        .btn {
            border-radius: 10px;
            padding: 10px 20px; 
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.95rem;   
        }

        .table {
            margin-top: 15px;
        }

        th, td {
            text-align: left;
            padding: 12px; 
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
            color: black;
        }

        th {
            background-color: #f8f9fa;
            color: black;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        .text-center {
            text-align: center;
        }

        .btn-primary:hover {
            background-color: #5ab1a2; 
            transform: translateY(-2px);
        }

        .btn-primary:focus {
            outline: none; 
            box-shadow: 0 0 5px rgba(110, 214, 185, 0.5); 
        }

        .alert {
            border-radius: 10px; 
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 2rem;
            margin-bottom: 0;
        }

        .header-subtitle {
            font-size: 1.2rem;
            color: #ffffff; 
        }

        .action-buttons {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }

        .action-buttons a, .action-buttons form {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <h1 class="header-title text-center" style="font-size: 2.5rem; font-weight: bold;">Parents List</h1>
                <p class="header-subtitle text-center"></p>
                <a href="{{ route('parents.create') }}" class="btn btn-primary mb-3">Add New Parent</a>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>NO</th> 
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
                                <td>{{ ucfirst($parent->parent) }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('parents.edit', $parent->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('parents.destroy', $parent->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this parent?');">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
