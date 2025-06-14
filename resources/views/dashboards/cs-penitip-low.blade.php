<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penitip dengan Saldo Rendah - ReUseMart</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>


@extends('layouts.app')

@section('title', 'CS - Manage Penitip')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Manage Penitip</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Last updated: {{ now()->format('H:i A, d M Y') }}</span>
                    <img src="https://via.placeholder.com/40" alt="User" class="rounded-full">
                </div>
            </div>

            <!-- Search and Create Button -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="flex justify-between items-center">
                    <form method="GET" action="{{ route('cs.penitip.index') }}" class="flex space-x-4">
                        <input type="text" name="search" placeholder="Search by Name, ID, Email, or KTP" class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64" value="{{ request('search') }}">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Search</button>
                    </form>
                    <a href="{{ route('cs.penitip.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 flex items-center">
                        <span class="mr-2">+</span> Add Penitip
                    </a>
                </div>
            </div>

            <!-- Penitip Table -->
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
                                <th class="p-3 text-sm font-medium text-gray-600">ID Penitip</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Nama</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Saldo</th>
                                <th class="p-3 text-sm font-medium text-gray-600">Jumlah Jual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penitipList as $penitip)
                                <tr class="border-b hover:bg-gray-50 transition duration-150">
                                    <td class="p-3 text-sm text-gray-700">{{ $penitip->id_penitip }}</td>
                                    
                                    <td class="p-3 text-sm text-gray-700">{{ $penitip->nama }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $penitip->saldo }}</td>
                                    <td class="p-3 text-sm text-gray-700">{{ $penitip->jumlah_jual }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-3 text-center text-gray-500">No penitips found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                
            </div>
        </main>
    </div>
@endsection