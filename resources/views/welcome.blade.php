<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir App - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-card fade-in">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🛒</div>
            <h1>Kasir App</h1>
            <p>Sistem Point of Sale Modern</p>
            
            @auth
                <div class="alert alert-info" style="margin-bottom: 1.5rem;">
                    <strong>Login sebagai:</strong><br>
                    {{ Auth::user()->name }} 
                    <span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                    Dashboard →
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                    Login
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
