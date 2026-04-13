@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>📄 Detail Transaksi</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>ID Order:</strong> {{ $order->id }}</p>
                            <p><strong>Tanggal:</strong> {{ $order->sale_date->format('d/m/Y H:i:s') }}</p>
                            <p><strong>Kasir:</strong> {{ $order->employee->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Pelanggan:</strong> {{ $order->customer ? $order->customer->name : 'Non-Member' }}</p>
                            @if($order->customer)
                                <p><strong>No. HP:</strong> {{ $order->customer->phone_number }}</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <h5>Item Pembelian</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->detailOrders as $detail)
                                <tr>
                                    <td>{{ $detail->product->name }}</td>
                                    <td>{{ $detail->amount }}</td>
                                    <td>Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            @if($order->points_used > 0)
                                <p class="text-warning"><strong>Poin Digunakan:</strong> -{{ $order->points_used }}</p>
                            @endif
                            @if($order->points_earned > 0)
                                <p class="text-success"><strong>Poin Didapat:</strong> +{{ $order->points_earned }}</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <h4>Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                            <p><strong>Bayar:</strong> Rp {{ number_format($order->total_pay, 0, ',', '.') }}</p>
                            <p><strong>Kembali:</strong> Rp {{ number_format($order->total_return, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Kembali</a>
                        <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
