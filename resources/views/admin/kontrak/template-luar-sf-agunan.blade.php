<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Pengajuan Marketing</title>
    <style>
        table {
            border-collapse: collapse;
            width: 70%;
            border: 1px solid black;
            margin: auto;
        }

        th {
            border: 1px solid black;
            text-align: center;
        }

        td {
            border: 1px solid black;
            padding: 5px;
            /* Tambahkan padding agar tampilan lebih rapi */
            text-align: left;
            /* Bisa diubah sesuai kebutuhan */
        }

        .no-border {
            border: none;
            border-collapse: collapse;
            border-spacing: 0;
            /* Menghilangkan jarak antar baris */
            width: 70%;
            margin-left: 0%;
        }

        .no-border td {
            border: none;
            padding: 0;
            /* Hilangkan padding agar tidak ada jarak antar row */
            margin: 0;
            line-height: 1.5;
            /* Pastikan teks tidak terlalu tinggi */
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            text-align: left;
            /* border: 1px solid black; */
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: right;
            padding-bottom: 20px;
            /* Memberikan jarak antara footer dan konten */
            background-color: white;
            /* Agar tidak tertimpa konten */
        }

        body {
            margin-left: 6%;
            margin-right: 6%;
            margin-bottom: 70px;
            /* Menambahkan ruang kosong di bagian bawah halaman */
            margin-top: 70px;
            /* border: 1px solid black; */
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('assets/images/logo-2.png') }}" width="150" height="auto" alt="">
    </div>
    <div>
        <h3 style="text-align: center; margin-bottom: 0%; margin-top: 0%">PERJANJIAN PINJAMAN
        </h3>
        <p style="text-align: center; margin-top: 0%; margin-bottom: 3%">No : {{ $suratKontrak->nomor_kontrak }}</p>
        <p style="margin-bottom:2%; text-align:justify;">Perjanjian
            Pinjaman Dengan Agunan ini merupakan fasilitas Grup
            Perusahaan
            dengan nama Cash Lentera Usaha CG termasuk syarat dan ketentuan khusus dan umumnya,
            <strong>perjanjian</strong> dibuat, oleh dan antara :
        </p>
        <div style="margin-left: 0; margin-bottom: 2%;">
            <table class="no-border" style="width: 100%">
                <tr>
                    <td style="width: 20%;">Nama</td>
                    <td style="width: 3%">:</td>
                    <td>{{ Str::title($suratKontrak->nama) }}</td>
                </tr>
                <tr>
                    <td style="width: 20%; vertical-align: top">Alamat</td>
                    <td style="width: 3%; vertical-align: top">:</td>
                    <td style="text-align: justify; vertical-align: top">{{ $suratKontrak->alamat }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%;">Nomor KTP</td>
                    <td style="width: 3%">:</td>
                    <td>{{ Str::title($suratKontrak->no_ktp) }}</td>
                </tr>
            </table>
        </div>
        <p style="margin-top:0pt; margin-bottom:2%; text-align:justify;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selanjutnya dalam
            perjanjian ini disebut&nbsp;<strong>Peminjam</strong></p>
        <div style="margin-left: 0; margin-bottom: 2%;">
            <table class="no-border" style="width: 100%">
                <tr>
                    <td style="width: 20%;">Nama</td>
                    <td style="width: 3%">:</td>
                    <td>Cash Lentera Usaha CG</td>
                </tr>
                <tr>
                    <td style="width: 20%; vertical-align: top">Alamat</td>
                    <td style="width: 3%; vertical-align: top">:</td>
                    <td style="text-align: justify; vertical-align: top">Kawasan Industri Kembang Kuning,
                        Klapanunggal, Cileungsi - Bogor 16820</td>
                </tr>
            </table>
        </div>
        <p style="margin-top:0pt; margin-bottom:2%; text-align:justify;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selanjutnya dalam
            perjanjian ini disebut&nbsp;<strong>Pemberi Pinjaman</strong>
        </p>
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:justify;">
            <strong>Peminjam</strong> dan <strong>Pemberi Pinjaman</strong> telah setuju untuk mengikatkan diri
            dalam Perjanjian ini dengan syarat dan ketentuan khusus (<strong>SKK</strong>), seperti berikut :
        </p>
        <div style="margin-left: 0%;margin-bottom: 2%">
            <ul type="A" style="margin: 0; padding-left: 0%">
                <li>
                    <strong>
                        <u>
                            <h4 style="margin-bottom: 1%; margin-top: 2%">Struktur Pinjaman</h4>
                        </u>
                    </strong>
                    <div style="margin-left: 6%; margin-bottom: 2%;">
                        <table class="no-border">
                            <tr>
                                <td style="width: 35%;">Jumlah</td>
                                <td style="width: 5%">:</td>
                                <td>Rp. {{ number_format($suratKontrak->pokok_pinjaman, 0, ',', '.') }},-</td>
                            </tr>
                            <tr>
                                <td style="width: 35%;">Jangka Waktu</td>
                                <td style="width: 5%">:</td>
                                <td>{{ $suratKontrak->tenor }} bulan</td>
                            </tr>
                            <tr>
                                <td style="width: 45%;">Biaya Administrasi</td>
                                <td style="width: 5%">:</td>
                                <td>Rp. {{ number_format($suratKontrak->biaya_admin, 0, ',', '.') }},-</td>
                            </tr>
                            <tr>
                                <td style="width: 45%;">Bunga Pinjaman</td>
                                <td style="width: 5%">:</td>
                                <td>Rp. {{ number_format($suratKontrak->bunga, 0, ',', '.') }},-</td>
                            </tr>
                            <tr>
                                <td style="width: 45%;">Jumlah Cicilan/bulan</td>
                                <td style="width: 5%">:</td>
                                <td>Rp. {{ number_format($suratKontrak->cicilan, 0, ',', '.') }},-</td>
                            </tr>
                            <tr>
                                <td style="width: 45%;">Biaya Layanan/bulan</td>
                                <td style="width: 5%">:</td>
                                <td>Rp. {{ number_format($suratKontrak->biaya_layanan, 0, ',', '.') }},-</td>
                            </tr>
                        </table>
                    </div>
                    <ol type="1" style="margin:0pt; padding-left:6%; text-align:justify;">
                        <li style="margin-bottom:5;">
                            <strong>Pemberi Pinjaman</strong> memberikan fasilitas Pinjaman sebagaimana disebut diatas
                            kepada<strong> Peminjam </strong>dan<strong> Peminjam</strong> menyanggupi untuk
                            mengembalikan pinjaman
                            beserta semua
                            pembayaran terkait berdasarkan ketentuan-ketentuan yang disepakati dalam Perjanjian ini,
                            termasuk
                            Skema
                            Pembayaran Angsuran, Syarat dan Ketentuan Umum, Lampiran-lampiran, dan/atau
                            perubahan-perubahannya
                            yang
                            merupakan satu kesatuan dan tak terpisahkan dalam Perjanjian ini.
                        </li>
                        <li style="margin-bottom:5;">
                            Pada tanggal penandatanganan Perjanjian
                            ini, <strong>Peminjam</strong> telah menyepakati dan menyetujui Perjanjian Pinjaman dan
                            akan
                            memanfaatkan untuk kebutuhannya.
                        </li>
                        <li style="margin-bottom:5;">
                            Perjanjian ini dinyatakan sebagai bukti penerimaan tunai yang
                            sah
                            sejak tanggal penerimaan tunai dan/atau ditandatanganinya perjanjian ini.
                        </li>
                        <li style="margin-bottom:5;">
                            <strong>Peminjam</strong> berkewajiban untuk membayar Pokok Pinjaman dan bunganya per
                            bulan berdasarkan Skema Pembayaran Angsuran yang terlampir dalam Perjanjian ini.
                        </li>
                    </ol>
                </li>
                <br>
                <br>
                <li>
                    <strong>
                        <u>
                            <h4 style="margin-bottom: 1%; margin-top: 2%">Pengembalian Pinjaman</h4>
                        </u>
                    </strong>
                    <ol type="1" style="margin:0pt; padding-left:6%; text-align:justify;">
                        <li style="margin-bottom:5;">
                            <strong>Peminjam</strong> harus membayarkan jumlah pengembalian yang ditetapkan, dengan
                            sistem transfer setiap bulan sesuai dengan jadwal pengembalian ke Rekening Financial
                            <strong>Cash Lentera Usaha CG , BCA an Riska Ervia (572-555-665-6)</strong> atau ke Rekening
                            <strong>BJB an Wendri (0146-332-7811-00)</strong>. Apabila telah melakukan Pembayaran harap
                            mengirimkan bukti Transaksi ke <strong>Nomor Official CG (+62 822-9914-1811)</strong>.
                        </li>
                        <li style="margin-bottom:5;">
                            Prioritas pembayaran atas Perjanjian ini adalah sebagai berikut :</li>
                        <ol type="a" style="margin-bottom:5;">
                            <li>Biaya-biaya</li>
                            <li>Denda</li>
                            <li>Bunga Pinjaman</li>
                            <li>Pinjaman</li>
                        </ol>
                        <li style="margin-bottom:5;">
                            Pelunasan dipercepat</li>
                        <ol type="a" style="margin:0pt; margin-bottom:5;">
                            <li><strong>Peminjam</strong> dapat mempercepat pelunasan seluruh pinjamannya sesuai dengan
                                kebijakan dari <strong>Pemberi Pinjaman</strong></li>
                            <li>
                                Jika <strong>Peminjam</strong> bermaksud mempercepat pelunasan pinjamannya yang belum
                                lunas, <strong>Peminjam</strong> harus memberitahukan kepada <strong>Pemberi
                                    Pinjaman</strong>
                                sebelum pelunasan paling lambat 3 (tiga) hari sebelum tanggal pembayaran dengan tetap
                                membayar jumlah keseluruhan pinjaman.
                            </li>
                            <li>Pelunasan yang dipercepat atau dibayar lebih awal, tidak
                                dikenakan biaya tambahan</li>
                        </ol>
                        <li>
                            Peminjam dengan ini menyatakan dan menjamin akan membayar Pokok Pinjaman dan Bunganya per
                            bulan berdasarkan Skema Pembayaran Angsuran yang terlampir dalam perjanjian ini.
                        </li>
                    </ol>
                </li>
                <li>
                    <strong>
                        <u>
                            <h4 style="margin-bottom: 1%; margin-top: 2%">Skema Pembayaran Angsuran</h4>
                        </u>
                    </strong>
                    <p style="margin-top: 0%; text-align: justify;">
                        Berikut adalah proyeksi/perkiraan Skema Pembayaran Angsuran, yang menjadi acuan serta petunjuk
                        cara pembayaran dan/atau dengan sistem pemotongan gaji sesuai dengan syarat dan ketentuan yang
                        disetujui kedua belah pihak.
                    </p>
                    <p>Biaya Admin 1% dari Nominal Pokok Pinjaman</p>
                    <p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:115%;"><span
                            style="">&nbsp;</p>
                    <table>
                        <thead>
                            <tr>
                                <th style="background-color: lightgreen; padding: 5">Bulan</th>
                                <th style="background-color: lightgreen; padding: 5">Jatuh Tempo</th>
                                <th style="background-color: lightgreen; padding: 5">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($angsuran as $index => $item)
                                @php
                                    // Jika cicilan pertama, tambahkan biaya layanan dan biaya admin
                                    if ($index === 0) {
                                        $jumlah_cicilan =
                                            $item['cicilan'] +
                                            $suratKontrak->biaya_layanan +
                                            $suratKontrak->biaya_admin;
                                    } else {
                                        // Cicilan selanjutnya hanya ditambah biaya layanan
                                        $jumlah_cicilan = $item['cicilan'] + $suratKontrak->biaya_layanan;
                                    }
                                @endphp
                                <tr>
                                    <td style="text-align: center; width: 15%">{{ $loop->iteration }}</td>
                                    <td style="width: 50%">{{ $item['tanggal'] }}</td>
                                    <td style="text-align: center">Rp.
                                        {{ number_format($jumlah_cicilan, 0, ',', '.') }},-</td>
                                </tr>
                            @endforeach
                            @php
                                $totalCicilan =
                                    collect($angsuran)->sum('cicilan') +
                                    $suratKontrak->biaya_layanan * count($angsuran) +
                                    $suratKontrak->biaya_admin;
                            @endphp
                            <tr>
                                <td colspan="2" style="text-align: center"><b>TOTAL</b></td>
                                <td style="text-align: center">
                                    <b>
                                        Rp. {{ number_format($totalCicilan, 0, ',', '.') }},-
                                    </b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </li>
                <li>
                    <strong>
                        <u>
                            <h4 style="margin-bottom: 1%; margin-top: 2%">Hak dan Kewajiban Para Pihak</h4>
                        </u>
                    </strong>
                    <p style="margin-top: 0%; text-align: justify;">
                        Hak dan Kewajiban Para Pihak dalam Perjanjian ini tunduk pada Syarat dan
                        Ketentuan Khusus (<strong>SKK</strong>) yang terlampir pada Point A di dalam Perjanjian ini.
                        Peminjam dengan ini menegaskan bahwa Peminjam telah dengan sebagaimana mestinya memahami dan
                        membaca&nbsp;<strong>SKK</strong>.
                    </p>
                </li>
                <li>
                    <strong>
                        <u>
                            <h4 style="margin-bottom: 1%; margin-top: 2%">Lain-lain</h4>
                        </u>
                    </strong>
                    <ol type="1" style="text-align: justify;">
                        <li style="margin-bottom:1%"><b>Peminjam</b> mengakui dan sepakat bahwa rincian dari <b>Pemberi
                                Pinjaman</b> adalah rincian yang benar.</li>
                        <li style="margin-bottom:1%">
                            Prioritas pembayaran atas Perjanjian perihal &ldquo;Denda&rdquo; dikenakan kepada
                            <strong>Peminjam</strong> yang bayar lewat dari tanggal <strong>Jatuh Tempo</strong> akan
                            dikenakan denda Bunga
                            keterlambatan sebesar 0.25% per hari dari total tunggakan.
                        </li>
                        <li style="margin-bottom:1%">
                            Bahwa <strong>Peminjam</strong> bersedia membayar biaya penagihan apabila melakukan
                            wanprestasi terhadap isi Perjanjian ini.
                        </li>
                        <li style="margin-bottom:1%">
                            Bahwa apabila <strong>Peminjam</strong> lalai dalam melakukan pembayaran melebihi batas
                            waktu tunggakan 100 hari (seratus) maka <strong>Peminjam</strong> akan dibebankan biaya
                            penagihan sebesar 30%
                            dari total pinjaman yang tertunggak.
                        </li>
                        <li style="margin-bottom:1%">
                            <strong>Pemberi Pinjaman</strong> dapat melimpahkan hak dan kewajibannya berdasarkan
                            perjanjian ini ke pihak ketiga dengan atau tanpa persetujuan <strong>Peminjam</strong>.
                        </li>
                        <li style="margin-bottom:1%">
                            Dalam hal <strong>Pinjaman</strong> tidak dapat memenuhi kewajibannya untuk membayar
                            Pinjaman dan Bunganya sebagaimana diatur pada Perjanjian ini, maka <strong>Peminjam</strong>
                            setuju dan sepakat
                            untuk menggunakan harta pribadinya untuk dicairkan/diserahkan dan dipergunakan sebagai
                            pembayaran sisa Pinjaman
                            dan Bunganya tersebut.
                        </li>
                        <li style="margin-bottom:1%">
                            Sehubungan dengan hal tersebut diatas, <strong>Peminjam</strong> dengan ini memberikan hak
                            dan/atau kuasanya kepada pihak Pemberi Pinjaman dengan hak substitusi dan/atau hak retensi
                            untuk melikuidasi /
                            menjual, menebus aset lain yang merupakan milik <strong>Peminjam</strong> untuk melunasi
                            Pinjamannya.
                        </li>
                        <li style="margin-bottom:1%">
                            Setiap sengketa, kontroversial, atau klaim yang timbul dari atau sehubungan dengan
                            Perjanjian ini baik terkait pelaksanaan kontrak, gugatan atau lainnya termasuk pertanyaan
                            tentang keberadaan,
                            keabsahan atau pengakhiran, akan diselesaikan secara kekeluargaan oleh Para Pihak dalam
                            waktu 30 (tiga
                            puluh) hari setelah salah satu Pihak memberitahukan ke Pihak lain tentang timbulnya Sengketa
                            tersebut. Sengketa
                            yang tidak dapat diselesaikan melalui musyawarah untuk mufakat, akan diselesaikan di kantor
                            Pengadilan Negeri
                            setempat tanpa mengurangi hak Pemberi Pinjaman untuk mengajukan tuntutan hukum terhadap
                            Peminjam dihadapan
                            pengadilan lain di wilayah Republik Indonesia.
                        </li>
                        <li style="margin-bottom:1%">
                            Perjanjian Pinjaman ini tidak dapat berakhir karena Peminjam meninggal dunia, dan akan turun
                            temurun dan harus dipenuhi oleh alih waris dari Peminjam.
                        </li>
                        <li style="margin-bottom:1%">
                            Peminjam menyatakan bahwa Peminjam telah membaca dan memahami isi Perjanjian ini dan
                            menyepakati semua tanggung jawab, hak dan kewajibannya.
                        </li>
                    </ol>
                </li>
            </ul>
        </div>

        <p style="margin-top:2%; margin-bottom:0pt; text-align:justify; line-height:115%;">
            <strong>Peminjam</strong>&nbsp;menegaskan bahwa telah menerima dan setuju terhadap ketentuan
            Perjanjian ini, termasuk Skema Pembayaran Angsuran, Syarat dan Ketentuan Umum, Lampiran-lampiran dan
            perubahan-perubahannya. Perjanjian ini dibuat dalam 2 rangkap dan masing-masing bermaterai cukup serta
            memiliki kekuatan hukum yang sama dan masing-masing pihak akan menerima 1 rangkap.
        </p>
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:115%;">&nbsp;</p>
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:115%;">
            <strong>DEMIKIAN</strong>&nbsp;para pihak melalui perwakilan resminya telah menandatangani
            Perjanjian ini di tempat dan pada tanggal tersebut dibawah ini :
        </p>
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:115%;">&nbsp;</p>
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:115%;">&nbsp;</p>
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:115%;">Tempat
            Penandatanganan&nbsp;&nbsp;:<span
                style="width:4.3pt; font-family:'MS Gothic'; display:inline-block;">&nbsp;____________________________
        </p>
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:115%;">&nbsp;</p>
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:115%;">&nbsp;</p>
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:115%;">Tanggal Penandatanganan
            :&nbsp;____________________________</p>
        <p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; line-height:115%;">&nbsp;</p>

        <table class="no-border" style="width:100%; border-collapse: collapse;">
            <tr>
                <td colspan="3" style="text-align: center; padding: 15px;"><b>Peminjam</b></td>
                <td colspan="3" style="text-align: center; padding: 15px;"><b>Team Cash Lentera Usaha CG</b></td>
            </tr>
            <tr>
                <td style="padding-top: 50px; white-space: nowrap; width: 15%; text-align: left;">Tanda Tangan</td>
                <td style="padding-top: 50px; width: 3%; text-align: center;">:</td>
                <td style="padding-top: 50px; width: 30%; text-align: left;">____________________</td>
                <td style="padding-top: 50px; white-space: nowrap; width: 15%; text-align: left;">Tanda Tangan</td>
                <td style="padding-top: 50px; width: 3%; text-align: center;">:</td>
                <td style="padding-top: 50px; width: 30%; text-align: left;">____________________</td>
            </tr>
            <tr>
                <td style="padding-top: 25px; white-space: nowrap; text-align: left;">Nama</td>
                <td style="padding-top: 25px; width: 3%; text-align: center;">:</td>
                <td style="padding-top: 25px; text-align: left;">____________________</td>
                <td style="padding-top: 25px; white-space: nowrap; text-align: left;">Nama</td>
                <td style="padding-top: 25px; width: 3%; text-align: center;">:</td>
                <td style="padding-top: 25px; text-align: left;">____________________</td>
            </tr>
        </table>
    </div>
    <div class="footer">
        <p style="margin-top:0pt; margin-bottom:0pt;">&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Paraf ____________________</strong>
        </p>
    </div>

</body>

</html>
