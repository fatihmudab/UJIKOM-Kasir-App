<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #2c3e50;
            border-bottom: 2px solid #34495e;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            color: white !important;
            font-size: 20px;
            font-weight: 600;
        }
        .nav-link {
            color: #bdc3c7 !important;
            padding: 8px 16px !important;
            font-size: 14px;
        }
        .nav-link:hover {
            color: white !important;
        }
        .btn-logout {
            background: #e74c3c;
            border: none;
            color: white;
            padding: 6px 16px;
            font-size: 13px;
        }
        .btn-logout:hover {
            background: #c0392b;
            color: white;
        }
        .card {
            border: 1px solid #e1e8ed;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .card-header {
            background: #fff;
            border-bottom: 2px solid #e1e8ed;
            font-weight: 600;
            color: #2c3e50;
            padding: 12px 20px;
        }
        .badge {
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 500;
        }
        .btn {
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 14px;
        }
        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
        }
        table {
            font-size: 14px;
        }
        .form-control {
            border-radius: 6px;
            border: 1px solid #dce1e6;
            padding: 10px 12px;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
        }
        .alert {
            border-radius: 6px;
            border: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span style="color: #3498db;">Kasir</span>App
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-box"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') }}">
                            <i class="fas fa-shopping-cart"></i> Transaksi
                        </a>
                    </li>
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3" style="font-size: 14px;">
                        <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                        <span class="badge bg-primary ms-2">{{ strtoupper(auth()->user()->role) }}</span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-logout btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4" style="padding: 0 30px;">
        @yield('content')
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
