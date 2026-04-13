@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-shopping-cart me-2"></i>Transaksi Baru</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Produk:</label>
                        <select id="productSelect" class="form-control">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" data-name="{{ $product->name }}">
                                    {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }} (Stok: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah:</label>
                        <input type="number" id="quantity" class="form-control" min="1" value="1">
                    </div>

                    <button onclick="addToCart()" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah ke Keranjang
                    </button>

                    <hr>

                    <h5>Keranjang Belanja</h5>
                    <table class="table table-bordered" id="cartTable">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                        </tbody>
                    </table>

                    <div class="text-end">
                        <h4>Total: Rp <span id="cartTotal">0</span></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-user me-2"></i>Info Pelanggan</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tipe Pelanggan:</label>
                        <select id="customerType" class="form-control" onchange="toggleCustomerForm()">
                            <option value="non-member">Non-Member</option>
                            <option value="existing-member">Member Lama</option>
                            <option value="new-member">Member Baru</option>
                        </select>
                    </div>

                    <div id="existingMemberForm" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Cari Member (No. HP):</label>
                            <input type="text" id="searchPhone" class="form-control" placeholder="Ketik minimal 3 digit no. HP" autocomplete="off">
                            <small class="text-muted">Ketik 3 digit awal no. HP untuk melihat daftar member</small>
                        </div>

                        <!-- Dropdown hasil pencarian -->
                        <div id="customerDropdown" style="display: none;" class="list-group mb-3 position-relative">
                            <!-- Hasil pencarian akan muncul di sini -->
                        </div>

                        <div id="searchingMember" style="display: none;">
                            <div class="alert alert-info">
                                <small>Mencari member...</small>
                            </div>
                        </div>

                        <div id="existingMember" style="display: none;">
                            <div class="alert alert-success">
                                <p class="mb-1"><strong>Nama:</strong> <span id="memberName"></span></p>
                                <p class="mb-1"><strong>No. HP:</strong> <span id="memberPhone"></span></p>
                                <p class="mb-1"><strong>Poin:</strong> <span id="memberPoints"></span></p>
                                <p class="mb-0"><small class="text-success"><i class="fas fa-check-circle me-1"></i>Member dipilih! Siap transaksi.</small></p>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="usePoints" onchange="updateTotal()">
                                <label class="form-check-label" for="usePoints">
                                    Gunakan poin sebagai diskon
                                </label>
                            </div>
                        </div>

                        <div id="memberNotFound" style="display: none;">
                            <div class="alert alert-warning">
                                <strong>Tidak ada member yang cocok!</strong><br>
                                <small>No. HP ini belum terdaftar. Silakan pilih "Member Baru" untuk mendaftarkan member baru.</small>
                            </div>
                        </div>
                    </div>

                    <div id="newMemberForm" style="display: none;">
                        <div class="alert alert-info">
                            <strong>Info:</strong> Member baru akan didaftarkan atas nama <strong>{{ auth()->user()->name }}</strong> (akun kasir yang sedang login).
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. HP Member Baru:</label>
                            <input type="text" id="newMemberPhone" class="form-control" placeholder="Contoh: 08123456789">
                            <small class="text-muted">No. HP akan digunakan sebagai ID member</small>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">Total Bayar:</label>
                        <h3>Rp <span id="finalTotal">0</span></h3>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Uang:</label>
                        <input type="number" id="totalPay" class="form-control" min="0" oninput="calculateChange()">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kembalian:</label>
                        <h4>Rp <span id="changeAmount">0</span></h4>
                    </div>

                    <button onclick="processTransaction()" class="btn btn-success w-100">
                        <i class="fas fa-check-circle me-1"></i> Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];
let selectedCustomer = null;

function addToCart() {
    const select = document.getElementById('productSelect');
    const productId = select.value;
    const quantity = parseInt(document.getElementById('quantity').value);

    if (!productId) {
        alert('Pilih produk terlebih dahulu');
        return;
    }

    const product = select.options[select.selectedIndex];
    const stock = parseInt(product.dataset.stock);

    if (quantity > stock) {
        alert('Stok tidak mencukupi');
        return;
    }

    const existingItem = cart.find(item => item.product_id === productId);

    if (existingItem) {
        if (existingItem.quantity + quantity > stock) {
            alert('Stok tidak mencukupi');
            return;
        }
        existingItem.quantity += quantity;
    } else {
        cart.push({
            product_id: productId,
            name: product.dataset.name,
            price: parseInt(product.dataset.price),
            quantity: quantity
        });
    }

    updateCartUI();
    document.getElementById('quantity').value = 1;
    select.value = '';
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartUI();
}

