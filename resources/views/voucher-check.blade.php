<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengecekan Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-center mb-6">Cek Voucher</h2>

                <form method="POST" action="{{ route('check.voucher.submit') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="nama" id="nama" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                            placeholder="Masukkan Nama Lengkap">
                    </div>

                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700">No ID Card</label>
                        <input type="text" name="nik" id="nik" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                            placeholder="Masukkan Nomor Induk Karyawan/ID Card">
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">
                            Cari Voucher
                        </button>
                    </div>
                </form>

                @isset($vouchers)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-4">Daftar Voucher</h3>

                        @foreach ($vouchers as $voucher)
                            <div class="border-t pt-4 mb-4">
                                <div class="bg-gray-50 p-4 rounded-md">
                                    @if ($voucher->is_claim == '1')
                                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative"
                                            role="alert">
                                            <strong>Peringatan!</strong> Voucher sudah diklaim.
                                        </div>
                                    @elseif ($voucher->voucher)
                                        <p><strong>Nama:</strong> {{ $voucher->nama }}</p>
                                        <p><strong>Kode Voucher:</strong> {{ $voucher->kode_voucher }}</p>
                                        <p><strong>Tanggal Kadaluarsa:</strong>
                                            {{ \Carbon\Carbon::parse($voucher->kadaluarsa)->translatedFormat('d F Y') }}
                                        </p>
                                        @if ($voucher->saldo)
                                            <p><strong>Saldo:</strong> Rp. {{ $voucher->saldo }}</p>
                                        @endif

                                        @if ($voucher->isExpired)
                                            <div class="mt-2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                                role="alert">
                                                <strong>Peringatan!</strong> Voucher telah kadaluarsa.
                                            </div>
                                        @else
                                            <div class="mt-2 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                                                role="alert">
                                                @if ($voucher->type === 'Doorprize')
                                                    <strong>Selamat!</strong> <br>
                                                    Voucher Doorprize masih berlaku.
                                                @else
                                                    <strong>Selamat!</strong> <br>
                                                    Voucher Discount masih berlaku.
                                                @endif
                                            </div>

                                            {{-- @if ($voucher->voucher) --}}
                                            <div class="flex justify-center mt-2">
                                                <a href="{{ asset('storage/vouchers/' . $voucher->voucher) }}"
                                                    target="_blank">
                                                    <img src="{{ asset('storage/vouchers/' . $voucher->voucher) }}"
                                                        alt="Gambar Voucher" class="max-w-full h-auto rounded-lg shadow-md">
                                                </a>
                                            </div>
                                            <div class="flex justify-end mt-2">
                                                <a href="{{ asset('storage/vouchers/' . $voucher->voucher) }}"
                                                    class="btn btn-primary btn-sm" download>
                                                    Download <i class="mdi mdi-download"></i>
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative"
                                            role="alert">
                                            <strong>Peringatan!</strong> Voucher tidak ditemukan.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endisset

                @if ($errors->any())
                    <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        @foreach ($errors->all() as $error)
                            <strong>Peringatan!</strong> {{ $error }}
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
