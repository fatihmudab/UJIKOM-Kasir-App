<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir App - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        .welcome-card {
            background: white;
            border: 1px solid #ddd;
            padding: 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .welcome-card h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .welcome-card p {
            color: #666;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: #333;
            border-color: #333;
        }
        .btn-primary:hover {
            background: #555;
            border-color: #555;
        }
    </style>
</head>
<body>
    <div class="welcome-card">
        <h1>Selamat Datang</h1>
        <p>Sistem Kasir App</p>
        @auth
            <p>Login sebagai: {{ Auth::user()->name }} ({{ Auth::user()->role }})</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        @endauth
    </div>
</body>
</html>
