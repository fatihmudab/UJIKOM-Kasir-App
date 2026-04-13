<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kasir App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            color: white !important;
            font-weight: 700;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }
        .welcome-section {
            text-align: center;
            margin-bottom: 40px;
        }
        .welcome-section h1 {
            color: #667eea;
            font-weight: 700;
        }
        .role-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
        }
        .role-admin {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .role-employee {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .menu-item {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
            display: block;
        }
        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            color: #333;
        }
        .menu-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        .menu-title {
            font-weight: 600;
            font-size: 18px;
        }
        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">🛒 Kasir App</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Hello, {{ $user->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-card">
            <div class="welcome-section">
                <h1>Selamat Datang! 👋</h1>
                <p class="text-muted">Anda login sebagai <strong>{{ $user->email }}</strong></p>
                <span class="role-badge role-{{ $user->role }}">
                    {{ strtoupper($user->role) }}
                </span>
            </div>

            <div class="menu-grid">
                @if($user->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="menu-item">
                        <div class="menu-icon">👑</div>
                        <div class="menu-title">Admin Dashboard</div>
                    </a>
                @endif

                @if($user->role === 'employee')
                    <a href="{{ route('employee.dashboard') }}" class="menu-item">
                        <div class="menu-icon">💼</div>
                        <div class="menu-title">Transaksi Baru</div>
                    </a>
                @endif

                <a href="{{ route('products.index') }}" class="menu-item">
                    <div class="menu-icon">📦</div>
                    <div class="menu-title">Produk</div>
                </a>

                <a href="{{ route('orders.index') }}" class="menu-item">
                    <div class="menu-icon">🛒</div>
                    <div class="menu-title">Riwayat Transaksi</div>
                </a>

                @if($user->role === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="menu-item">
                        <div class="menu-icon">👥</div>
                        <div class="menu-title">Manajemen User</div>
                    </a>
                @endif

                @if($user->role === 'admin')
                    <a href="{{ route('admin.customers.index') }}" class="menu-item">
                        <div class="menu-icon">👥</div>
                        <div class="menu-title">Data Pelanggan</div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
