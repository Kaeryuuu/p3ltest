@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Update Profile</h2>

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

            <form id="updateProfileForm" method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="nama" id="nama" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('nama', $user->nama) }}" required>
                </div>
                <div class="mb-4">
                    <label for="telepon" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="telepon" id="telepon" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('telepon', $user->telepon) }}" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password (Leave blank to keep unchanged)</label>
                    <input type="password" name="password" id="password" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 p-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg w-full hover:bg-blue-700 transition duration-200">Update Profile</button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById('updateProfileForm').addEventListener('submit', function(event) {
            if (!confirm('Are you sure you want to update your profile?')) {
                event.preventDefault();
            }
        });
    </script>
@endsection