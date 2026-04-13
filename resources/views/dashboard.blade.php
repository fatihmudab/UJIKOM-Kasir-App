<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kasir App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-card {
            background: white;
            padding: 40px;
            margin-top: 40px;
        }
        .welcome-section {
            text-align: center;
            margin-bottom: 40px;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .menu-item {
            background: white;
            border: 1px solid #dee2e6;
            padding: 30px;
            text-align: center;
            text-decoration: none;
            color: #212529;
            display: block;
        }
        .menu-item:hover {
            background-color: #f8f9fa;
            color: #212529;
        }
        .menu-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        .menu-title {
            font-weight: 600;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0d6efd;">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">🛒 Kasir App</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Hello, {{ $user->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-card">
            <div class="welcome-section">
                <h1>Selamat Datang! 👋</h1>
                <p class="text-muted">Anda login sebagai <strong>{{ $user->email }}</strong></p>
                <p>
                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                        {{ strtoupper($user->role) }}
                    </span>
                </p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
