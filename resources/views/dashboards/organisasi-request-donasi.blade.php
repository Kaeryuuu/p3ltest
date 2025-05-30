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

            <!-- Search and Create -->
            <div class="mb-6 flex justify-between">
                <form method="GET" action="{{ route('organisasi.request-donasi.index') }}" class="flex">
                    <input type="text" name="search" class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by description or status" value="{{ request('search') }}">
                    <button type="submit" class="ml-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Search</button>
                </form>
                <a href="{{ route('organisasi.request-donasi.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Create New Donation Request</a>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Table -->
            <div class="bg-white p-6 rounded-lg shadow">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $request->id_request }}</td>
                                <td class="px-4 py-2">{{ $request->deskripsi }}</td>
                                <td class="px-4 py-2">{{ $request->tanggal_permintaan->format('d M Y') }}</td>
                                <td class="px-4 py-2">{{ $request->status }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('organisasi.request-donasi.edit', $request->id_request) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('organisasi.request-donasi.destroy', $request->id_request) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Are you sure you want to delete this Donation Request?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $requests->links() }}
                </div>
            </div>
        </main>
    </div>
@endsection