@extends('layouts.app') {{-- Pastikan layout ini ada dan memiliki Tailwind CSS --}}

@section('title', 'Register - ReUseMart')

@section('content')
<body class="bg-gradient-to-br from-blue-100 via-indigo-50 to-gray-100"> {{-- Background yang sama dengan login --}}
    <div class="min-h-screen flex flex-col items-center justify-center py-10 px-4">
        <div class="mb-8">
            <a href="{{ route('homepage') }}"> {{-- Arahkan ke halaman utama atau login --}}
                 <img src="https://via.placeholder.com/180x60?text=ReUseMart" alt="ReUseMart Logo" class="h-14">
            </a>
            </div>

        <div class="bg-white p-8 sm:p-10 rounded-xl shadow-2xl w-full max-w-xl">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Buat Akun Baru</h2>
            <p class="text-center text-gray-500 mb-8">Bergabunglah dengan ReUseMart hari ini!</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex">
                        <div>
                            <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zM10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1-11a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2h-2a1 1 0 0 1-1-1zm0 4a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2h-2a1 1 0 0 1-1-1z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold">Oops! Ada beberapa kesalahan:</p>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                     <div class="flex">
                        <div>
                           <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zM10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6l-2.3-2.3a1 1 0 1 0-1.4 1.4l3 3a1 1 0 0 0 1.4 0l5-5z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold">Sukses!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-6 border-b border-gray-200">
                <nav class="flex -mb-px" aria-label="Tabs">
                    <button class="user-tab active w-1/2 py-4 px-1 text-center border-b-2 border-blue-600 font-medium text-sm text-blue-600 hover:text-blue-700 focus:outline-none" data-tab="user">
                        Sebagai Pengguna
                    </button>
                    <button class="user-tab w-1/2 py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none" data-tab="organisasi">
                        Sebagai Organisasi
                    </button>
                </nav>
            </div>

            <div class="tab-content" id="user">
                <form id="registerPembeliForm" method="POST" action="{{ route('registerUser') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ old('nama') }}" required placeholder="John Doe">
                    </div>
                    <div>
                        <label for="email_user" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email_user" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ old('email') }}" required placeholder="pengguna@email.com">
                    </div>
                    <div>
                        <label for="telepon_user" class="block text-sm font-semibold text-gray-700 mb-1">Telepon</label>
                        <input type="text" name="telepon" id="telepon_user" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ old('telepon') }}" required placeholder="08123456789">
                    </div>
                    <div>
                        <label for="password_user" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password_user" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required placeholder="••••••••">
                    </div>
                    <div>
                        <label for="password_confirmation_user" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation_user" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-300">
                        Register sebagai Pengguna
                    </button>
                </form>
            </div>

            <div class="tab-content hidden" id="organisasi">
                <form id="registerOrganisasiForm" method="POST" action="{{ route('registerPost') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="nama_org" class="block text-sm font-semibold text-gray-700 mb-1">Nama Organisasi</label>
                        <input type="text" name="nama" id="nama_org" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ old('nama_org') }}" required placeholder="Nama Organisasi Anda">
                    </div>
                    <div>
                        <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Organisasi</label>
                        <textarea name="deskripsi" id="deskripsi" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" rows="3" placeholder="Jelaskan tentang organisasi Anda">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div>
                        <label for="email_org" class="block text-sm font-semibold text-gray-700 mb-1">Email Organisasi</label>
                        <input type="email" name="email" id="email_org" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ old('email_org') }}" required placeholder="organisasi@email.com">
                    </div>
                    <div>
                        <label for="telepon_org" class="block text-sm font-semibold text-gray-700 mb-1">Telepon Organisasi</label>
                        <input type="text" name="telepon" id="telepon_org" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" value="{{ old('telepon_org') }}" required placeholder="021-xxxxxxx">
                    </div>
                    <div>
                        <label for="password_org" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password_org" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required placeholder="••••••••">
                    </div>
                    <div>
                        <label for="password_confirmation_org" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation_org" class="mt-1 p-3 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-300">
                        Register sebagai Organisasi
                    </button>
                </form>
            </div>

            <p class="mt-8 text-center text-sm text-gray-600">
                Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline hover:text-blue-700">Login disini</a>
            </p>
        </div>
    </div>

    <style>
        .modal-active { /* Untuk animasi jika diinginkan */
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease;
        }
        .modal-inactive {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-20px);
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease 0.1s;
        }
    </style>

    <div id="pembeliModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center p-4 modal-inactive z-50">
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xl max-w-md w-full transform transition-all">
            <div class="flex justify-between items-start">
                <h3 class="text-xl font-semibold text-gray-800 mb-1">Konfirmasi Registrasi</h3>
                <button onclick="document.getElementById('pembeliModal').classList.replace('modal-active', 'modal-inactive')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <p class="text-sm text-gray-600 mt-2 mb-6">Apakah Anda yakin ingin mendaftar sebagai Pengguna?</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelPembeli" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">Batal</button>
                <button id="confirmPembeli" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">Ya, Daftar</button>
            </div>
        </div>
    </div>

    <div id="organisasiModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center p-4 modal-inactive z-50">
         <div class="bg-white p-6 sm:p-8 rounded-xl shadow-xl max-w-md w-full transform transition-all">
            <div class="flex justify-between items-start">
                <h3 class="text-xl font-semibold text-gray-800 mb-1">Konfirmasi Registrasi</h3>
                 <button onclick="document.getElementById('organisasiModal').classList.replace('modal-active', 'modal-inactive')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <p class="text-sm text-gray-600 mt-2 mb-6">Apakah Anda yakin ingin mendaftar sebagai Organisasi?</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelOrganisasi" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">Batal</button>
                <button id="confirmOrganisasi" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">Ya, Daftar</button>
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        const tabs = document.querySelectorAll('.user-tab');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => {
                    t.classList.remove('active', 'border-blue-600', 'text-blue-600');
                    t.classList.add('text-gray-500', 'border-transparent', 'hover:text-gray-700', 'hover:border-gray-300');
                });
                this.classList.add('active', 'border-blue-600', 'text-blue-600');
                this.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');

                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById(this.dataset.tab).classList.remove('hidden');
            });
        });

        // Modal Handling Helper
        function setupModal(formId, modalId, cancelBtnId, confirmBtnId) {
            const form = document.getElementById(formId);
            const modal = document.getElementById(modalId);
            const cancelBtn = document.getElementById(cancelBtnId);
            const confirmBtn = document.getElementById(confirmBtnId);

            if (!form || !modal || !cancelBtn || !confirmBtn) {
                console.error('Modal elements not found for', modalId);
                return;
            }

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                modal.classList.remove('modal-inactive');
                modal.classList.add('modal-active');
            });

            cancelBtn.addEventListener('click', function() {
                modal.classList.remove('modal-active');
                modal.classList.add('modal-inactive');
            });

            // Tambahkan event listener untuk tombol close (x) di modal jika ada
            const closeButton = modal.querySelector('button[onclick*="classList.replace"]');
            if(closeButton) {
                closeButton.addEventListener('click', function() {
                     modal.classList.remove('modal-active');
                     modal.classList.add('modal-inactive');
                });
            }


            confirmBtn.addEventListener('click', function() {
                modal.classList.remove('modal-active');
                modal.classList.add('modal-inactive');
                form.submit(); // Submit the actual form
            });
        }

        // Pembeli Modal Handling
        setupModal('registerPembeliForm', 'pembeliModal', 'cancelPembeli', 'confirmPembeli');

        // Organisasi Modal Handling
        setupModal('registerOrganisasiForm', 'organisasiModal', 'cancelOrganisasi', 'confirmOrganisasi');

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                document.querySelectorAll('.modal-active').forEach(modal => {
                    modal.classList.remove('modal-active');
                    modal.classList.add('modal-inactive');
                });
            }
        });
    </script>
</body>
@endsection