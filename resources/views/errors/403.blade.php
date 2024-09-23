<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            text-align: center;
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        h1 {
            font-size: 5rem;
            font-weight: 700;
            color: #ff6b6b;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 1.5rem;
            font-weight: 500;
            color: #333;
            margin-bottom: 20px;
        }

        p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 30px;
        }

        a {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
        }

        a:hover {
            background-color: #0056b3;
        }

        * {
            transition: all 0.3s ease;
        }
    </style>
</head>
    <body>
        <div class="container">
            <h1>403</h1>
            <h2>Forbidden</h2>
            <p>Sorry, you don't have permission to access this page.</p>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.index') }}">Go Back to Homepage</a>
            @else
                <a href="{{ route('people.index') }}">Go Back to Homepge</a>
            @endif
        </div>
    </body>
</html>
