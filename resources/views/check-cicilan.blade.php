<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulasi Perhitungan Pinjaman Cash Gampang</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('assets/css/cicilan/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/cicilan/table.css') }}" rel="stylesheet">
    <style>
        /* Custom styles */
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="http://cashgampang.com">
                <img src="{{ asset('assets/images/logo-2.png') }}" alt="Cash Gampang" height="40">
            </a>

            <!-- Tombol Toggler untuk Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Navigasi -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-lg-flex d-none">
                    <!-- Tampilan Desktop -->
                    <a href="https://simulasi-pinjam.cash-gampang.com/" class="btn btn-primary my-2 my-lg-0">
                        Hitung Pinjaman
                    </a>
                    <a href="http://pengajuan-marketing.cash-gampang.com/topup-pengajuan"
                        class="btn btn-primary ms-lg-2 my-2 my-lg-0">
                        Top Up Pengajuan
                    </a>
                </div>
                <div class="d-lg-none text-center w-100">
                    <!-- Tampilan Mobile -->
                    <a href="https://simulasi-pinjam.cash-gampang.com/" class="btn btn-primary d-block my-2">
                        Hitung Pinjaman
                    </a>
                    <a href="http://pengajuan-marketing.cash-gampang.com/topup-pengajuan"
                        class="btn btn-primary d-block my-2">
                        Top Up Pengajuan
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Cek Cicilan Section (Initially Hidden) -->
    <div>
        <!-- Header -->
        <header class="bg-primary py-5 mb-5">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-lg-12">
                        <h1 class="display-4 text-white mt-5 mb-2">Cek Sisa Cicilan</h1>
                        <p class="lead mb-5 text-white-50">Masukkan ID Pinjam Anda untuk mengecek sisa cicilan.</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Cek Cicilan Section (Initially Hidden) -->
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-8 mx-auto">
                    <h2>Cek Sisa Cicilan</h2>
                    <form action="{{ route('check.cicilan.submit') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="id_pinjam">Masukkan ID Pinjam:</label>
                            <input type="text" id="id_pinjam" name="id_pinjam" class="form-control"
                                placeholder="Masukkan ID Pinjam" required>
                            <!-- Contoh Format ID -->
                            <p class="small">
                                <strong>Catatan:</strong>
                                Format ID Pinjam: <code>4 Angka terakhir NIK + Tanggal Lahir (DDMMYY)</code>.
                                Contoh: <code>0021-120298</code>
                            </p>
                        </div>
                        <button class="btn btn-primary mt-2" id="submitLoanId">Cek Sisa Cicilan</button>
                    </form>
                    @if (session()->has('cicilans'))
                        @php $cicilans = session('cicilans'); @endphp
                        <div class="mt-4 result-box">
                            <div class="table-title">Sisa Pinjaman</div>
                            <div class="table-content table-responsive">
                                <h6 class="mb-3">Nama Peminjam :
                                    <b>{{ $cicilans->first()->nama_konsumen }}</b>
                                </h6>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Pinjaman Ke-</th>
                                            <th>Nominal Pinjaman</th>
                                            <th>Tenor Pinjaman</th>
                                            <th>Cicilan Perbulan</th>
                                            <th>Sisa Tenor</th>
                                            <th>Sisa Pinjaman</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cicilans as $cicilan)
                                            <tr>
                                                <td class="nowrap text-center align-middle">{{ $cicilan->pinjaman_ke }}
                                                </td>
                                                <td class="nowrap text-center align-middle">
                                                    Rp. {{ number_format($cicilan->pokok_pinjaman, 0, ',', '.') }}</td>
                                                <td class="nowrap text-center align-middle">
                                                    {{ $cicilan->jumlah_tenor_seharusnya }}
                                                    {{ $cicilan->divisi == 'Borongan' ? 'Periode' : 'Bulan' }}
                                                </td>
                                                <td class="nowrap text-center align-middle">
                                                    Rp. {{ number_format($cicilan->cicilan_perbulan, 0, ',', '.') }}
                                                </td>
                                                <td class="nowrap text-center align-middle">{{ $cicilan->sisa_tenor }}
                                                    {{ $cicilan->divisi == 'Borongan' ? 'Periode' : 'Bulan' }}
                                                </td>
                                                <td class="nowrap text-center align-middle">
                                                    Rp. {{ number_format($cicilan->sisa_pinjaman, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <span class="text-danger mt-4">Nb : Nominal cicilan diatas belum termasuk denda
                                    keterlambatan
                                    pembayaran.</span>
                            </div>
                        </div>
                    @endisset

                    @if (session()->has('error'))
                        <div class="alert alert-danger mt-4">
                            {{ session('error') }}
                        </div>
                    @endif
            </div>
        </div>
    </div>
</div>
</body>

</html>
