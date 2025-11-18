@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Konsumen</h1>
        <button onclick="document.getElementById('modal-tambah-consumer').classList.remove('hidden')" class="bg-black text-white px-4 py-2 rounded-md shadow hover:bg-gray-800">
            Tambah
        </button>

    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table id="consumersTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No HP</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Note</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($consumers as $consumer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ ucwords($consumer->name) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $consumer->phone_number }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $consumer->address }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $consumer->note }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            <!-- Tombol Edit -->
                            <button type="button" class="inline-flex items-center px-4 py-2.5 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600"
                            onclick='openEditModal(@json($consumer))'>
                            Edit
                        </button>

                            <!-- Tombol Hapus -->
                            <form id="delete-form-{{ $consumer->id }}" action="{{ route('consumers.destroy', $consumer->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" class="inline-flex items-center px-4 py-2.5 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600"
                            onclick="confirmDelete({{ $consumer->id }})">Hapus</button>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <!-- Modal Tambah -->
<div id="modal-tambah-consumer" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <!-- Close button -->
            <button onclick="document.getElementById('modal-tambah-consumer').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>

            <h2 class="text-lg font-semibold mb-4">Tambah Pelanggan</h2>

                <form action="{{ route('consumers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" min="1" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('name')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Phone Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No HP</label>
                        <input type="number" name="phone_number" value="{{ old('phone_number') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('phone_number')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Note -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan (opsional)</label>
                        <textarea name="note" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Action -->
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="resetForm(); document.getElementById('modal-tambah-consumer').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                    </div>
                </form>
        </div>
    </div>
</div>


<!-- Modal Edit -->
<div id="modal-edit-consumer" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 hidden">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 relative">
            <button onclick="document.getElementById('modal-edit-consumer').classList.add('hidden')" class="absolute top-4 right-4 text-xl font-bold text-gray-600 hover:text-gray-800">&times;</button>
            <h2 class="text-lg font-semibold mb-4">Edit Konsumen</h2>

            <form id="editConsumerForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" id="editName" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('name')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Phone Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No HP</label>
                        <input type="number" name="phone_number" id="editPhone" value="{{ old('phone_number') }}" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">
                        @error('phone_number')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" id="editAddress" required class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Note -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan (optional)</label>
                        <textarea name="note" id="editNote" class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1 text-sm">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="resetForm(); document.getElementById('modal-edit-consumer').classList.add('hidden')" class="px-4 py-2 rounded-md border text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-md text-sm hover:bg-gray-800">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    function openEditModal(consumer) {
        console.log('Produk for modal:', consumer);

    document.getElementById('modal-edit-consumer').classList.remove('hidden');
    document.getElementById('editConsumerForm').action = `/consumers/${consumer.id}`;
    document.getElementById('editName').value = consumer.name;
    document.getElementById('editPhone').value = consumer.phone_number;
    document.getElementById('editAddress').value = consumer.address;
    document.getElementById('editNote').value = consumer.note;
    }

function confirmDelete(consumerId) {
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
            document.getElementById(`delete-form-${consumerId}`).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    @if($errors->any())
        document.getElementById('modal-tambah-consumer').classList.remove('hidden');
    @endif
});

function resetForm() {
    const form = document.querySelector('#modal-tambah-consumer form');
    form.reset();
}

document.querySelector('#modal-tambah-consumer .absolute').addEventListener('click', function() {
    resetForm();
    document.getElementById('modal-tambah-consumer').classList.add('hidden');
});

</script>

@if (session('success') || session('error'))
    <div id="flash-message"
         data-type="{{ session('success') ? 'success' : 'error' }}"
         data-message="{{ session('success') ?? session('error') }}">
    </div>
@endif

@if(session('editConsumer'))
    <script>
        window.onload = function() {
            openEditModal(@json(session('editConsumer')));
        }
    </script>
@endif



@endsection
