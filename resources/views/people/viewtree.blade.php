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

        .modal-dialog {
            max-width: 800px; /* Perbesar ukuran modal */ 
        }


        .modal-content {
            padding: 20px; /* Tambahkan padding agar tidak terlalu mepet */
        }

        #treeContainer {
            width: 100%; /* Atur agar memenuhi lebar modal */
            height: 600px; /* Atur agar cukup tinggi */
            display: flex; 
            justify-content: center;
            align-items: center;
            overflow-x: auto; /* Jika konten lebih besar dari container, memungkinkan scrolling horizontal */
        }
        .couple {
            margin-top: -67px; /* Sama seperti person */
            margin-left: 20px; /* Jarak antara person dan couple */
        }

        #couple {
            margin-top: -67px; /* Sama seperti person */
        }
        .person-couple-container {
            display: flex;
            align-items: flex-start; /* Ubah ini untuk menjadikan posisi ke atas */
        }

        .person-box, .couple-box {
            padding: 10px;
            border-radius: 8px;
            margin: 5px;
            width: 160px;
        }

        .family-tree {
        display: flex; /* Gunakan flexbox untuk menyusun elemen secara horizontal */
        justify-content: center; /* Pusatkan konten */
        align-items: flex-start; /* Sesuaikan posisi vertikal */
        }

        .people {
            display: flex; /* Mengatur people agar tampil bersebelahan */
            flex-direction: column; /* Bagan orang dalam satu kolom */
            margin-right: 20px; /* Ruang antara people dan couple */
        }

        .couple {
            display: flex; /* Mengatur couple agar tampil bersebelahan */
            flex-direction: column; /* Bagan pasangan dalam satu kolom */
        }

        .connector-couple {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100px; /* Sesuaikan ukuran */
        }

        .line-couple {
            width: 250px; /* Panjang garis yang bisa disesuaikan */
            height: 2px;
            background-color: green;
            position: absolute; /* Memungkinkan penempatan tepat */
            top: 390px; /* Atur ini untuk menggeser garis ke atas, misalnya 25px */
            left: 36%; /* Mengatur ke tengah secara horizontal */
            transform: translateX(102%); /* Menggeser garis ke kiri agar sejajar */
        }


        .couple-box {
            padding: 10px;
            border-radius: 8px;
            margin: 0px;
            width: 1px; /* Pastikan ini sesuai dengan yang diinginkan */
            position: relative; /* Agar properti top bekerja */
            top: -30px; /* Atur untuk menaikkan elemen */
            left: 230px; /* Geser sedikit ke kanan, sesuaikan nilainya */
        }
        

        /* CSS untuk bagan orang tua pasangan */
        .couple-parents {
            display: flex; /* Mengatur tampilan dalam baris */
            justify-content: center; /* Pusatkan konten secara horizontal */
            margin-top: 20px; /* Jarak atas untuk pemisahan */
        }

        .couple-parent {
            display: flex;
            flex-direction: column; /* Susun orang tua secara vertikal
             */
            align-items: center; /* Pusatkan konten */
            margin: 0 30px; /* Jarak antar orang tua */
        }

        .couple-parent-box {
            padding: 15px; /* Padding yang lebih besar untuk ruang */
            border-radius: 10px; /* Radius sudut yang lebih besar */
            width: 180px; /* Lebar yang sedikit lebih besar */
            border: 2px solid #ccc; /* Warna batas default */
            outline: 2px solid transparent; /* Outline default yang transparan */
            outline-offset: 2px; /* Jarak antara outline dan border */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Efek bayangan yang lebih kuat */
            transition: transform 0.3s, outline 0.3s, box-shadow 0.3s; /* Efek transisi lebih halus */
            background-color: #ffffff; /* Warna latar belakang */
        }

        .couple-parent-box:hover {
            transform: scale(1.05); /* Meningkatkan ukuran saat hover */
            border-color: #007bff; /* Mengubah warna batas saat hover */
            outline-color: #007bff; /* Mengubah warna outline saat hover */
            box-shadow: 0 6px 15px rgba(0, 123, 255, 0.3); /* Efek bayangan saat hover */
        }

        .couple-parent-title {
            font-weight: bold;
            text-align: center; /* Pusatkan teks */
            color: #333; /* Warna teks judul */
            margin-bottom: 8px; /* Jarak bawah untuk pemisahan */
            font-size: 14px; /* Ukuran font yang lebih besar */
        }

        .couple-parent-details {
            display: flex;
            justify-content: space-between; /* Ruang antara label dan data */
            font-size: 13px; /* Ukuran font untuk detail */
            color: #555; /* Warna teks detail */
        }

        /* Gaya untuk garis penghubung antara orang tua dan pasangan */
        .couple-parent-connector {
            width: 60px; /* Panjang garis penghubung yang lebih panjang */
            height: 3px; /* Ketebalan garis yang lebih tebal */
            background-color: green; /* Warna garis penghubung */
            margin: 5px auto; /* Jarak atas-bawah untuk pusatkan garis secara horizontal */
            position: relative; /* Memungkinkan penempatan tepat */
            top: -8px; /* Mengatur posisi garis */
            border-radius: 2px; /* Menambahkan sudut melingkar pada garis penghubung */
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
        <div class="person-couple-container">
        <div id="people" class="person-box">
            <!-- Data people akan di-append di sini -->
        </div>
        <div class="connector-couple">
            <div class="line-couple"></div>
        </div>
        <div id="couple" class="couple-box">
            <!-- Data couple akan di-append di sini -->
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
            $('#couple').empty();

            $.ajax({
                url: `/get-parent-people/${id}`,  // Endpoint backend yang akan memberikan data orang tua
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
                        const deathDate = person.death_date ? new Date(person.death_date).toLocaleDateString() : '-';
                        $('#person').append(`
                            <div style="background-color: ${bgColor}; color: ${textColor}; border: 3px solid ${lineColor}; padding: 5px; margin: 5px; width: 160px; margin-left: 0; font-size: 12px; border-radius: 8%;">
                                <p style="font-weight: bold; text-align: center;">${person.name} (people)</p>
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
                    }

        // Menampilkan data untuk pasangan (couple)
        if (res.data.couple && res.data.couple.length > 0) {
            const couple = res.data.couple[0];
            const coupleId = couple.partner;

            // Konfigurasi warna dan tanggal berdasarkan kondisi
            const lineColor = (coupleId.gender === 'male') ? 'blue' : 'magenta';
            const bgColor = couple.divorce_date ? 'red' : 'green';
            const textColor = 'white';
            const birthDate = coupleId.birth_date ? new Date(coupleId.birth_date).toLocaleDateString() : 'N/A';
            const divorceDate = couple.divorce_date ? new Date(couple.divorce_date).toLocaleDateString() : '-';

            // Panggil fungsi untuk mendapatkan orang tua pasangan
            getCoupleParents(coupleId.id);

                                    //                     .line-horizontal {
                                //     width: 75px; /* Panjang garis horizontal */
                                //     height: 2px;
                                //     background-color: green;
                                //     position: absolute;
                                //     left: 160px;
                                //     top: -60px;  /* Mengatur posisi garis agar berada di tengah antara mother dan father */
                                // }

            // tampilan parents couple
            $('#couple').append(`
                <div id="couple-container" style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-top: -220px;">
                    <div id="couple-parents" style="display: flex; flex-direction: column; align-items: center; margin-bottom: -30px; transform: translateX(50px);">
                        <div id="parent-line" style="display: flex; justify-content: center; align-items: center; position: relative; margin-top: -15px;">
                            <!-- Garis horizontal di antara kedua parent -->
                               
                            <div style="width: 100px; height: 2px; background-color: green; position: absolute; top: 100%;"></div>
                            <!-- Tempat untuk menampilkan orang tua -->
                        </div>
                        <!-- Garis vertikal yang menghubungkan pasangan dengan garis horizontal -->
                        <div style="width: 2px; height: 20px; top: -60px; background-color: green;"></div>
                    </div>
                    <!-- Tambahkan margin-top khusus untuk couple -->
                    <div style="background-color: ${bgColor}; color: ${textColor}; border: 3px solid ${lineColor}; padding: 3px; margin: 90px 5px 5px; width: 160px; font-size: 12px; border-radius: 8%;">
                        <p style="font-weight: bold;">${coupleId.name} (couple)</p>
                        <div style="display: flex; justify-content: space-between;">
                            <p>Birth Date:</p>
                            <p>${birthDate}</p>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <p>Divorce Date:</p>
                            <p>${divorceDate}</p>
                        </div>
                    </div>
                </div>
            `);



        } else {
            $('#couple').append(`
                <div style="color: black; border: 2px solid black; padding: 10px; margin: 5px; width: 160px; height: 116px; box-sizing: border-box; text-align: center; border-radius: 8%;">
                    <p style="margin: 0;">No data for couple</p>
                </div>
            `);
        }

        // Fungsi untuk mendapatkan orang tua pasangan
        function getCoupleParents(coupleId) {
            $.ajax({
                url: `/get-parent-people/${coupleId}`,
                type: 'GET',
                success: function(res) {
                    let parentsHTML = '';

                    // Menampilkan data untuk Ibu dan Ayah
                    parentsHTML += displayParentData(res.data.mother, 'mother');
                    parentsHTML += displayParentData(res.data.father, 'father');

                    // Tempatkan orang tua di dalam elemen "parent-line"
                    $('#couple-parents #parent-line').html(parentsHTML);
                }
            });
        }

        // Fungsi untuk menampilkan data orang tua
        function displayParentData(parentsData, parentType) {
            let parentHTML = '';
            
            if (parentsData.length > 0) {
                $.each(parentsData, function(index, value) {
                    const lineColor = value.user_parent.gender === 'male' ? 'blue' : 'magenta';
                    const bgColor = value.user_parent.death_date ? 'black' : 'white';
                    const textColor = value.user_parent.death_date ? 'white' : 'black';
                    const birthDate = value.user_parent.birth_date ? new Date(value.user_parent.birth_date).toLocaleDateString() : 'N/A';
                    const deathDate = value.user_parent.death_date ? new Date(value.user_parent.death_date).toLocaleDateString() : '-';

                    parentHTML += `
                        <div style="background-color: ${bgColor}; color: ${textColor}; border: 3px solid ${lineColor}; padding: 5px; margin: 0 10px; width: 160px; font-size: 12px; border-radius: 8%; text-align: center;">
                            <p style="font-weight: bold;">${value.user_parent.name} (${parentType})</p>
                            <div style="display: flex; justify-content: space-between;">
                                <p>Birth Date:</p>
                                <p>${birthDate}</p>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <p>Death Date:</p>
                                <p>${deathDate}</p>
                            </div>
                        </div>
                    `;
                });
            } else {
                parentHTML += `
                    <div style="color: black; border: 2px solid black; padding: 10px; margin: 0 10px; width: 160px; height: 116px; box-sizing: border-box; text-align: center; border-radius: 8%;">
                        <p style="margin: 0;">No data for ${parentType}</p>
                    </div>
                `;
            }

            return parentHTML;
        }
                }
            });
        }

        // Panggil fungsi saat halaman dimuat
        $(document).ready(function() {
            showParentDiagram({{ $person->id }}); 
        });
    </script>
</body>
</html>
