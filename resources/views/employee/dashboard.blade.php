@extends('layouts.app')

@section('content')
<h2 class="mb-3">Dashboard Kasir</h2>
<p class="text-muted mb-4">Selamat datang, <strong>{{ auth()->user()->name }}</strong></p>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 13px;">Pesanan Saya</p>
                        <h3 class="mb-0" style="color: #2c3e50;">{{ $stats['my_orders'] }}</h3>
                    </div>
                    <div style="font-size: 40px; opacity: 0.15;">
                        <i class="fas fa-shopping-basket"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 13px;">Total Pendapatan</p>
                        <h4 class="mb-0" style="color: #27ae60;">Rp {{ number_format($stats['my_revenue'], 0, ',', '.') }}</h4>
                    </div>
                    <div style="font-size: 40px; opacity: 0.15;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 13px;">Pesanan Hari Ini</p>
                        <h3 class="mb-0" style="color: #2c3e50;">{{ $stats['today_orders'] }}</h3>
                    </div>
                    <div style="font-size: 40px; opacity: 0.15;">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1" style="font-size: 13px;">Pendapatan Hari Ini</p>
                        <h4 class="mb-0" style="color: #3498db;">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</h4>
                    </div>
                    <div style="font-size: 40px; opacity: 0.15;">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Aksi Cepat</div>
            <div class="card-body">
                <a href="{{ route('transaction.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-1"></i> Buat Transaksi
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-box me-1"></i> Lihat Produk
                </a>
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
                        <td>{{ $order->customer ? $order->customer->name : 'Non-Member' }}</td>
                        <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada pesanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

