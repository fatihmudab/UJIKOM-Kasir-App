@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">🛒 Riwayat Transaksi</h4>
                    <div>
                        <a href="{{ route('orders.export') }}" class="btn btn-success">📥 Export CSV</a>
                        @if(auth()->user()->role === 'employee')
                            <a href="{{ route('transaction.create') }}" class="btn btn-primary">+ Transaksi Baru</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>ID Order</th>
                                    <th>Kasir</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Poin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->sale_date->format('d/m/Y H:i') }}</td>
                                        <td>{{ substr($order->id, 0, 8) }}...</td>
                                        <td>{{ $order->employee->name }}</td>
                                        <td>{{ $order->customer ? $order->customer->name : 'Non-Member' }}</td>
                                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($order->points_used > 0)
                                                <span class="badge bg-warning">-{{ $order->points_used }}</span>
                                            @endif
                                            @if($order->points_earned > 0)
                                                <span class="badge bg-success">+{{ $order->points_earned }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
