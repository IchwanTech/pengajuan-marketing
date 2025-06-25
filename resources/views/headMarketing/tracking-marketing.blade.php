@extends('layouts.parent-layout')

@section('page-title', 'Tracking Marketing')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center border-bottom mb-3">
                        <h6 class="card-title">Data Pengajuan Marketing</h6>
                    </div>

                    <!-- Form Filter -->
                    <form method="GET" action="{{ route('headMarketing.tracking') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="month" class="form-label">Pilih Bulan</label>
                                <select name="month" id="month" class="form-control">
                                    <option value="">Semua Bulan</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        @php
                                            $namaBulan = \Carbon\Carbon::createFromFormat('m', $m)->translatedFormat(
                                                'F',
                                            );
                                        @endphp
                                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                            {{ $namaBulan }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="year" class="form-label">Pilih Tahun</label>
                                <select name="year" id="year" class="form-control">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}"
                                            {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mt-4">
                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-2">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Marketing</th>
                                    <th class="text-center">Total Pengajuan</th>
                                    <th class="text-center">Approve</th>
                                    <th class="text-center">Reject</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($marketing->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data untuk bulan dan tahun yang
                                            dipilih</td>
                                    </tr>
                                @else
                                    @foreach ($marketing as $data)
                                        <tr>
                                            <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                            <td>{{ Str::title($data->marketing_name) }}</td>
                                            <td class="align-middle text-center">
                                                @if ($data->total_nasabah > 0)
                                                    {{ $data->total_nasabah }} Nasabah
                                                @else
                                                    Tidak Ada Pengajuan Nasabah
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">{{ $data->total_approved_hm }}</td>
                                            <td class="align-middle text-center">{{ $data->total_rejected_hm }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td class="text-center" colspan="2">Total</td>
                                    <td class="text-center">{{ $marketing->sum('total_nasabah') }} Nasabah</td>
                                    <td class="text-center">{{ $marketing->sum('total_approved_hm') }}</td>
                                    <td class="text-center">{{ $marketing->sum('total_rejected_hm') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
