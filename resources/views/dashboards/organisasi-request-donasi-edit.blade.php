
@extends('layouts.app')

@section('title', 'Organisasi - Edit Donation Request')

@section('content')
    <div class="flex">
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-blue-600">ReUseMart - Organisasi</h2>
            </div>
            <nav class="mt-6">
                <a href="{{ route('organisasi.dashboard') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('organisasi.dashboard') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">🏠</span> Dashboard
                </a>
                <a href="{{ route('organisasi.request-donasi.index') }}" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 {{ request()->routeIs('organisasi.request-donasi.index') ? 'bg-gray-200' : '' }}">
                    <span class="mr-2">📦</span> Manage Donation Requests
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
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Donation Request: {{ $requestDonasi->id_request }}</h1>

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

                <form id="editDonationRequestForm" method="POST" action="{{ route('organisasi.request-donasi.update', $requestDonasi->id_request) }}" class="grid grid-cols-1 gap-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="deskripsi" id="deskripsi" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" rows="4" required>{{ old('deskripsi', $requestDonasi->deskripsi) }}</textarea>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" required>
                            <option value="Pending" {{ old('status', $requestDonasi->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ old('status', $requestDonasi->status) == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ old('status', $requestDonasi->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('organisasi.request-donasi.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update Donation Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editDonationRequestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Pembaruan Permintaan Donasi</h3>
            <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin memperbarui Permintaan Donasi ini?</p>
            <div class="flex justify-end space-x-4">
                <button id="cancelEditDonationRequest" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</button>
                <button id="confirmEditDonationRequest" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Ya, Perbarui</button>
            </div>
        </div>
    </div>

    <script>
        const editDonationRequestForm = document.getElementById('editDonationRequestForm');
        const editDonationRequestModal = document.getElementById('editDonationRequestModal');
        const cancelEditDonationRequest = document.getElementById('cancelEditDonationRequest');
        const confirmEditDonationRequest = document.getElementById('confirmEditDonationRequest');

        editDonationRequestForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const deskripsi = document.getElementById('deskripsi').value.trim();
            const status = document.getElementById('status').value;
            if (!deskripsi) {
                showErrorModal('Description cannot be empty');
                return;
            }
            if (!['Pending', 'Approved', 'Rejected'].includes(status)) {
                showErrorModal('Invalid status selected');
                return;
            }
            editDonationRequestModal.classList.remove('hidden');
        });

        cancelEditDonationRequest.addEventListener('click', function() {
            editDonationRequestModal.classList.add('hidden');
            showErrorModal('Donation request update cancelled');
        });

        confirmEditDonationRequest.addEventListener('click', function() {
            editDonationRequestModal.classList.add('hidden');
            const status = document.getElementById('status').value;
            editDonationRequestForm.submit();
            if (status === 'Approved') {
                showActivateModal('Donation request activated successfully!');
            } else if (status === 'Rejected') {
                showDeactivateModal('Donation request deactivated successfully!');
            } else {
                showSuccessModal('Donation request updated successfully!');
            }
        });

        @if ($errors->any())
            showErrorModal('Failed to update donation request. Please check your input.');
        @endif
    </script>
@endsection
