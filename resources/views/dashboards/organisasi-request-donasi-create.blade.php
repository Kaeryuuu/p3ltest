
@extends('layouts.app')

@section('title', 'Organisasi - Create Donation Request')

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
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Create New Donation Request</h1>

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
                        <p class="text-sm">{{ session('success.message') }}</p>
                    </div>
                @endif

                <form id="createDonationRequestForm" method="POST" action="{{ route('organisasi.request-donasi.store') }}" class="grid grid-cols-1 gap-6">
                    @csrf
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="deskripsi" id="deskripsi" class="mt-1 p-2 border border-gray-300 rounded-lg w-full" rows="4" required>{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('organisasi.request-donasi.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                        <button type="button" onclick="showConfirmDialog()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Create Donation Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="confirmDialog" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Confirm Donation Request</h2>
            <p class="text-gray-700 mb-4">Are you sure you want to create this donation request?</p>
            <p class="text-gray-700 mb-4">
                <strong>Description:</strong> <span id="confirmDeskripsi"></span>
            </p>
            <div class="flex justify-end space-x-4">
                <button onclick="cancelConfirm()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Cancel</button>
                <button onclick="submitForm()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Confirm</button>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div id="successResultModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Donation Request Added Successfully</h2>
            <p class="text-gray-700 mb-4">{{ session('success.message') }}</p>
            <p class="text-gray-700 mb-4">
                <strong>ID Organisasi:</strong> {{ session('success.ID Organisasi') }} <br>
                <strong>Nama Organisasi:</strong> {{ session('success.Nama Organisasi') }} <br>
                <strong>Description:</strong> {{ session('success.deskripsi') }} <br>
            </p>
            <div class="flex justify-end space-x-4">
                <button onclick="addAnother()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add Another</button>
                <a href="{{ route('organisasi.request-donasi.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">View All Donation Requests</a>
            </div>
        </div>
    </div>
    @endif

    <script>
        function showConfirmDialog() {
            const deskripsi = document.getElementById('deskripsi').value.trim();
            if (!deskripsi) {
                showErrorModal('Description cannot be empty');
                return;
            }
            document.getElementById('confirmDeskripsi').textContent = deskripsi;
            document.getElementById('confirmDialog').classList.remove('hidden');
        }

        function cancelConfirm() {
            document.getElementById('confirmDialog').classList.add('hidden');
            showErrorModal('Donation request creation cancelled');
        }

        function submitForm() {
            document.getElementById('confirmDialog').classList.add('hidden');
            document.getElementById('createDonationRequestForm').submit();
            showSuccessModal('Donation request created successfully!');
        }

        function addAnother() {
            document.getElementById('successResultModal').remove();
            document.getElementById('createDonationRequestForm').reset();
        }

        @if ($errors->any())
            showErrorModal('Failed to create donation request. Please check your input.');
        @endif

        @if (session('success'))
            document.getElementById('successResultModal').classList.remove('hidden');
        @endif
    </script>
@endsection
