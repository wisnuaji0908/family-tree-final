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
    <script src="https://unpkg.com/d3@6"></script>
    <script src="https://unpkg.com/family-chart"></script>
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

    <style>
        .f3 {
            height: 700px;
            max-height: calc(100vh - 80px);
            width: 900px;
            max-width: 100%;
            margin: auto;
            position: relative;
            background: linear-gradient(145deg, #f0f0f0, #ffffff);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: box-shadow 0.4s ease, transform 0.3s ease;
        }

        /* Efek hover untuk memperbesar elemen utama */
        .f3:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* Cursor pointer pada elemen yang dapat diklik */
        .f3 .cursor-pointer {
            cursor: pointer;
        }

        /* SVG utama dengan latar belakang gradien dan efek hover */
        .f3 svg.main_svg {
            width: 100%;
            height: 100%;
            background-color: #3b5560;
            color: #3b5560;
            transition: background-color 0.4s ease, transform 0.3s ease;
        }

        .f3 svg.main_svg:hover {
            background-color: #2e3a44;
            transform: scale(1.05);
        }

        .f3 svg.main_svg text {
            fill: currentColor;
        }

        /* Kartu Gender dengan efek warna lembut dan transisi halus */
        .f3 rect.card-female,
        .f3 .card-female .card-body-rect,
        .f3 .card-female .text-overflow-mask {
            fill: #f7b7b5; /* Soft pink */
            transition: fill 0.3s ease-in-out;
        }

        .f3 rect.card-male,
        .f3 .card-male .card-body-rect,
        .f3 .card-male .text-overflow-mask {
            fill: #a7c9f0; /* Soft blue */
            transition: fill 0.3s ease-in-out;
        }

        .f3 .card-genderless .card-body-rect,
        .f3 .card-genderless .text-overflow-mask {
            fill: #d3d3d3; /* Soft gray */
        }

        .f3 .card_add .card-body-rect {
            fill: #3b5560;
            stroke-width: 4px;
            stroke: #fff;
            cursor: pointer;
            border-radius: 12px;
            transition: transform 0.3s ease, fill 0.4s ease;
        }

        .f3 g.card_add text {
            fill: #fff;
        }

        .f3 .card_add:hover .card-body-rect {
            fill: #2e3a44;
            transform: scale(1.1);
        }

        .f3 .card-main {
            stroke: #000;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .f3 .card-main:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .f3 .card_family_tree rect {
            transition: 0.3s ease, transform 0.3s ease;
            border-radius: 12px;
        }

        .f3 .card_family_tree:hover rect {
            transform: scale(1.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .f3 .card_add_relative {
            cursor: pointer;
            color: #fff;
            transition: color 0.4s ease, transform 0.3s ease;
        }

        .f3 .card_add_relative:hover {
            color: #f39c12; 
            transform: scale(1.1);
        }

        .f3 .card_add_relative circle {
            fill: rgba(0, 0, 0, 0);
        }

        .f3 .card_edit.pencil_icon {
            color: #fff;
            transition: color 0.4s ease, transform 0.3s ease;
        }

        .f3 .card_edit.pencil_icon:hover {
            color: #f39c12;
            transform: rotate(15deg);
        }

        .f3 .card_break_link,
        .f3 .link_upper,
        .f3 .link_lower,
        .f3 .link_particles {
            transform-origin: 50% 50%;
            transition: transform 1s ease, opacity 0.4s ease;
        }

        .f3 .card_break_link {
            color: #fff;
        }

        .f3 .card_break_link.closed .link_upper {
            transform: translate(-140.5px, 655.6px);
        }

        .f3 .card_break_link.closed .link_upper g {
            transform: rotate(-58deg);
        }

        .f3 .card_break_link.closed .link_particles {
            transform: scale(0);
        }

        .f3 .input-field input {
            height: 2.5rem !important;
            border-radius: 10px;
            border: 1px solid #ccc;
            padding: 0 15px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .f3 .input-field input:focus {
            border-color: #3b5560;
            box-shadow: 0 0 8px rgba(59, 85, 96, 0.2);
        }

        .f3 .input-field > label:not(.label-icon).active {
            transform: translateY(-8px) scale(0.8);
            color: #3b5560;
        }

        .f3 .card-main, .f3 .card_add, .f3 .card_add_relative {
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        .f3 .card-main:nth-child(1),
        .f3 .card_add:nth-child(2),
        .f3 .card_add_relative:nth-child(3) {
            animation-delay: 0.5s;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>

</head>
<body>
<!-- Include Navbar -->
@include('nav')
    <div id="FamilyChart" class="f3"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script type="module">
        function fetchFamilyData(userId, callback) {
          $.ajax({
            url: `/get-parent-people/${userId}`,
            method: "GET",
            dataType: "json",
            success: function(result) {
            console.log("Server response:", result.data);

            if (result.success) {
                const person = result.data.person;
                console.log("Person:", person);

                const father = result.data.father.length ? result.data.father[0] : null;
                const mother = result.data.mother.length ? result.data.mother[0] : null;
                const spouses = result.data.couple.map(couple => couple.partner);

                const data = [
                {
                    id: person.id.toString(),
                    rels: {
                    spouses: spouses.map(spouse => spouse.id.toString()),
                    father: father ? father.user_parent.id.toString() : null,
                    mother: mother ? mother.user_parent.id.toString() : null,
                    children: []
                    },
                    data: {
                    "name": `${person.name} (People)`,
                    birthday: `${person.birth_date}` + ` (${person.gender})` + (person.death_date ? ` (${person.death_date})` : ''),
                    "gender": person.gender.charAt(0).toUpperCase(),
                    "death_date": person.death_date ? person.death_date : 'Death Date Not Provided',
                    "color": person.death_date ? "black" : "white"
                    }
                },
                ...spouses.map(spouse => ({
                    id: spouse.id.toString(),
                    rels: {
                    spouses: [person.id.toString()],
                    children: []
                    },
                    data: {
                    "name": `${spouse.name} (Couple)`,
                    birthday: `${spouse.birth_date}` + ` (${spouse.gender})` + (spouse.death_date ? ` (${spouse.death_date})` : ''),
                    "gender": spouse.gender.charAt(0).toUpperCase(),
                    "death_date": spouse.death_date ? spouse.death_date : 'Death Date Not Provided',
                    "color": spouse.death_date ? "black" : "white"
                    }
                }))
                ];


                if (father) {
                console.log("Father Data:", father);
                data.push({
                    id: father.user_parent.id.toString(),
                    rels: {
                    spouses: mother ? [mother.user_parent.id.toString()] : [],
                    children: [person.id.toString()]
                    },
                    data: {
                    "name": `${father.user_parent.name} (Father)`,
                    birthday: `${father.user_parent.birth_date || ''}` + ` (${father.user_parent.gender || ''})` + (father.user_parent.death_date ? ` (${father.user_parent.death_date})` : ''),
                    "gender": father.user_parent.gender ? father.user_parent.gender.charAt(0).toUpperCase() : 'Gender Not Provided',
                    "death_date": father.user_parent.death_date ? father.user_parent.death_date : 'Death Date Not Provided',
                    "color": father.user_parent.death_date ? "black" : "white"  
                    }
                });
                }


                if (mother) {
                console.log("Mother Data:", mother);
                data.push({
                    id: mother.user_parent.id.toString(),
                    rels: {
                    spouses: father ? [father.user_parent.id.toString()] : [],
                    children: [person.id.toString()]
                    },
                    data: {
                    "name": `${mother.user_parent.name} (Mother)`,
                    birthday: `${mother.user_parent.birth_date || ''}` + ` (${mother.user_parent.gender || ''})` + (mother.user_parent.death_date ? ` (${mother.user_parent.death_date})` : ''),
                    "gender": mother.user_parent.gender ? mother.user_parent.gender.charAt(0).toUpperCase() : 'Gender Not Provided',
                    "death_date": mother.user_parent.death_date ? mother.user_parent.death_date : 'Death Date Not Provided',
                    "color": mother.user_parent.death_date ? "black" : "white"  
                    }
                });   
                }


                callback(data);
              } else {
                console.error("Failed:", result.message);
                callback([]);
              }
            },
            error: function(xhr, status, error) {
              console.error("Error:", error);
              callback([]);
            }
          });
        }

            fetchFamilyData(1, function(data) {
            console.log("Data:", data);
            const store = f3.createStore({
                data,
                node_separation: 250,
                level_separation: 150
            }),
            view = f3.d3AnimationView({
                store,
                cont: document.querySelector("#FamilyChart")
            }),
            Card = f3.elements.Card({
              store,
              svg: view.svg,
              card_dim: {
                w: 220,
                h: 70,
                text_x: 75,
                text_y: 15,
                img_w: 60,
                img_h: 60,
                img_x: 5,
                img_y: 5
              },
              card_display: [
                (d) => `${d.data["name"] || ""}`,
                (d) => `${d.data["birthday"] || ""}`,
                (d) => `${d.data["death_date"] || ""}`,
              ],
              mini_tree: true,
              link_break: false
          });

          view.setCard(Card);
          store.setOnUpdate((props) => view.update(props || {}));
          store.update.tree({ initial: true });
        });
    </script>
</body>

</html>