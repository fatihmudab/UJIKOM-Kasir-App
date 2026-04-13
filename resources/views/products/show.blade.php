@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>📦 Detail Produk</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($product->image)
                                <div class="mb-3">
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 300px;">
                                </div>
                            @endif
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Nama Produk</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Harga</th>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Stok</th>
                                    <td>
                                        <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                            {{ $product->stock }} pcs
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat Tanggal</th>
                                    <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">← Kembali</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">Edit</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
