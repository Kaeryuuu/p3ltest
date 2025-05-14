@extends('layouts.app')

@section('title', 'Organisasi - Manage Donation Requests')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Manage Donation Requests</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Last updated: {{ now()->format('H:i A, d M Y') }}</span>
                    <img src="https://via.placeholder.com/40" alt="User" class="rounded-full">
                </div>
            </div>

            <!-- Search and Create Button -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="flex justify-between items-center">
                    <form method="GET" action="{{ route('organisasi.request-donasi.index') }}" class="flex space-x-4">
                        <input type="text" name="search" placeholder="Search by Description, Status, or Date" class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64" value="{{ request('search') }}">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Search</button>
                    </form>
                    <a href="{{ route('organisasi.request-donasi.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 flex items-center">
                        <span class="mr-2">+</span> Add Donation Request
                    </a>
                </div>
            </div>

            <!-- Requests Table -->
            <div class="bg-white p-6 rounded-lg shadow">
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="p-3 text-sm font-medium text-gray-600">ID Request</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Description</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Date</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Status</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $request)
                                <tr class="border-b hover:bg-gray-50 transition duration-150">
                                    <td class="p-3 text-sm text-gray-700">{{ $request->id_request }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $request->deskripsi }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $request->tanggal_permintaan->format('d M Y') }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $request->status }}</td>
                                    <td class="p-3 text-sm text-gray-700">
                                        <a href="{{ route('organisasi.request-donasi.edit', $request->id_request) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                                        <form action="{{ route('organisasi.request-donasi.destroy', $request->id_request) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this donation request?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-3 text-center text-gray-500">No donation requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $requests->appends(request()->query())->links() }}
                </div>
            </div>
        </main>
    </div>
@endsection