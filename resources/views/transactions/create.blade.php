@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Kasir</h1>

    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <!-- Search Produk -->
                        <div class="mb-4">
                            <label for="search-product">Cari Produk: </label>
                            <input type="text" id="search-product" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Search product...">
                        </div>


                        <!-- Daftar Produk -->
                        <div id="product-list" class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        </div>

                        <!-- Search Konsumen -->
                        <div class="mb-4">
                            <label for="search-product">Cari Konsumen: </label>
                            <input type="text" id="search-consumer" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Search konsumen...">
                        </div>

                        <!-- Daftar Konsumen -->
                        <div id="consumer-list" class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        </div>

                        <div id="consumer_info" class="mt-4 hidden">
                            <p><strong>Nama:</strong> <span id="consumer_name_display"></span></p>
                            <p><strong>Telepon:</strong> <span id="consumer_phone_display"></span></p>
                            <p><strong>Alamat:</strong> <span id="consumer_address_display"></span></p>
                        </div>
                        <input type="hidden" id="consumer_id" name="consumer_id" value="">

                        <!-- Keranjang -->
                        <h4 class="font-semibold mb-2">Cart</h4>
                        <table class="min-w-full border-collapse border border-gray-300" id="cart-table">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 border">Product</th>
                                    <th class="px-4 py-2 border">Qty</th>
                                    <th class="px-4 py-2 border">Price</th>
                                    <th class="px-4 py-2 border">Total</th>
                                    <th class="px-4 py-2 border">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Items in cart will be dynamically added here -->
                            </tbody>
                        </table>

                        <!-- Total Bayar -->
                        <div class="mt-4">
                            <h4 class="font-semibold">Total: <span id="total-price">0</span></h4>
                        </div>

                        <!-- Pembayaran -->
                        <div class="mt-4">
                            <label for="payment" class="block text-sm font-medium text-gray-700">Cash Received</label>
                            <input type="number" id="payment" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Enter amount received" oninput="calculateChange()">
                        </div>

                        <!-- Kembalian -->
                        <div class="mt-4">
                            <h4 class="font-semibold">Change: <span id="change">0</span></h4>
                        </div>

                        <!-- Submit -->
                        <div class="mt-4">
                            <button id="submit-transaction" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">
                                Submit Transaction
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@vite(['resources/css/app.css', 'resources/js/app.js'])

