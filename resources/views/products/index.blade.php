@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Produk</h1>
        <button onclick="document.getElementById('modal-tambah-product').classList.remove('hidden')" class="bg-black text-white px-4 py-2 rounded-md shadow hover:bg-gray-800">
            Tambah
        </button>

    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table id="productsTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Expired</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $product->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ ucwords($product->name) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $product->stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $product->exp ?? '-'}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            <!-- Tombol Edit -->
                            <button type="button" class="inline-flex items-center px-4 py-2.5 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600"
                            onclick='openEditModal(@json($product))'>
                            Edit
                        </button>

                            <!-- Tombol Hapus -->
                            <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" class="inline-flex items-center px-4 py-2.5 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600"
                            onclick="confirmDelete({{ $product->id }})">Hapus</button>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <!-- Modal Tambah -->
<div id="modal-tambah-product" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <!-- Close button -->
            <button onclick="document.getElementById('modal-tambah-product').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>

            <h2 class="text-lg font-semibold mb-4">Tambah Produk</h2>

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" min="1" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('name')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Stock -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('stock')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="text" name="price" id="addPrice" value="{{ old('price') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('price')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Expired Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Kadaluarsa (optional)</label>
                        <input type="date" min="{{ now()->toDateString() }}" name="exp" value="{{ old('exp') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('exp')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action -->
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="resetForm(); document.getElementById('modal-tambah-product').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                    </div>
                </form>

        </div>
    </div>
</div>


<!-- Modal Edit -->
<div id="modal-edit-product" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <button onclick="document.getElementById('modal-edit-product').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>
            <h2 class="text-lg font-semibold mb-4">Edit Product</h2>

            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                    <!-- Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Code</label>
                        <input type="text" name="code" id="editCode" value="{{ old('code') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm" readonly>
                        @error('code')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" id="editName" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('name')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Stok -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" min="1" name="stock" id="editStock" value="{{ old('stock') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('stock')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="text" name="price" id="editPrice" value="{{ old('price') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('price')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Expired Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Kadaluarsa (optional)</label>
                        <input type="date" min="{{ now()->toDateString() }}" id="editExp" name="exp" value="{{ old('exp') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('exp')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="resetForm(); document.getElementById('modal-edit-product').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    function openEditModal(product) {
        console.log('Produk for modal:', product);

    document.getElementById('modal-edit-product').classList.remove('hidden');
    document.getElementById('editProductForm').action = `/products/${product.id}`;
    document.getElementById('editCode').value = product.code;
    document.getElementById('editName').value = product.name;
    document.getElementById('editStock').value = product.stock;
    document.getElementById('editExp').value = product.exp;
    const formattedPrice = formatRupiah(String(product.price));
    document.getElementById('editPrice').value = formattedPrice;
    }

function confirmDelete(productId) {
    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Data ini akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${productId}`).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const products = @json($products);
    const today = new Date();

    let lowStock = [];
    let expSoon = [];

    products.forEach(product => {
        if (product.stock < 5) {
            lowStock.push(`<b>${product.name}</b> — Stok tersisa <b>${product.stock}</b> item`);
        }

        if (product.exp) {
            const expDate = new Date(product.exp);
            const diffTime = expDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (diffDays > 0 && diffDays <= 5) {
                expSoon.push(`<b>${product.name}</b> — Kadaluarsa dalam <b>${diffDays}</b> hari (${expDate.toLocaleDateString('id-ID')})`);
            }
        }
    });

    let htmlContent = '';

    if (lowStock.length > 0) {
        htmlContent += `
            <div style="margin-bottom:10px;">
                <h3 style="color:#eab308; margin-bottom:5px;">Stok Hampir Habis</h3>
                <ul style="text-align:left; margin:0; padding-left:20px;">
                    ${lowStock.map(item => `<li>${item}</li>`).join('')}
                </ul>
            </div>
        `;
    }

    if (expSoon.length > 0) {
        htmlContent += `
            <div>
                <h3 style="color:#3b82f6; margin-bottom:5px;">Produk Hampir Kadaluarsa</h3>
                <ul style="text-align:left; margin:0; padding-left:20px;">
                    ${expSoon.map(item => `<li>${item}</li>`).join('')}
                </ul>
            </div>
        `;
    }

    if (htmlContent) {
        Swal.fire({
            title: '📦 Peringatan Produk',
            html: htmlContent,
            icon: 'warning',
            confirmButtonText: 'Mengerti',
            width: 600,
            showCloseButton: true,
            customClass: {
                popup: 'rounded-2xl shadow-lg'
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    @if($errors->any())
        document.getElementById('modal-tambah-product').classList.remove('hidden');
    @endif
});

function resetForm() {
    const form = document.querySelector('#modal-tambah-product form');
    form.reset();
}

document.querySelector('#modal-tambah-product .absolute').addEventListener('click', function() {
    resetForm();
    document.getElementById('modal-tambah-product').classList.add('hidden');
});

function formatRupiah(angka, prefix = 'Rp ') {
    let numberString = angka.replace(/[^,\d]/g, '').toString();
    let split = numberString.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix + rupiah;
}

function unformatRupiah(rupiah) {
    return rupiah.replace(/[^0-9]/g, '');
}

document.addEventListener('DOMContentLoaded', function () {
    const addPriceInput = document.getElementById('addPrice');
    const editPriceInput = document.getElementById('editPrice');

    if (addPriceInput) {
        addPriceInput.addEventListener('keyup', function () {
            this.value = formatRupiah(this.value);
        });
    }

    if (editPriceInput) {
        editPriceInput.addEventListener('keyup', function () {
            this.value = formatRupiah(this.value);
        });
    }
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
            const priceInputs = this.querySelectorAll('input[name="price"]');
            priceInputs.forEach(input => {
                input.value = unformatRupiah(input.value);
            });
        });
    });
});


</script>

@if (session('success') || session('error'))
    <div id="flash-message"
         data-type="{{ session('success') ? 'success' : 'error' }}"
         data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif

@if(session('editProduct'))
    <script>
        window.onload = function() {
            openEditModal(@json(session('editProduct')));
        }
    </script>
@endif



@endsection
