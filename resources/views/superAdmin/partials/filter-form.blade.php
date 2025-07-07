{{-- resources/views/superAdmin/partials/filter-form.blade.php --}}
<form method="GET" action="{{ route('superAdmin.data.pengajuan.luar') }}" class="mb-4">
    <div class="row">
        <!-- Filter Waktu -->
        <div class="col-md-3">
            <label for="filter_time" class="form-label">Pilih Waktu</label>
            <select name="filter_time" id="filter_time" class="form-control">
                <option value="">Semua Waktu</option>
                <option value="today" {{ request('filter_time') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="this_month" {{ request('filter_time') == 'this_month' ? 'selected' : '' }}>Bulan Ini
                </option>
                <option value="this_year" {{ request('filter_time') == 'this_year' ? 'selected' : '' }}>Tahun Ini
                </option>
            </select>
        </div>

        <!-- Filter Bulan -->
        <div class="col-md-3">
            <label for="month" class="form-label">Pilih Bulan</label>
            <select name="month" id="month" class="form-control">
                <option value="">Semua Bulan</option>
                @php
                    $months = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ];
                @endphp
                @foreach ($months as $num => $name)
                    <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Tahun -->
        <div class="col-md-3">
            <label for="year" class="form-label">Pilih Tahun</label>
            <select name="year" id="year" class="form-control">
                <option value="">Semua Tahun</option>
                @foreach ($availableYears as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tombol Submit -->
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
        </div>
    </div>
</form>
