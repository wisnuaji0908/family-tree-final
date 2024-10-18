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
        }
        .container {
            background-color: #ffffff;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 30px; 
            width: 90%; 
            max-width: 700px; 
            text-align: center;
        }
        .form-row {
            display: flex;
            justify-content: space-between; 
            align-items: flex-start; 
            margin-bottom: 15px; 
        }

        .flex-fill {
            flex: 1; 
            margin-right: 10px; 
        }

        .flex-fill:last-child {
            margin-right: 0;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px; 
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
        <div class="logo-container">
            <img id="current-logo" src="{{ asset('storage/' . $setting?->app_logo) }}" alt="Current Logo" style="{{ $setting?->app_logo ? '' : 'display:none;' }}">
            <img id="preview-logo" src="#" alt="New Logo"> 
            <div class="app-name">{{ $setting?->app_name }}</div>
        </div>

        <h1>App Settings</h1>
        <p>Manage your app settings below</p>

        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

    <form action="{{ route('setting.update', $setting->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return showPreview();">
        @csrf
        @method('PUT')
            <div class="form-row">
                <div class="flex-fill">
                    <div class="mb-3">
                        <label for="app_logo" class="form-label">App Logo</label>
                        <input type="file" id="app_logo" name="app_logo" accept="image/*" onchange="previewLogo(event)">
                    </div>
                    <div class="mb-3">
                        <label for="app_name" class="form-label">App Name</label>
                        <input type="text" id="app_name" name="app_name" placeholder="Nama Aplikasi" value="{{ old('app_name', $setting?->app_name) }}" required>
                    </div>
                </div>
                <div class="flex-fill">
                    <div class="mb-3">
                        <label for="japati_token" class="form-label">Japati Token</label>
                        <input type="text" id="japati_token" name="japati_token" placeholder="Japati Token" value="{{ old('japati_token', $setting?->japati_token) }}">
                    </div>
                    <div class="mb-3">
                        <label for="japati_gateway" class="form-label">Japati Gateway</label>
                        <input type="text" id="japati_gateway" name="japati_gateway" placeholder="Japati Gateway" value="{{ old('japati_gateway', $setting?->japati_gateway) }}">
                    </div>
                    <div class="mb-3">
                        <label for="japati_url" class="form-label">Japati URL</label>
                        <input type="text" id="japati_url" name="japati_url" placeholder="Japati URL" value="{{ old('japati_url', $setting?->japati_url) }}">
                    </div>
                    </div>
                </div>
            <button type="submit" style="margin-bottom: 15px;">Save Changes</button>
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
