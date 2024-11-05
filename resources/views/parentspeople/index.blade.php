<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting?->app_name ?? config('app.name') }} - Parents</title>  
    <link rel="icon" href="{{ $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://d3js.org/d3.v7.min.js"></script>

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
            background-color:#51A783;
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

        /* css utuk diagram modal  */
        .diagram-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .parents {
            display: flex;
            justify-content: space-between;
            width: 400px; /* Sesuaikan ukuran */
            position: relative;
        }

        .connector-horizontal {
            position: relative;
            display: flex;
            justify-content: center;
            width: 400px; /* Ukuran yang sama dengan parents untuk menjaga keselarasan */
        }

        .line-horizontal {
            width: 75px; /* Panjang garis horizontal */
            height: 2px;
            background-color: green;
            position: absolute;
            left: 160px;
            top: -60px;  /* Mengatur posisi garis agar berada di tengah antara mother dan father */
        }

        .connector-vertical {
            display: flex;
            justify-content: center;
            margin: 0;
            position: relative;
            width: 400px; 
        }

        .line-vertical {
            width: 2px;
            height: 140px; /* Sesuaikan dengan tinggi yang Anda inginkan */
            background-color: green;
            position: relative;
            top: -60px; /* Posisikan agar garis vertikal menyambung dengan horizontal */
            left: -3px; /* Geser garis ke kiri */
            margin-left: -1px; 
        }

        .parent {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .children {
        margin-top: 0px; /* Hilangkan jarak antara garis vertikal dan kotak person */
        display: flex;
        justify-content: center;
        align-items: center;
        }

        #person {
            margin-top: -67px; /* Geser kotak person agar lebih dekat ke garis vertikal */
        }


    </style>
