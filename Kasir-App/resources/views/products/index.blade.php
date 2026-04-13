@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">📦 Daftar Produk</h4>
                    @if(auth()->user()->role === 'admin')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            + Tambah Produk
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    @if(auth()->user()->role === 'admin')
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                                {{ $product->stock }} pcs
                                            </span>
                                        </td>
                                        @if(auth()->user()->role === 'admin')
                                            <td>
                                                <button class="btn btn-sm btn-info" onclick="viewProduct('{{ $product->id }}')">Lihat</button>
                                                <button class="btn btn-sm btn-warning" onclick="editProduct('{{ $product->id }}')">Edit</button>
                                                <button class="btn btn-sm btn-primary" onclick="updateStock('{{ $product->id }}', {{ $product->stock }})">Stok</button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteProduct('{{ $product->id }}')">Hapus</button>
                                            </td>
                                        @endif
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

@if(auth()->user()->role === 'admin')

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">➕ Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addProductForm" action="{{ route('admin.products.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="price" class="form-control" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stock" class="form-control" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL Gambar (opsional)</label>
                        <input type="text" name="image" class="form-control" placeholder="https://example.com/image.jpg">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">✏️ Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProductForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id" id="editProductId">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" id="editProductName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="price" id="editProductPrice" class="form-control" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stock" id="editProductStock" class="form-control" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL Gambar (opsional)</label>
                        <input type="text" name="image" id="editProductImage" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📦 Update Stok Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateStockForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="updateStockProductId">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" id="updateStockProductName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="number" id="updateStockCurrent" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok Baru</label>
                        <input type="number" name="stock" id="updateStockNew" class="form-control" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Product Modal -->
<div class="modal fade" id="viewProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📦 Detail Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewProductContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">⚠️ Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteProductBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteProductId = null;

function viewProduct(productId) {
    const modal = new bootstrap.Modal(document.getElementById('viewProductModal'));
    
    fetch(`/products/${productId}`, {
        headers: {
            'Accept': 'text/html'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const content = doc.querySelector('.table')?.outerHTML || '<p class="text-danger">Produk tidak ditemukan</p>';
        document.getElementById('viewProductContent').innerHTML = content;
        modal.show();
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('viewProductContent').innerHTML = '<p class="text-danger">Gagal memuat data produk</p>';
        modal.show();
    });
}

function editProduct(productId) {
    const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
    
    fetch(`/products/${productId}`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('editProductId').value = data.id;
        document.getElementById('editProductName').value = data.name;
        document.getElementById('editProductPrice').value = data.price;
        document.getElementById('editProductStock').value = data.stock;
        document.getElementById('editProductImage').value = data.image || '';
        document.getElementById('editProductForm').action = `/admin/products/${productId}`;
        modal.show();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal memuat data produk. Silakan coba lagi.');
    });
}

function updateStock(productId, currentStock) {
    const modal = new bootstrap.Modal(document.getElementById('updateStockModal'));
    
    fetch(`/products/${productId}`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('updateStockProductId').value = data.id;
        document.getElementById('updateStockProductName').value = data.name;
        document.getElementById('updateStockCurrent').value = data.stock;
        document.getElementById('updateStockNew').value = data.stock;
        document.getElementById('updateStockForm').action = `/admin/products/${productId}/update-stock`;
        modal.show();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal memuat data produk. Silakan coba lagi.');
    });
}

function deleteProduct(productId) {
    deleteProductId = productId;
    new bootstrap.Modal(document.getElementById('deleteProductModal')).show();
}

document.getElementById('confirmDeleteProductBtn').addEventListener('click', function() {
    if (deleteProductId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/products/${deleteProductId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
});

// Handle form submissions with AJAX
document.getElementById('addProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            alert('Produk berhasil dibuat');
            location.reload();
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Terjadi kesalahan');
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    });
});

document.getElementById('editProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            alert('Produk berhasil diupdate');
            location.reload();
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Terjadi kesalahan');
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    });
});

document.getElementById('updateStockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const stock = document.getElementById('updateStockNew').value;
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ stock: parseInt(stock) })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Stok berhasil diupdate');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    });
});
</script>
@endif
@endsection
