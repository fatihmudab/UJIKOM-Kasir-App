<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .login-card {
            background: white;
            border: 1px solid #ddd;
            padding: 30px;
            max-width: 400px;
            width: 100%;
        }
        .login-card h2 {
            color: #333;
            text-align: center;
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
    <div class="login-card">
        <h2>Login</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('home') }}">← Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
