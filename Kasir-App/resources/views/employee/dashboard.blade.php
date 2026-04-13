@extends('layouts.app')

@section('content')
<h2>Dashboard Kasir</h2>
<p>Selamat datang, {{ auth()->user()->name }}</p>

<div class="row mb-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6>Pesanan Saya</h6>
                <h3>{{ $stats['my_orders'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6>Total Pendapatan</h6>
                <h4>Rp {{ number_format($stats['my_revenue'], 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6>Pesanan Hari Ini</h6>
                <h3>{{ $stats['today_orders'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6>Pendapatan Hari Ini</h6>
                <h4>Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Aksi Cepat</div>
            <div class="card-body">
                <a href="{{ route('transaction.create') }}" class="btn btn-success">Buat Transaksi</a>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Lihat Produk</a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Pesanan Terbaru</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
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
                        <td>{{ substr($order->id, 0, 8) }}...</td>
                        <td>{{ $order->sale_date->format('d/m/Y H:i') }}</td>
                        <td>{{ $order->customer ? $order->customer->name : 'Non-Member' }}</td>
                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada pesanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

