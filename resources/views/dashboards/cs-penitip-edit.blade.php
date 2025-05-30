
@extends('layouts.app')

@section('title', 'CS - Edit Penitip')

@section('content')
    <div class="flex">
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-blue-600">ReUseMart - CS</h2>
            </div>
            <nav class="mt-6">
                <a href="{{ route('cs.dashboard') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('cs.dashboard') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">🏠</span> Dashboard
                </a>
                <a href="{{ route('cs.penitip.index') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('cs.penitip.index') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">👤</span> Manage Penitip
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200">
                    <span class="mr-2">⚙️</span> Settings
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200">
                    <span class="mr-2">❓</span> Help
                </a>
            </nav>
        </aside>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Penitip: {{ $penitip->id_penitip }}</h1>

            <div class="bg-white p-6 rounded-lg shadow max-w-4xl">
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                <form id="editPenitipForm" method="POST" action="{{ route('cs.penitip.update', $penitip->id_penitip) }}" class="grid grid-cols-1 md:grid-cols-2 gap-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="no_ktp" class="block text-sm font-medium text-gray-700">KTP Number</label>
                        <input type="text" id="no_ktp" class="mt-1 p-2 border border-gray-300 rounded-lg w-full bg-gray-100" value="{{ $penitip->no_ktp }}" readonly>
                    </div>
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="nama" id="nama" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" value="{{ old('nama', $penitip->nama) }}" required>
                    </div>
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="telepon" id="telepon" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" value="{{ old('telepon', $penitip->telepon) }}" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" value="{{ old('email', $penitip->email) }}" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password (Leave blank to keep unchanged)</label>
                        <input type="password" name="password" id="password" class="mt-1 p-2 border border-gray-300 rounded-lg w-full">
                    </div>
                    <div>
                        <label for="foto_ktp" class="block text-sm font-medium text-gray-700">KTP Photo (Leave blank to keep current)</label>
                        <input type="file" name="foto_ktp" id="foto_ktp" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" accept="image/*">
                        @if ($penitip->url_foto)
                            <img src="{{ $penitip->url_foto }}" alt="Current KTP Photo" class="mt-2 w-32 h-20 object-cover rounded">
                        @endif
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" required>
                            <option value="Active" {{ old('status', $penitip->status ?? 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status', $penitip->status ?? 'Active') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 flex justify-end space-x-4">
                        <a href="{{ route('cs.penitip.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update Penitip</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editPenitipModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Pembaruan Penitip</h3>
            <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin memperbarui Penitip ini?</p>
            <div class="flex justify-end space-x-4">
                <button id="cancelEditPenitip" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</button>
                <button id="confirmEditPenitip" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Ya, Perbarui</button>
            </div>
        </div>
    </div>

    <script>
        const editPenitipForm = document.getElementById('editPenitipForm');
        const editPenitipModal = document.getElementById('editPenitipModal');
        const cancelEditPenitip = document.getElementById('cancelEditPenitip');
        const confirmEditPenitip = document.getElementById('confirmEditPenitip');

        editPenitipForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const nama = document.getElementById('nama').value.trim();
            const telepon = document.getElementById('telepon').value.trim();
            const email = document.getElementById('email').value.trim();
            const status = document.getElementById('status').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!nama) {
                showErrorModal('Name cannot be empty');
                return;
            }
            if (!telepon) {
                showErrorModal('Phone cannot be empty');
                return;
            }
            if (!email || !emailRegex.test(email)) {
                showErrorModal('Please enter a valid email');
                return;
            }
            if (!['Active', 'Inactive'].includes(status)) {
                showErrorModal('Invalid status selected');
                return;
            }
            editPenitipModal.classList.remove('hidden');
        });

        cancelEditPenitip.addEventListener('click', function() {
            editPenitipModal.classList.add('hidden');
            showErrorModal('Penitip update cancelled');
        });

        confirmEditPenitip.addEventListener('click', function() {
            editPenitipModal.classList.add('hidden');
            const status = document.getElementById('status').value;
            editPenitipForm.submit();
            if (status === 'Active') {
                showActivateModal('Penitip activated successfully!');
            } else if (status === 'Inactive') {
                showDeactivateModal('Penitip deactivated successfully!');
            } else {
                showSuccessModal('Penitip updated successfully!');
            }
        });

        @if ($errors->any())
            showErrorModal('Failed to update penitip. Please check your input.');
        @endif
    </script>
@endsection
