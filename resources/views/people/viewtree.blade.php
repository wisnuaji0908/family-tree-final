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
            background: #ffffff;
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

        // Ambil ID person sebelum 'viewtree'
        const urlParts = window.location.pathname.split('/');
        const personId = urlParts[urlParts.indexOf('people') + 1];

        console.log("ID yang di-highlight:", personId); // Debugging

        let cy = null; // Cytoscape instance diinisialisasi sebagai null

        // Fungsi untuk fetch dan render family tree
        const renderFamilyTree = () => {
            Promise.all([
                // fetch(`/api/get-family-tree/${personId}`).then(response => response.json()), // Fetch API sesuai ID
                fetch('/api/get-family-tree/2').then(response => response.json()), // Ambil data diagram
                fetch('/get-diagram-positions').then(response => response.json()) // Ambil posisi tersimpan
            ])
                .then(([data, positions]) => {
                    if (!data || !data.nodes || !data.edges) {
                        console.error("Invalid data from API:", data);
                        return; // Stop eksekusi jika data kosong atau invalid
                    }

                    console.log("Nodes:", data.nodes);
                    console.log("Edges:", data.edges);
                    console.log("Positions:", positions);
                    const elements = [];

                    data.nodes.forEach(node => {
                        const position = positions.find(pos => pos.node_id === node.data.id);

                        // Tambahkan shadow node lebih dulu hanya untuk node yang di-highlight
                        if (node.data.id === `person_${personId}`) {
                            elements.push({
                                data: { id: `shadow_${node.data.id}` },
                                position: position ? { x: position.x_position, y: position.y_position } : undefined,
                                classes: 'shadow-node',
                            });
                        }

                        // Tambahkan node utama
                        elements.push({
                            data: { id: node.data.id, label: node.data.label, color: node.data.color, borderColor: node.data.borderColor, textColor: node.data.textColor },
                            position: position ? { x: position.x_position, y: position.y_position } : undefined,
                        });
                    });


                    // Edges
                    data.edges.forEach(edge => {

                        if (edge.data.target == 'person_9') {
                            console.log('cerai', edge)
                        }
                        elements.push({
                            data: {
                                source: edge.data.source,
                                target: edge.data.target,
                                label: edge.data.role, // Tambahkan label dari role
                                color: edge.data.color, // Tambahkan warna dari data backend
                            },
                        });
                    });

                    console.log("e:", elements);

                    // Inisialisasi atau update Cytoscape
                    if (cy === null) {
                        cy = cytoscape({
                            container: document.getElementById('cy'),
                            elements: elements,
                            layout: {
                                name: 'preset', // Gunakan layout preset untuk posisi manual
                            },
                            textureOnViewport: true, // Tambahkan properti ini
                            motionBlur: true,        // Optimalkan motion blur
                            style: [ // Definisi global stylesheet Cytoscape
                                {
                                    selector: 'node',
                                    style: {
                                        'label': 'data(label)',
                                        'background-color': 'data(color)',
                                        'border-color': 'data(borderColor)',
                                        'text-valign': 'center',
                                        'text-halign': 'center',
                                        'color': 'data(textColor)', // Terapkan warna teks
                                        'font-size': '50px',
                                        'shape': 'round-rectangle',
                                        'width': '1000px', // Lebar node
                                        'height': '300px', // Tinggi node
                                        'padding': '40px', // Jarak teks
                                        'text-wrap': 'wrap', // Membuat teks multi-line
                                        'text-max-width': '2000px', // Maksimal lebar teks
                                        'border-width': 15, // Border tebal
                                    },
                                },
                                {
                                    selector: '.shadow-node',
                                    style: {
                                        'background-color': 'yellow',
                                        'opacity': 0.3,
                                        'width': '1200px',
                                        'height': '330px',
                                        'border-width': 0, // Hilangin border di shadow node
                                        'label': '',       // Reset label
                                        'border-color': 'transparent', // Pastikan border jadi transparan
                                    },
                                },
                                {
                                    selector: 'edge',
                                    style: {
                                        'label': 'data(label)', // Ambil label dari data edge
                                        'color': '#000', // Warna teks
                                        'text-wrap': 'wrap', // Aktifkan wrapping teks
                                        'text-max-width': '800px', // Tentukan maksimal lebar teks
                                        'width': 30,
                                        'line-color': 'data(color)', // Warna garis edge dari data backend
                                        'target-arrow-color': 'data(color)', // Warna panah target dari data backend
                                        'target-arrow-shape': 'triangle',
                                        'label': 'data(label)', // Ambil label dari data
                                        'text-rotation': 'autorotate', // Agar teks mengikuti arah edge
                                        'font-size': '30px',
                                    },
                                },
                            ],
                        });
                    } else {
                        console.log("Before removing elements:", cy.elements().map(e => e.data()));
                        cy.elements().remove();
                        console.log("After removing elements:", cy.elements().map(e => e.data()));

                        cy.add(elements);
                        cy.layout({ name: 'preset' }).run();
                        console.log("Updated Cytoscape elements:", cy.elements().map(e => e.data()));
                    }
                })
                .catch(error => console.error("Error fetching family tree:", error));
        };

        const addShadowNode = (cy, targetId) => {
            const targetNode = cy.getElementById(targetId);
            if (targetNode) {
                const position = targetNode.position();

                // Cek dulu apakah shadow node sudah ada
                if (!cy.getElementById(`shadow_${targetId}`).length) {
                    // Tambahkan shadow node
                    cy.add({
                        data: { id: `shadow_${targetId}` },
                        position: { x: position.x, y: position.y },
                        classes: 'shadow-node',
                    });

                    // Paksa shadow node ke belakang
                    cy.style().selector('.shadow-node').style({
                        'z-compound-order': -1, // Z-index paling rendah
                    }).update();

                    console.log(`Shadow node ditambahkan untuk ID: ${targetId}`);
                }
            } else {
                console.warn(`Node dengan ID ${targetId} tidak ditemukan.`);
            }
        };


        // Render awal
        renderFamilyTree();
    </script>
</body>
</html>
