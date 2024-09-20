<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Parent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        th, td {
            text-align: left;
            padding: 12px; 
            color: black; /* Teks berwarna hitam */
        }

        th {
            background-color: #f8f9fa; /* Warna latar belakang header */
            color: black; /* Teks header berwarna hitam */
        }

        .text-center {
            text-align: center;
        }

        .form-label {
            font-weight: 600; /* Teks label menjadi lebih tebal */
        }

        .btn-primary:hover {
            background-color: #5ab1a2; 
            transform: translateY(-2px);
        }
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
                        <label for="user_id" class="form-label">User</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user->id ?>"><?= $user->email ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="people_id" class="form-label">People</label>
                        <select name="people_id" id="people_id" class="form-select">
                            <?php foreach ($people as $person): ?>
                                <option value="<?= $person->id ?>"><?= $person->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="parent" class="form-label">Parent Role</label>
                        <select name="parent" id="parent" class="form-select">
                            <option value="father">Father</option>
                            <option value="mother">Mother</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="/parents" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
