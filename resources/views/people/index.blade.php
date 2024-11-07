<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting?->app_name ?? config('app.name') }} - People</title>  
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
        .btn-light-green {
            background-color: #51A783; 
            color: white; 
            border: none; 
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
                                                                        {{-- @php
                                            $children = $people->filter(function($person) use ($people) {
                                                return $person->parent_id == $people[0]->id;
                                            })->map(function($child) {
                                                return [
                                                    "name" => $child->name,
                                                    "gender" => $child->gender,
                                                    "place_birth" => $child->place_birth,
                                                    "birth_date" => $child->birth_date,
                                                    "death_date" => empty($child->death_date) ? 'Not Provided' : $child->death_date,
                                                ];
                                            })->values();
                                        @endphp --}}

                                        {{-- <script>
                                            var rootPerson = {
                                                "name": "{{ $people[0]->name }}",
                                                "gender": "{{ $people[0]->gender }}",
                                                "place_birth": "{{ $people[0]->place_birth }}",
                                                "birth_date": "{{ $people[0]->birth_date }}",
                                                "death_date": "{{ empty($people[0]->death_date) ? 'Not Provided' : $people[0]->death_date }}",
                                                "children": @json($children)
                                            };

                                            var treeData = [rootPerson]; // Use the root person and their children

                                            // D3.js logic to visualize the tree (same as before)
                                            var margin = {top: 40, right: 90, bottom: 50, left: 90},
                                                width = 960 - margin.left - margin.right,
                                                height = 600 - margin.top - margin.bottom;

                                            var svg = d3.select("body").append("svg")
                                                .attr("width", width + margin.left + margin.right)
                                                .attr("height", height + margin.top + margin.bottom)
                                                .append("g")
                                                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

                                            var i = 0,
                                                duration = 750,
                                                root;

                                            var treemap = d3.tree().size([height, width]);

                                            // Assign the data to a hierarchy using parent-child relationships
                                            root = d3.hierarchy(treeData[0], function(d) {
                                                return d.children;
                                            });

                                            root.x0 = height / 2;
                                            root.y0 = 0;

                                            root.children.forEach(collapse);
                                            update(root);

                                            function collapse(d) {
                                                if (d.children) {
                                                    d._children = d.children;
                                                    d._children.forEach(collapse);
                                                    d.children = null;
                                                }
                                            }

                                            function update(source) {
                                                var treeData = treemap(root);
                                                var nodes = treeData.descendants(),
                                                    links = treeData.descendants().slice(1);

                                                nodes.forEach(function(d){ d.y = d.depth * 180 });

                                                var node = svg.selectAll('g.node')
                                                    .data(nodes, function(d) { return d.id || (d.id = ++i); });

                                                var nodeEnter = node.enter().append('g')
                                                    .attr('class', 'node')
                                                    .attr("transform", function(d) {
                                                        return "translate(" + source.y0 + "," + source.x0 + ")";
                                                    })
                                                    .on('click', click);

                                                nodeEnter.append('rect')
                                                    .attr('width', 160)
                                                    .attr('height', 80)
                                                    .attr('x', -80)
                                                    .attr('y', -40)
                                                    .style("fill", "#fff");

                                                nodeEnter.append('text')
                                                    .attr("dy", "-1em")
                                                    .attr("x", 0)
                                                    .attr("text-anchor", "middle")
                                                    .text(function(d) { return d.data.name; });

                                                nodeEnter.append('text')
                                                    .attr("dy", "1em")
                                                    .attr("x", 0)
                                                    .attr("text-anchor", "middle")
                                                    .style("fill", "gray")
                                                    .text(function(d) { 
                                                        return "Gender: " + d.data.gender + " | Birth: " + d.data.birth_date + " | Death: " + d.data.death_date;
                                                    });

                                                var nodeUpdate = nodeEnter.merge(node);

                                                nodeUpdate.transition()
                                                    .duration(duration)
                                                    .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

                                                var nodeExit = node.exit().transition()
                                                    .duration(duration)
                                                    .attr("transform", function(d) {
                                                        return "translate(" + source.y + "," + source.x + ")";
                                                    })
                                                    .remove();

                                                var link = svg.selectAll('path.link')
                                                    .data(links, function(d) { return d.id; });

                                                var linkEnter = link.enter().insert('path', "g")
                                                    .attr("class", "link")
                                                    .attr('d', function(d){
                                                        var o = {x: source.x0, y: source.y0}
                                                        return diagonal(o, o);
                                                    });

                                                var linkUpdate = linkEnter.merge(link);

                                                linkUpdate.transition()
                                                    .duration(duration)
                                                    .attr('d', function(d){ return diagonal(d, d.parent); });

                                                var linkExit = link.exit().transition()
                                                    .duration(duration)
                                                    .attr('d', function(d) {
                                                        var o = {x: source.x, y: source.y}
                                                        return diagonal(o, o);
                                                    })
                                                    .remove();

                                                nodes.forEach(function(d){
                                                    d.x0 = d.x;
                                                    d.y0 = d.y;
                                                });

                                                function diagonal(s, d) {
                                                    return `M ${s.y} ${s.x}
                                                            C ${(s.y + d.y) / 2} ${s.x},
                                                            ${(s.y + d.y) / 2} ${d.x},
                                                            ${d.y} ${d.x}`;
                                                }

                                                function click(d) {
                                                    if (d.children) {
                                                        d._children = d.children;
                                                        d.children = null;
                                                    } else {
                                                        d.children = d._children;
                                                        d._children = null;
                                                    }
                                                    update(d);
                                                }
                                            }
                                        </script> --}}
                                
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
                                                <a href="{{ route('people.viewtree', $data->id) }}" class="btn btn-sm btn-light-green">View Tree</a>
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