</head>
<body>
    

    <!-- Include Navbar -->
    @include('nav')

    <div class="container-fluid py-0"> 
        <div class="row">
            <div class="col-12">
                <div class="card my-2">
                    <div class="card-header pb-0">
                        <h5 class="mb-0" style="font-size: 20px;">Parents List</h5>
                        <a href="{{ route('parentspeople.create') }}" class="btn btn-add">
                            [+] Add Parent
                        </a>
                    </div>
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

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
                                            <!-- Mengatur nomor urut tabel berdasarkan pagination -->
                                            <td>{{ ($parents->currentPage() - 1) * $parents->perPage() + $index + 1 }}.</td>
                                            
                                            <!-- Menampilkan nama 'Person' -->
                                            <td>{{ $parent->people->name ?? 'N/A' }}</td> 
                                            
                                            <!-- Menampilkan nama 'Parent' -->
                                            <td>{{ $parent->userParent->name }}</td> 
                                            
                                            <!-- Menampilkan role (misalnya 'Father' atau 'Mother') -->
                                            <td>{{ ucfirst($parent->parent) }}</td> 
                                            
                                            <!-- Kolom untuk aksi edit dan delete -->
                                            <td class="text-center action-buttons">
                                                @if(auth()->user()->id === $parent->user_id)
                                                    <!-- Tampilkan tombol edit dan delete jika user yang login adalah pembuat data -->
                                                    <a href="{{ route('parentspeople.edit', $parent->id) }}" class="btn btn-sm btn-edit me-2">Edit</a>
                                                    
                                                    <form action="{{ route('parentspeople.destroy', $parent->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="button" class="btn btn-sm btn-view" data-bs-toggle="modal" data-people-id="{{ $parent->people_id }}" onclick="showParentModal({{ $parent->people_id }})" data-bs-target="#parentsModal">View Parents</button>
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
    
                                                @else
                                                    <!-- Jika user login bukan pembuat data, tampilkan pesan -->
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
                    <div class="connector-horizontal">
                        <span class="line-horizontal"></span>
                    </div>
                    <!-- Garis vertikal yang menghubungkan garis horizontal dengan person -->
                    <div class="connector-vertical">
                        <span class="line-vertical"></span>
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
                // Menampilkan data untuk ibu
                if (res.data.mother.length > 0) {
                    $.each(res.data.mother, function(index, value) {
                        let lineColor = value.user_parent.gender === 'male' ? 'blue' : 'magenta';
                        let bgColor = value.user_parent.death_date ? 'black' : 'white';
                        let textColor = value.user_parent.death_date ? 'white' : 'black';
                        const birthDate = value.user_parent.birth_date ? new Date(value.user_parent.birth_date).toLocaleDateString() : 'N/A';
                        const deathDate = value.user_parent.death_date ? new Date(value.user_parent.death_date).toLocaleDateString() : '-';
                        $('#mother').append(`
                            <div style="background-color: ${bgColor}; color: ${textColor}; border: 3px solid ${lineColor}; padding: 5px; margin: 5px; width: 160px; margin-left: 1px; font-size: 12px; border-radius: 8%;">
                                <p style="font-weight: bold; text-align: center;">${value.user_parent.name} (${value.parent})</p>
                                <div style="display: flex; justify-content: space-between;">
                                <p>Birth Date:</p>
                                <p>${birthDate}</p>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <p>Death Date:</p>
                                <p>${deathDate}</p>
                            </div>
                        </div>
                        `);
                    });
                } else {
                    $('#mother').append(`
                        <div style=" color: black; border: 2px solid black; padding: 10px; margin: 5px; width: 160px; height: 116px; box-sizing: border-box; text-align: center; border-radius: 8%;">
                            <p style="margin: 0;">No data for mother</p>
                        </div>
                    `);
                }

                // Menampilkan data untuk ayah
                if (res.data.father.length > 0) {
                    $.each(res.data.father, function(index, value) {
                        let lineColor = value.user_parent.gender === 'male' ? 'blue' : 'magenta';
                        let bgColor = value.user_parent.death_date ? 'black' : 'white';
                        let textColor = value.user_parent.death_date ? 'white' : 'black';
                        const birthDate = value.user_parent.birth_date ? new Date(value.user_parent.birth_date).toLocaleDateString() : 'N/A';
                        const deathDate = value.user_parent.death_date ? new Date(value.user_parent.death_date).toLocaleDateString() : '-';
                            $('#father').append(`
                                <div style="background-color: ${bgColor}; color: ${textColor}; border: 3px solid ${lineColor}; padding: 5px; margin: 5px; width: 160px; margin-left: -30px; font-size: 12px; border-radius: 8%;">
                                    <p style="font-weight: bold; text-align: center;">${value.user_parent.name} (${value.parent})</p>
                                    <div style="display: flex; justify-content: space-between;">
                                        <p>Birth Date:</p>
                                        <p>${birthDate}</p>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <p>Death Date:</p>
                                        <p>${deathDate}</p>
                                    </div>
                                </div>
                            `);
                    });
                } else {
                    $('#father').append(`
                        <div style=" color: black; border: 2px solid black; padding: 10px; margin: 5px; width: 160px; height: 116px;  box-sizing: border-box; text-align: center; border-radius: 8%; ">
                            <p style="margin: 0;">No data for father</p>
                        </div>
                    `);
                }

                // Menampilkan data untuk person/child
                if (res.data.person) {
                    const person = res.data.person;
                    let lineColor = (person.gender === 'male') ? 'blue' : 'magenta';
                    let bgColor = person.death_date ? 'black' : 'white';
                    let textColor = person.death_date ? 'white' : 'black';
                    const birthDate = person.birth_date ? new Date(person.birth_date).toLocaleDateString() : 'N/A';
                    const deathDate = person.death_date ? new Date(person.death_date).toLocaleDateString() : 'N/A';
                    $('#person').append(`
                        <div style="background-color: ${bgColor}; color: ${textColor}; border: 3px solid ${lineColor}; padding: 5px; margin: 5px; width: 160px; height: 116px; margin-left: -2px; font-size: 12px; border-radius: 8%;">
                            <p style="font-weight: bold; text-align: center;">${person.name}</p>
                            <div style="display: flex; justify-content: space-between;">
                                <p>Birth Date:</p>
                                <p>${birthDate}</p>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <p>Death Date:</p>
                                <p>${person.death_date ? deathDate : '-'}</p>
                            </div>
                        </div>
                    `);

                } else {
                    $('#person').append('<p>N/A</p>');
                }

                // D3.js untuk menggambar garis "T"
                d3.select("#line-container").selectAll("*").remove();

                const svg = d3.select("#line-container");
                const width = +svg.attr("width");
                const height = +svg.attr("height");
                const startX = width / 2;
                const startY = 10;
                const endX = startX;
                const endY = height - 10;
                const leftX = startX - 50;
                const rightX = startX + 50;

                svg.append("line")
                    .attr("x1", startX)
                    .attr("y1", startY)
                    .attr("x2", endX)
                    .attr("y2", endY)
                    .attr("stroke", "black")
                    .attr("stroke-width", 2);

                svg.append("line")
                    .attr("x1", leftX)
                    .attr("y1", startY)
                    .attr("x2", rightX)
                    .attr("y2", startY)
                    .attr("stroke", "black")
                    .attr("stroke-width", 2);
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
    <script src="https://cdn.jsdelivr.net/npm/boots .fr4ntrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>