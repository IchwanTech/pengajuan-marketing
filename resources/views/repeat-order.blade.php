<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Top Up Manual Cash Gampang</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/styleRo.css') }}">
</head>

<body>

    <div class="registration-form">
        @if (session('success'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger text-center" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('input.pengajuan.submit') }}" method="POST">
            @csrf

            <div class="form-icon">
                <img src="{{ asset('favicon.ico') }}" alt="">
            </div>

            <div class="mb-3">
                <h5 class="text-center">Form Top Up Manual Cash Gampang</h5>
            </div>

            <!-- Nama -->
            <div class="form-group mb-3">
                <label for="nama_lengkap">Nama</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                    placeholder="Danang Pambudi" required>
                <small class="text-muted">Input nama lengkap</small>
            </div>

            <!-- Nomor Karyawan -->
            <div class="form-group mb-3">
                <label for="nik">Nomor ID Card</label>
                <input type="text" id="nik" name="nik" class="form-control" placeholder="2354/IX/24"
                    required>
                <small class="text-muted">Jika tidak memiliki ID Card, dapat diisi dengan divisi/lokasi kerja</small>
            </div>

            <!-- No HP -->
            <div class="form-group mb-3">
                <label for="no_hp">No HP</label>
                <input type="text" id="no_hp" name="no_hp" class="form-control" placeholder="081234567890"
                    required>
                <small class="text-muted">Input angka tanpa titik.</small>
            </div>

            <!-- Nominal Pinjaman -->
            <div class="form-group mb-3">
                <label for="nominal_pinjaman">Nominal Pinjaman</label>
                <input type="number" id="nominal_pinjaman" name="nominal_pinjaman" class="form-control"
                    placeholder="500000" required>
                <small class="text-muted">Minimal Pinjaman Rp. 500.000, dan input angka tanpa titik.</small>
            </div>

            <!-- Tenor -->
            <div class="form-group mb-3">
                <label for="tenor">Tenor (bulan)</label>
                <input type="number" id="tenor" name="tenor" class="form-control" placeholder="10" required>
                <small class="text-muted">Input angka tenor saja, misal 10.</small>
            </div>

            <!-- Pengajuan Ke Berapa -->
            <div class="form-group mb-3">
                <label for="pinjaman_ke">Pengajuan Ke Berapa</label>
                <input type="number" id="pinjaman_ke" name="pinjaman_ke" class="form-control" placeholder="1" required>
                <small class="text-muted">Input angka pengajuan ke berapa</small>
            </div>

            <!-- Pilih Marketing -->
            <div class="form-group mb-3">
                <label for="marketing">Pilih Marketing</label>
                <select id="marketing" name="marketing" class="form-control" required>
                    <option value="" disabled selected>Pilih Marketing</option>
                    {{-- <option value="Putri">Putri</option> --}}
                    @foreach ($user as $marketing)
                        @php
                            $initials = implode(
                                '',
                                array_map(function ($word) {
                                    return strtoupper($word[0]);
                                }, explode(' ', $marketing->name)),
                            );
                        @endphp
                        <option value="{{ $marketing->id }}">Marketing {{ $initials }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Pilih Marketing sesuai dengan pengajuan sebelumnya</small>
            </div>

            <!-- Alasan Topup -->
            <div class="form-group mb-3">
                <label for="alasan_topup">Alasan Topup</label>
                <textarea name="alasan_topup" id="alasan_topup" class="form-control" cols="20" rows="5"
                    placeholder="Isi alasan topup"></textarea>
                <small class="text-muted">Silahkan isi alasan topup sesuai kebutuhan</small>
            </div>

            <!-- Submit Button -->
            <div class="text-end">
                <button type="submit" id="submit-button" class="btn btn-primary">Submit</button>
                {{-- <a href="https://wa.me/6289616033536" target="_blank" rel="noopener noreferrer"
                    class="btn btn-primary" id="whatsapp-button" style="display: none;">Submit</a> --}}
            </div>

        </form>
        <div class="social-media">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024 <a
                    href="https://www.cashgampang.com/" target="_blank">Cash Gampang</a>. All rights
                reserved.</span>
        </div>
    </div>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const konsumenBaru = document.getElementById('konsumen_baru');
            const repeatOrder = document.getElementById('repeat_order');
            const submitButton = document.getElementById('submit-button');
            const whatsappButton = document.getElementById('whatsapp-button');

            // Event listener untuk Konsumen Baru
            konsumenBaru.addEventListener('change', function() {
                if (konsumenBaru.checked) {
                    submitButton.style.display = 'none'; // Sembunyikan tombol submit
                    whatsappButton.style.display = 'inline-block'; // Tampilkan tombol WhatsApp
                }
            });

            // Event listener untuk Repeat Order
            repeatOrder.addEventListener('change', function() {
                if (repeatOrder.checked) {
                    submitButton.style.display = 'inline-block'; // Tampilkan tombol submit
                    whatsappButton.style.display = 'none'; // Sembunyikan tombol WhatsApp
                }
            });
        });
    </script> --}}
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js">
    </script>
    {{-- <script>
        $(document).ready(function() {
            $('#birth-date').mask('00/00/0000');
            $('#phone-number').mask('0000-0000-0000-0000');
        })
    </script> --}}
</body>

</html>
