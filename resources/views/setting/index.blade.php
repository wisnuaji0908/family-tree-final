<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting?->app_name ?? config('app.name') }} - App Settings</title> 
    <link rel="icon" href="{{ $setting?->app_logo ? asset('storage/' . $setting?->app_logo) : asset('default_favicon.ico') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
            font-size: 15px; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; 
            margin: 0;
            padding: 0; 
            overflow: hidden; 
        }
        .container {
            background-color: #ffffff;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 30px; 
            width: 90%; 
            max-width: 350px; 
            text-align: center;
        }
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 15px; 
        }

        .logo-container img {
            width: 70px;
            height: auto;
            margin-bottom: 10px; 
        }

        #preview-logo {
            display: none; 
            width: 70px; 
            height: auto;
            margin-top: 10px; 
            position: relative; 
        }

        .app-name {
            font-size: 1.3em;
            font-weight: bold;
            color: #333;
            margin: 3px 0; 
        }
        h1 {
            font-size: 1.5em;
            color: #4a4a4a;
            margin-bottom: 10px; 
        }
        p {
            font-size: 0.9em;
            color: #6c757d;
            margin-bottom: 15px; 
        }   
        form {
            display: flex;
            flex-direction: column;
            align-items: center; 
        }
        input[type="text"], input[type="file"] {
            font-size: 0.9em; 
            padding: 10px; 
            margin-bottom: 15px; 
            border-radius: 5px;
            border: 1px solid #ced4da;
            transition: all 0.3s;
            width: 100%; 
            max-width: 250px; 
        }
        input[type="text"]:focus, input[type="file"]:focus {
            outline: none;
            border-color: #51A783; 
            box-shadow: 0 0 5px rgba(81, 167, 131, 0.5);
        }
        button {
            padding: 10px; 
            background-color: #51A783;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 0.9em; 
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%; 
            max-width: 250px;
        }
        button:hover {
            background-color: #429f72;
        }
        .danger-button {
            background-color: #dc3545; 
        }
        .danger-button:hover {
            background-color: #c82333; 
        }
        .success-message {
            color: #28a745;
            margin-bottom: 15px; 
        }
        label {
            margin-top: 10px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>App Settings</h1>
        <p>Manage your app settings below</p>

        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <div class="logo-container">
            <div class="app-name">{{ $setting?->app_name }}</div>
            @if($setting?->app_logo)
                <img id="current-logo" src="{{ asset('storage/' . $setting?->app_logo) }}" alt="Current Logo">
            @endif
            <img id="preview-logo" src="#" alt="New Logo"> 
        </div>
        <form action="{{ route('setting.update', $setting->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return showPreview();">            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="app_name" class="form-label">App Name</label>
                <input type="text" id="app_name" name="app_name" placeholder="Nama Aplikasi" value="{{ old('app_name', $setting?->app_name) }}" required>
            </div>
            <div class="mb-3">
                <label for="app_logo" class="form-label">App Logo</label>
                <input type="file" id="app_logo" name="app_logo" accept="image/*" onchange="previewLogo(event)">
            </div>
            <button type="submit">Save Changes</button>
        </form>
        <button class="danger-button" onclick="confirmCancel()" style="margin-top: 15px;">Cancel</button>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview-logo');
            const currentLogo = document.getElementById('current-logo');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentLogo.style.display = 'none'; 
                    preview.src = e.target.result;
                    preview.style.display = 'block'; 
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.style.display = 'none'; 
            }
        }

        function showPreview() {
            const appLogoInput = document.getElementById('app_logo');
            if (appLogoInput.files.length > 0) {
                const preview = document.getElementById('preview-logo');
                preview.src = URL.createObjectURL(appLogoInput.files[0]);
            }
            return true; 
        }

        function confirmCancel() {
            if (confirm("Are you sure you want to cancel changes to app settings?")) {
                window.location.href = "{{ route('admin.index') }}";
            }
        }
    </script>
</body>
</html>
