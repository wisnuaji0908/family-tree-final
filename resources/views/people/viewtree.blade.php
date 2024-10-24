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
        /* CSS untuk diagram */
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
            font-size: 15px; 
        }
        .diagram-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
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

<div class="container">
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

<script>
    function showParentDiagram(id) {
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
                        <div style="color: black; border: 2px solid black; padding: 10px; margin: 5px; width: 160px; height: 116px; box-sizing: border-box; text-align: center; border-radius: 8%;">
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
                        <div style="color: black; border: 2px solid black; padding: 10px; margin: 5px; width: 160px; height: 116px; box-sizing: border-box; text-align: center; border-radius: 8%;">
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
            },
            error: function(err) {
                console.error(err);
                alert('Error fetching data');
            }
        });
    }

    // Panggil fungsi dengan id yang sesuai
    $(document).ready(function() {
        showParentDiagram(1); // Ganti 1 dengan id yang sesuai
    });
</script>
</body>
</html>
