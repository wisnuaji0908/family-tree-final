<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parents List Admin</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
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
        .btn-view {
            background-color: #51A783;
            color: white;
            font-size: 15px;
        }
        .table {
            margin: 0 auto; /* Rata tengah */
            border-collapse: collapse;
            font-size: 15px;
            width: 95%; /* Lebar tabel */
        }

        th, td {
            text-align: left;
            padding: 12px; /* Tingkatkan padding untuk konsistensi */
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

        /* css untuk diagram modal */
        .diagram-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        }

        .parents {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            width: 100%;
        }

        .parent {
            border: 2px solid #007bff;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            width: 150px;
            position: relative;
        }

        .connector {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-bottom: 20px;
            position: relative;
        }

        .connector .line {
            height: 2px;
            width: 50%;
            background-color: #007bff;
            position: relative;
        }

        .children {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .person {
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            width: 150px;
            position: relative;
            
        }

        /* Garis penghubung */
        .parents .parent:after {
            content: '';
            display: block;
            width: 2px;
            height: 23px;
            background: #007bff;
            position: absolute;
            left: 50%;
            top: 100%;
            transform: translateX(-50%);
        }

        .children .person:before {
            content: '';
            display: block;
            width: 2px;
            height: 21px;
            background: #007bff;
            position: absolute;
            left: 50%;
            top: -22px; /* Mengatur posisi agar di atas anak */
            transform: translateX(-50%);
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
                                            <td>{{ ($parents->currentPage() - 1) * $parents->perPage() + $index + 1 }}.</td>
                                            <td>{{ $parent->people->name ?? 'N/A' }}</td> 
                                            <td>{{ $parent->userParent->name }}</td> 
                                            <td>{{ ucfirst($parent->parent) }}</td> 
                                            <td class="text-center action-buttons">
                                                @if(auth()->user()->id === $parent->user_id)
                                                    <a href="{{ route('parents.edit', $parent->id) }}" class="btn btn-sm btn-edit me-2">Edit</a>
                                                    <form action="{{ route('parents.destroy', $parent->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                    <button type="button" class="btn btn-sm btn-view" data-bs-toggle="modal" data-people-id="{{ $parent->user_id }}" onclick="showParentModal({{ $parent->people_id }})" data-bs-target="#parentsModal">View Parents</button>
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

    <div class="d-flex justify-content-center mt-4">
        {{ $parents->links('pagination::bootstrap-4') }}
    </div>

   <!-- Modal -->
   <div class="modal fade" id="parentsModal" tabindex="-1" aria-labelledby="parentsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="parentsModalLabel">Diagram Parents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="diagram-container">
                    <div class="parents">
                        <div class="parent mother" id="mother">
                            <h6>Mother</h6>
                            <!-- Konten dari JS -->
                        </div>
                        <div class="parent father" id="father">
                            <h6>Father</h6>
                            <!-- Konten dari JS -->
                        </div>
                    </div>
                    <div class="connector">
                        <span class="line"></span>
                    </div>
                    <div class="children">
                        <div class="person" id="person">
                            <h6>Person</h6>
                            <!-- Konten dari JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <script>
        function showParentModal(id) {
    
            $('#mother').empty();
            $('#father').empty();
            $('#person').empty();

        
        $.ajax({
            url: `/get-parent/${id}`,  // Endpoint backend yang akan memberikan data orang tua
            type: 'GET',
            success: function(res) {
                
                
                if (res.data.mother.length > 0) {
                    $.each(res.data.mother, function(index, value) {
                        $('#mother').append(`<p>${value.user_parent.name} (${value.parent})</p>`);
                    });
                } else {
                    $('#mother').append('<p>No data for mother</p>');
                }

                
                if (res.data.father.length > 0) {
                    $.each(res.data.father, function(index, value) {
                        $('#father').append(`<p>${value.user_parent.name} (${value.parent})</p>`);
                    });
                } else {
                    $('#father').append('<p>No data for father</p>');
                }

                // Menampilkan data untuk person/child
                if (res.data.person) {
                    $('#person').append(`<p>${res.data.person.name}</p>`);
                } else {
                    $('#person').append('<p>{{ $parent->people->name ?? 'N/A' }}</p>');
                }
            },
            error: function(err) {
                console.error("Error fetching parent data:", err);
                $('#mother').append('<p>Error fetching data</p>');
                $('#father').append('<p>Error fetching data</p>');
                $('#person').append('<p>Error fetching data</p>');
            }
        });
    }

    </script>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>