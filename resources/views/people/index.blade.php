<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting?->app_name ?? config('app.name') }} - Edit People</title>  
    <link rel="icon" href="{{ $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- d3js --}}
    <script src="https://d3js.org/d3.v5.min.js"></script>
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
        rect {
            fill: white;
            stroke: silver;
            width: 80px;
            height: 40px;
            stroke-width: 2;
        }
        path {
            fill: none;
            stroke: silver;
            stroke-width: 2;

        }
        text {
            dominant-baseline: middle;
            text-anchor: middle;
        }
        .bigger {
          font-size: 20px;
        }
        .hide {
            visibility: hidden;
        }
    </style>
</head>
<body>
<!-- Include Navbar -->
@include('nav')

<script>
    // Set up the initial dimensions and margins
    var margin = { top: 50, right: 50, bottom: 50, left: 50 };
    var width = window.innerWidth - margin.left - margin.right;
    var height = window.innerHeight - margin.top - margin.bottom;

    // Create the SVG container and set its dimensions
    var svg = d3.select("body").append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    var data = [
        { "child": "John", "parent": "", "spouse": "Issabella" },
        { "child": "Arron", "parent": "Kevin" },
        { "child": "Kevin", "parent": "John", "spouse": "Emma" },
        { "child": "Hannah", "parent": "Ann" },
        { "child": "Rose", "parent": "Sarah" },
        { "child": "Ann", "parent": "John", "spouse": "Issac" },
        { "child": "Sarah", "parent": "Kevin", "spouse": "George" },
        { "child": "Mark", "parent": "Ann", "spouse": "Lucy" },
        { "child": "Angel", "parent": "Sarah" },
        { "child": "Iqbal", "parent": "Mark" }
    ];

    var dataStructure = d3.stratify()
        .id(function (d) { return d.child; })
        .parentId(function (d) { return d.parent; })
        (data);

    var treeStructure = d3.tree().size([width, height]);
    var information = treeStructure(dataStructure);

    // Draw the connections between nodes
    var connections = svg.append("g").selectAll("path")
        .data(information.links());

    connections.enter().append("path")
        .attr("d", function (d) {
            return "M" + d.source.x + "," + d.source.y +
                "C" + d.source.x + "," + (d.source.y + d.target.y) / 2 +
                " " + d.target.x + "," + (d.source.y + d.target.y) / 2 +
                " " + d.target.x + "," + d.target.y;
        })
        .attr("stroke", "#ccc")
        .attr("fill", "none");

    // Draw the rectangles for the nodes
    var rectangles = svg.append("g").selectAll("rect")
        .data(information.descendants());

    rectangles.enter().append("rect")
        .attr("x", function (d) { return d.x - 40; })
        .attr("y", function (d) { return d.y - 15; })
        .attr("width", 80)
        .attr("height", 30)
        .attr("fill", "#fff")
        .attr("stroke", "#000")
        .attr("stroke-width", 1);

    // Draw the rectangles for the spouses
    var spouseRectangles = svg.append("g").selectAll("rect")
        .data(information.descendants());

    spouseRectangles.enter().append("rect")
        .attr("x", function (d) { return d.x + 50; })
        .attr("y", function (d) { return d.y - 15; })
        .attr("width", 80)
        .attr("height", 30)
        .attr("fill", "#fff")
        .attr("stroke", "#000")
        .attr("stroke-width", 1)
        .classed("hide", function (d) {
            return d.data.spouse === undefined;
        });

    // Add names to the nodes
    var names = svg.append("g").selectAll("text")
        .data(information.descendants());

    names.enter().append("text")
        .text(function (d) { return d.data.child; })
        .attr("x", function (d) { return d.x; })
        .attr("y", function (d) { return d.y; })
        .attr("dy", ".35em")
        .attr("text-anchor", "middle");

    // Add names for the spouses
    var spouseNames = svg.append("g").selectAll("text")
        .data(information.descendants());

    spouseNames.enter().append("text")
        .text(function (d) { return d.data.spouse; })
        .attr("x", function (d) { return d.x + 90; })
        .attr("y", function (d) { return d.y; })
        .attr("dy", ".35em")
        .attr("text-anchor", "middle")
        .classed("hide", function (d) {
            return d.data.spouse === undefined;
        });

    // Make the chart responsive by updating on window resize
    window.addEventListener("resize", function () {
        width = window.innerWidth - margin.left - margin.right;
        height = window.innerHeight - margin.top - margin.bottom;
        svg.attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom);
        treeStructure.size([width, height]);
        information = treeStructure(dataStructure);

        // Update positions of nodes, connections, and text dynamically
        connections.attr("d", function (d) {
            return "M" + d.source.x + "," + d.source.y +
                "C" + d.source.x + "," + (d.source.y + d.target.y) / 2 +
                " " + d.target.x + "," + (d.source.y + d.target.y) / 2 +
                " " + d.target.x + "," + d.target.y;
        });

        rectangles.attr("x", function (d) { return d.x - 40; })
            .attr("y", function (d) { return d.y - 15; });

        spouseRectangles.attr("x", function (d) { return d.x + 50; })
            .attr("y", function (d) { return d.y - 15; });

        names.attr("x", function (d) { return d.x; })
            .attr("y", function (d) { return d.y; });

        spouseNames.attr("x", function (d) { return d.x + 90; })
            .attr("y", function (d) { return d.y; });
    });
</script>


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
