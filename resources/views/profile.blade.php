<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Profile Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DataTable CSS -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        /* Custom DataTable Styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            background-color: #f9fafb;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            outline: 2px solid #6366f1;
            border-color: #6366f1;
        }

        table.dataTable {
            border-collapse: collapse;
            width: 100%;
            border: none;
        }

        table.dataTable thead th {
            background-color: #f9fafb;
            color: #4b5563;
            font-weight: 600;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        table.dataTable tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }

        table.dataTable tbody tr:hover {
            background-color: #f3f4f6;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: none !important;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem !important;
            margin: 0 0.25rem;
            background: transparent !important;
            color: #4b5563 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6 !important;
            color: #111827 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #6366f1 !important;
            color: white !important;
            border: none !important;
        }

        .dataTables_wrapper .dataTables_info {
            font-size: 0.875rem;
            color: #6b7280;
            padding-top: 1rem;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto py-8 px-4">
        <!-- Flex container for Profile and Transaction History -->
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Profile Section -->
            <div class="bg-white rounded-xl shadow-sm p-8 w-full md:w-1/3 h-fit">
                <div class="flex flex-col items-center">
                    <div class="relative mb-6">
                        <img src="https://via.placeholder.com/120" alt="Profile Photo" class="w-28 h-28 rounded-full object-cover ring-4 ring-indigo-50">
                        <span class="absolute bottom-1 right-1 bg-green-400 w-4 h-4 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="text-center">
                        <h2 class="text-2xl font-bold mb-1 text-gray-800">John Doe</h2>
                        <p class="text-gray-500 mb-4">johndoe@example.com</p>
                        <div class="bg-indigo-50 rounded-lg py-3 px-4 inline-block">
                            <p class="text-lg text-indigo-700">
                                <span class="font-normal">Poin Reward:</span>
                                <span class="font-bold">1,200</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History Section -->
            <div class="bg-white rounded-xl shadow-sm p-8 w-full md:w-2/3">
                <h3 class="text-xl font-bold mb-6 text-gray-800">Riwayat Transaksi</h3>

                <div class="overflow-hidden">
                    <table id="transactionTable" class="w-full">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2025-05-01</td>
                                <td>Rp 1.200.000</td>
                                <td><span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span></td>
                                <td><button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Detail</button></td>
                            </tr>
                            <tr>
                                <td>2025-04-25</td>
                                <td>Rp 850.000</td>
                                <td><span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span></td>
                                <td><button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Detail</button></td>
                            </tr>
                            <tr>
                                <td>2025-04-18</td>
                                <td>Rp 725.000</td>
                                <td><span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Diproses</span></td>
                                <td><button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Detail</button></td>
                            </tr>
                            <tr>
                                <td>2025-04-10</td>
                                <td>Rp 1.050.000</td>
                                <td><span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span></td>
                                <td><button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Detail</button></td>
                            </tr>
                            <tr>
                                <td>2025-04-05</td>
                                <td>Rp 375.000</td>
                                <td><span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span></td>
                                <td><button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Detail</button></td>
                            </tr>
                            <tr>
                                <td>2025-03-27</td>
                                <td>Rp 925.000</td>
                                <td><span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span></td>
                                <td><button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Detail</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTable JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#transactionTable').DataTable({
                responsive: true,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Cari transaksi...",
                    lengthMenu: "Tampilkan _MENU_ transaksi",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ transaksi",
                    infoEmpty: "Tidak ada transaksi yang tersedia",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Improve search input
            $('.dataTables_filter input').addClass('focus:outline-none focus:ring-2 focus:ring-indigo-500');
        });
    </script>
</body>

</html>