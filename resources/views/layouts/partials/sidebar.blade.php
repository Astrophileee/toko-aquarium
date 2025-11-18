<div id="overlay" onclick="closeSidebar()" class="fixed inset-0 bg-[rgba(0,0,0,0.75)] z-30 hidden lg:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed z-40 top-0 left-0 w-64 min-h-screen bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto">
    <div class="p-4 flex items-center gap-2">
        <div>
            <h1 class="font-bold text-sm">Fisher Aquarium</h1>
            <p class="text-xs text-gray-500">Kasir</p>
        </div>
    </div>
    <nav class="mt-4 space-y-2 text-sm">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fas fa-home w-5 h-5 pt-1 text-gray-600"></i>
        Dashboard
    </a>

    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fa-solid fa-user w-5 h-5 pt-1 text-gray-600"></i>
        Data Pengguna
    </a>

    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fa-solid fa-newspaper w-5 h-5 pt-1 text-gray-600"></i>
        Data Produk
    </a>
    <a href="{{ route('consumers.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fa-solid fa-comments w-5 h-5 pt-1 text-gray-600"></i>
        Data Konsumen
    </a>
    <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fa-solid fa-inbox w-5 h-5 pt-1 text-gray-600"></i>
        Transaksi
    </a>
    </nav>
</aside>