function updateCartUI() {
    const tbody = document.getElementById('cartBody');
    tbody.innerHTML = '';

    let total = 0;

    cart.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        tbody.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>Rp ${item.price.toLocaleString('id-ID')}</td>
                <td>${item.quantity}</td>
                <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                <td><button onclick="removeFromCart(${index})" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button></td>
            </tr>
        `;
    });

    document.getElementById('cartTotal').textContent = total.toLocaleString('id-ID');
    updateTotal();
}

function toggleCustomerForm() {
    const type = document.getElementById('customerType').value;
    document.getElementById('existingMemberForm').style.display = type === 'existing-member' ? 'block' : 'none';
    document.getElementById('newMemberForm').style.display = type === 'new-member' ? 'block' : 'none';
    selectedCustomer = null;

    // Reset all states
    document.getElementById('existingMember').style.display = 'none';
    document.getElementById('memberNotFound').style.display = 'none';
    document.getElementById('searchingMember').style.display = 'none';
    document.getElementById('customerDropdown').style.display = 'none';
    document.getElementById('searchPhone').value = '';
    document.getElementById('usePoints').checked = false;

    // Add event listener untuk input search
    const searchInput = document.getElementById('searchPhone');
    searchInput.oninput = searchMember;

    updateTotal();
}

let searchTimeout = null;

function searchMember() {
    const phone = document.getElementById('searchPhone').value.trim();

    // Reset states
    document.getElementById('existingMember').style.display = 'none';
    document.getElementById('memberNotFound').style.display = 'none';
    document.getElementById('searchingMember').style.display = 'none';
    document.getElementById('customerDropdown').style.display = 'none';
    selectedCustomer = null;

    // Clear previous timeout
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    // Don't search if less than 3 digits
    if (phone.length < 3) {
        return;
    }

    // Show searching indicator
    document.getElementById('searchingMember').style.display = 'block';

    // Debounce search (wait 500ms after user stops typing)
    searchTimeout = setTimeout(() => {
        console.log('Searching for customers with phone:', phone);

        fetch(`/customers/search/${phone}`)
            .then(response => response.json())
            .then(data => {
                console.log('Search result:', data);

                document.getElementById('searchingMember').style.display = 'none';

                if (data.customers && data.customers.length > 0) {
                    // Tampilkan dropdown dengan daftar customer
                    showCustomerDropdown(data.customers);
                } else {
                    // Tidak ada hasil
                    document.getElementById('customerDropdown').style.display = 'none';
                    document.getElementById('memberNotFound').style.display = 'block';
                    selectedCustomer = null;
                }
                updateTotal();
            })
            .catch(error => {
                console.error('Error searching member:', error);
                document.getElementById('searchingMember').style.display = 'none';
                document.getElementById('memberNotFound').style.display = 'block';
                selectedCustomer = null;
            });
    }, 500);
}

function showCustomerDropdown(customers) {
    const dropdown = document.getElementById('customerDropdown');
    dropdown.innerHTML = '';
    dropdown.style.display = 'block';

    customers.forEach(customer => {
        const item = document.createElement('a');
        item.href = '#';
        item.className = 'list-group-item list-group-item-action';
        item.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong style="font-size: 16px;">${customer.name}</strong>
                    <br>
                    <small class="text-muted">${customer.phone_number}</small>
                </div>
                <span class="badge bg-primary">${customer.total_poin} poin</span>
            </div>
        `;
        item.onclick = (e) => {
            e.preventDefault();
            selectCustomer(customer);
        };
        dropdown.appendChild(item);
    });
}

function selectCustomer(customer) {
    // Sembunyikan dropdown
    document.getElementById('customerDropdown').style.display = 'none';

    // Set selected customer
    selectedCustomer = customer;

    // Update field search dengan no HP yang dipilih
    document.getElementById('searchPhone').value = customer.phone_number;

    // Tampilkan detail member
    document.getElementById('existingMember').style.display = 'block';
    document.getElementById('memberNotFound').style.display = 'none';
    document.getElementById('memberName').textContent = customer.name;
    document.getElementById('memberPhone').textContent = customer.phone_number;
    document.getElementById('memberPoints').textContent = customer.total_poin;

    console.log('Customer selected:', customer);

    updateTotal();
}

function updateTotal() {
    let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    if (selectedCustomer && document.getElementById('usePoints').checked) {
        const points = selectedCustomer.total_poin;
        total -= points;
        if (total < 0) total = 0;
    }

    document.getElementById('finalTotal').textContent = total.toLocaleString('id-ID');
    calculateChange();
}

function calculateChange() {
    const total = parseInt(document.getElementById('finalTotal').textContent.replace(/\./g, ''));
    const pay = parseInt(document.getElementById('totalPay').value) || 0;
    const change = pay - total;

    document.getElementById('changeAmount').textContent = change >= 0 ? change.toLocaleString('id-ID') : 0;
}

function processTransaction() {
    if (cart.length === 0) {
        alert('Keranjang masih kosong');
        return;
    }

    const total = parseInt(document.getElementById('finalTotal').textContent.replace(/\./g, ''));
    const pay = parseInt(document.getElementById('totalPay').value) || 0;

    if (pay < total) {
        alert('Uang pembayaran kurang');
        return;
    }

    const customerType = document.getElementById('customerType').value;
    let data = {
        items: cart,
        customer_type: customerType,
        use_points: document.getElementById('usePoints').checked ? true : false,
        total_pay: pay
    };

    if (customerType === 'existing-member') {
        if (!selectedCustomer) {
            alert('Silakan cari dan pilih member terlebih dahulu!');
            return;
        }
        data.customer_id = selectedCustomer.id;
    } else if (customerType === 'new-member') {
        const newMemberPhone = document.getElementById('newMemberPhone').value.trim();

        if (!newMemberPhone) {
            alert('No. HP member baru harus diisi!');
            return;
        }

        // Gunakan no HP sebagai nama member baru
        data.customer_name = '{{ auth()->user()->name }}';
        data.customer_phone = newMemberPhone;
    }

    fetch('/orders', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Transaksi berhasil!');
            window.location.href = `/orders/${result.order_id}`;
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    });
}
</script>
@endsection
