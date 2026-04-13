@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-2" style="color: #2c3e50;">Dashboard Admin</h2>
        <p class="text-muted">Ringkasan statistik dan aktivitas sistem kasir</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 13px;">Total Pengguna</p>
                        <h3 class="mb-0" style="color: #2c3e50;">{{ $stats['total_users'] }}</h3>
                    </div>
                    <div style="font-size: 40px; opacity: 0.3;">👥</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 13px;">Total Produk</p>
                        <h3 class="mb-0" style="color: #2c3e50;">{{ $stats['total_products'] }}</h3>
                    </div>
                    <div style="font-size: 40px; opacity: 0.3;">📦</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 13px;">Total Pesanan</p>
                        <h3 class="mb-0" style="color: #2c3e50;">{{ $stats['total_orders'] }}</h3>
                    </div>
                    <div style="font-size: 40px; opacity: 0.3;">🛒</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 13px;">Total Pelanggan</p>
                        <h3 class="mb-0" style="color: #2c3e50;">{{ $stats['total_customers'] }}</h3>
                    </div>
                    <div style="font-size: 40px; opacity: 0.3;">👤</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1" style="font-size: 13px;">Total Pendapatan</p>
                <h4 style="color: #27ae60;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1" style="font-size: 13px;">Pesanan Hari Ini</p>
                <h4 style="color: #2c3e50;">{{ $stats['today_orders'] }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1" style="font-size: 13px;">Pendapatan Hari Ini</p>
                <h4 style="color: #3498db;">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>

@if($stats['low_stock_products'] > 0)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>⚠️ Perhatian:</strong> Ada {{ $stats['low_stock_products'] }} produk dengan stok rendah.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Aksi Cepat</div>
            <div class="card-body">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Kelola User</a>
                <a href="{{ route('products.index') }}" class="btn btn-success">Kelola Produk</a>
                <a href="{{ route('orders.index') }}" class="btn btn-info">Lihat Pesanan</a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Pesanan Terbaru</div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td><code>{{ substr($order->id, 0, 8) }}</code></td>
                        <td>{{ $order->sale_date->format('d/m/Y H:i') }}</td>
                        <td>{{ $order->employee->name }}</td>
                        <td>{{ $order->customer ? $order->customer->name : 'Non-Member' }}</td>
                        <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada pesanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