<script>
    let cart = [];
    let totalPrice = 0;
    let productStock = {};

    document.addEventListener("DOMContentLoaded", function() {
        $('#search-product').on('input', function() {
            let query = $(this).val();

            if (query.length < 2) {
                $('#product-list').empty();
                return;
            }
            $.ajax({
                url: "{{ route('transactions.search') }}",
                method: 'GET',
                data: { query: query },
                success: function(response) {
                    $('#product-list').empty();

                    if (response.length === 0) {
                        $('#product-list').append('<p class="text-gray-500">Tidak ada produk ditemukan.</p>');
                        return;
                    }

                    response.forEach(function(product) {
                        productStock[product.id] = product.stock;
                        let formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(product.price);
                        $('#product-list').append(`
                            <div class="border p-4 rounded-lg shadow-md">
                                <p class="font-semibold">${product.name}</p>
                                <p>Price: ${formattedPrice}</p>
                                <p>Stock: ${product.stock}</p>
                                <button onclick="addToCart(${product.id}, '${product.name}', ${product.price}, ${product.stock})"
                                    class="px-4 py-2 ${product.stock > 0 ? 'bg-blue-500' : 'bg-gray-500 cursor-not-allowed'} text-white rounded-md ${product.stock > 0 ? 'hover:bg-blue-600' : ''}"
                                    ${product.stock <= 0 ? 'disabled' : ''}>
                                    ${product.stock > 0 ? 'Add to Cart' : 'Stock Habis'}
                                </button>
                            </div>
                        `);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        });



        $('#search-consumer').on('input', function() {
            let query = $(this).val();

            if (query.length < 2) {
                $('#consumer-list').empty();
                return;
            }

            $.ajax({
                url: "{{ route('transactions.searchConsumer') }}",
                method: 'GET',
                data: { query: query },
                success: function(response) {
                    $('#consumer-list').empty();

                    if (response.length === 0) {
                        $('#consumer-list').append('<p class="text-gray-500">Tidak ada konsumen ditemukan.</p>');
                        return;
                    }

                    response.forEach(function(consumer) {
                        $('#consumer-list').append(`
                            <div class="border p-4 rounded-lg shadow-md hover:bg-blue-50 cursor-pointer"
                                onclick="selectConsumer(${consumer.id}, '${consumer.name}', '${consumer.phone_number ?? '-'}', '${consumer.address ?? '-'}')">
                                <p class="font-semibold">${consumer.name}</p>
                                <p>Telepon: ${consumer.phone_number ?? '-'}</p>
                                <p>Alamat: ${consumer.address ?? '-'}</p>
                            </div>
                        `);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error (consumer):", xhr.responseText);
                }
            });
        });
    });

    function selectConsumer(id, name, phone_number, address) {
        $('#consumer_id').val(id);
        $('#consumer_name_display').text(name);
        $('#consumer_phone_display').text(phone_number);
        $('#consumer_address_display').text(address);
        $('#consumer_info').removeClass('hidden');
        $('#consumer-list').empty();
        $('#search-consumer').val('');
    }


    function addToCart(productId, name, price, stock) {
        let existingItem = cart.find(item => item.productId === productId);
        if (existingItem) {
            if (existingItem.qty < stock) {
                existingItem.qty++;
            } else {
                alert('Jumlah melebihi stok!');
                existingItem.qty = stock;
            }
        } else {
            cart.push({ productId, name, price, qty: 1, stock: stock });
        }
        updateCartTable();
    }

    function updateQuantity(productId, qty) {
        const product = cart.find(item => item.productId === productId);
        if (product) {
            qty = parseInt(qty, 10);
            if (isNaN(qty) || qty < 1) {
                alert('Jumlah Tidak Boleh 0');
                qty = 1;
            } else if (qty > product.stock) {
                alert('Jumlah melebihi stok!');
                qty = product.stock;
            }
            product.qty = qty;
            product.total = product.qty * product.price;
            updateCartTable();
        }
    }

    function removeItemFromCart(productId) {
        cart = cart.filter(item => item.productId !== productId);
        updateCartTable();
    }

    function updateCartTable() {
        $('#cart-table tbody').empty();
        totalPrice = 0;
        cart.forEach(item => {
            let totalItemPrice = item.qty * item.price;
            totalPrice += totalItemPrice;
            $('#cart-table tbody').append(`
                <tr class="border">
                    <td class="px-4 py-2 border">${item.name}</td>
                    <td class="px-4 py-2 border">
                        <input type="number" min="1" max="${item.stock}" value="${item.qty}" onchange="updateQuantity(${item.productId}, this.value)" class="border rounded-md">
                    </td>
                    <td class="px-4 py-2 border">${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.price)}</td>
                    <td class="px-4 py-2 border">${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(totalItemPrice)}</td>
                    <td class="px-4 py-2 border">
                        <button onclick="removeItemFromCart(${item.productId})" class="text-red-500">Remove</button>
                    </td>
                </tr>
            `);
        });
        $('#total-price').text(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(totalPrice));
        calculateChange();
    }
    function calculateChange() {
        let payment = parseInt(document.getElementById('payment').value) || 0;
        let change = payment - totalPrice;

        document.getElementById('change').textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(change);

        let submitButton = document.getElementById('submit-transaction');
        if (payment < totalPrice) {
            submitButton.disabled = true;
            submitButton.classList.add('bg-gray-500', 'cursor-not-allowed');
            submitButton.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        } else {
            submitButton.disabled = false;
            submitButton.classList.remove('bg-gray-500', 'cursor-not-allowed');
            submitButton.classList.add('bg-blue-500', 'hover:bg-blue-600');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        $('#submit-transaction').on('click', function () {
            if (cart.length === 0) {
                alert('Keranjang kosong, tambahkan produk terlebih dahulu.');
                return;
            }

            const payment = parseInt($('#payment').val());
            const change = payment - totalPrice;

            if (payment < totalPrice) {
                alert('Uang yang diterima kurang dari total harga.');
                return;
            }
            const transactionData = {
                total_price: totalPrice,
                date: new Date().toISOString().split('T')[0],
                cart: cart,
                consumer_id: $('#consumer_id').val(),
            };
            $.ajax({
                url: "{{ route('transactions.store') }}",
                method: 'POST',
                data: transactionData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success: function (response) {
                    if (response.success) {
                        alert('Transaksi berhasil disimpan!');
                        location.reload();
                    } else {
                        alert('Terjadi kesalahan, coba lagi.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Gagal menyimpan transaksi.');
                }
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



@endsection
