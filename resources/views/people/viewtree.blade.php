<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Family Tree</title>
    <!-- Cytoscape Core -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cytoscape/3.24.0/cytoscape.min.js"></script>
    <!-- Dagre.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dagre/0.8.5/dagre.min.js"></script>
    <!-- Cytoscape Dagre Layout -->
    <script src="https://cdn.jsdelivr.net/npm/cytoscape-dagre/cytoscape-dagre.min.js"></script>
    <style>
        #cy {
            width: 100%;
            height: 90vh;
            background: #f4f4f4;
        }
    </style>
</head>
<body>
    <button id="saveDiagramButton" style="margin: 10px; padding: 10px; background-color: green; color: white; font-size: 16px;">Save Diagram</button>
    <div id="cy"></div>
    <script>
        document.getElementById('saveDiagramButton').addEventListener('click', function () {
    const positions = cy.nodes().map(node => ({
        node_id: node.id(),
        x: node.position().x,
        y: node.position().y,
    }));

    fetch('/save-diagram-positions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Tambahkan CSRF token
        },
        body: JSON.stringify({ positions }),
    })
        .then(response => {
            console.log("Save response status:", response.status);
            return response.json();
        })
        .then(data => {
            alert(data.message);
        })
        .catch(error => console.error("Error saving diagram positions:", error));
});


        // Daftarkan layout Dagre
        cytoscape.use(cytoscapeDagre);

        let cy = null; // Cytoscape instance diinisialisasi sebagai null

        // Fungsi untuk fetch dan render family tree
        const renderFamilyTree = () => {
            Promise.all([
                fetch('/api/get-family-tree/2').then(response => response.json()), // Ambil data diagram
                fetch('/get-diagram-positions').then(response => response.json()) // Ambil posisi tersimpan
            ])
                .then(([data, positions]) => {
                    const elements = [];

                    // Nodes
                    data.nodes.forEach(node => {
                        const position = positions.find(pos => pos.node_id === node.data.id);
                        elements.push({
                            data: { id: node.data.id, label: node.data.label, color: node.data.color },
                            position: position ? { x: position.x_position, y: position.y_position } : undefined, // Gunakan posisi tersimpan jika ada
                        });
                    });

                    // Edges
                    data.edges.forEach(edge => {
                        elements.push({
                            data: {
                                source: edge.data.source,
                                target: edge.data.target,
                                label: edge.data.role, // Tambahkan label dari role
                            },
                        });
                    });

                    // Inisialisasi atau update Cytoscape
                    if (cy === null) {
                        cy = cytoscape({
                            container: document.getElementById('cy'),
                            elements: elements,
                            layout: {
                                name: 'preset', // Gunakan layout preset untuk posisi manual
                            },
                            style: [ // Definisi global stylesheet Cytoscape
                                {
                                    selector: 'node',
                                    style: {
                                        'label': 'data(label)',
                                        'background-color': 'data(color)',
                                        'text-valign': 'center',
                                        'text-halign': 'center',
                                        'color': '#fff',
                                        'font-size': '50px',
                                        'shape': 'round-rectangle',
                                        'width': '1000px', // Lebar node
                                        'height': '300px', // Tinggi node
                                        'padding': '40px', // Jarak teks
                                        'text-wrap': 'wrap', // Membuat teks multi-line
                                        'text-max-width': '2000px', // Maksimal lebar teks
                                    },
                                },
                                {
                                    selector: 'edge',
                                    style: {
                                        'width': 15,
                                        'line-color': '#ccc',
                                        'target-arrow-color': '#ccc',
                                        'target-arrow-shape': 'triangle',
                                        'label': 'data(label)', // Ambil label dari data
                                        'text-rotation': 'autorotate', // Agar teks mengikuti arah edge
                                        'font-size': '20px',
                                        'color': '#000',
                                    },
                                },
                            ],
                        });
                    } else {
                        cy.elements().remove(); // Hapus elemen lama
                        cy.add(elements); // Tambahkan elemen baru
                        cy.layout({ name: 'preset' }).run(); // Rerun layout
                    }
                })
                .catch(error => console.error("Error fetching family tree:", error));
        };

        // Render awal
        renderFamilyTree();
    </script>
</body>
</html>
