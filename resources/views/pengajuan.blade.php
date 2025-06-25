    <div class="form-step">
        <h5>Alamat Nasabah</h5>
        <div class="form-group">
            <label for="alamat_ktp" class="form-label">Alamat KTP</label>
            <input type="text" id="alamat_ktp" name="alamat_ktp" class="form-control" required>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="kelurahan" class="form-label">Kelurahan</label>
                <input type="text" id="kelurahan" name="kelurahan" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="rt_rw" class="form-label">RT/RW</label>
                <input type="text" id="rt_rw" name="rt_rw" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="kecamatan" class="form-label">Kecamatan</label>
            <input type="text" id="kecamatan" name="kecamatan" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="kota" class="form-label">Kabupaten/Kota</label>
            <input type="text" id="kota" name="kota" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="provinsi" class="form-label">Provinsi</label>
            <input type="text" id="provinsi" name="provinsi" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="domisili" class="form-label">Alamat Domisili</label>
            <select id="domisili" name="domisili" class="form-select" required>
                <option value="">Pilih</option>
                <option value="sesuai ktp">Sesuai KTP</option>
                <option value="tidak sesuai ktp">Tidak Sesuai KTP</option>
            </select>
        </div>
        <div class="form-group" id="alamat-lengkap-group" style="display: none;">
            <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
            <textarea name="alamat_lengkap" id="alamat_lengkap" cols="30" rows="10" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="status_rumah" class="form-label">Status Rumah</label>
            <select id="status_rumah" name="status_rumah" class="form-select" required>
                <option value="">Pilih</option>
                <option value="sewa">Sewa</option>
                <option value="kontrak">Kontrak</option>
                <option value="milik orang tua">Milik Orang Tua</option>
                <option value="pribadi">Milik Pribadi</option>
            </select>
        </div>
        <div class="text-end">
            <button type="button" class="btn btn-secondary prev-btn">Previous</button>
            <button type="submit" class="btn btn-primary next-btn">Next</button>
        </div>
    </div>

    <div class="form-step">
        <h5>Keluarga Nasabah</h5>
        <div class="form-group">
            <label for="hubungan" class="form-label">Hubungan Keluarga</label>
            <select id="hubungan" name="hubungan" class="form-select" required>
                <option value="">Pilih</option>
                <option value="orang tua">Orang Tua</option>
                <option value="suami">Suami</option>
                <option value="istri">Istri</option>
                <option value="anak">Anak</option>
            </select>
        </div>
        <div class="form-group">
            <label for="nama_keluarga" class="form-label">Nama Keluarga</label>
            <input type="text" id="nama_keluarga" name="nama_keluarga" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="bekerja" class="form-label">Apakah Bekerja ?</label>
            <select id="bekerja" name="bekerja" class="form-select" required>
                <option value="">Pilih</option>
                <option value="ya">Bekerja</option>
                <option value="tidak">Tidak Bekerja</option>
            </select>
        </div>
        <div id="pekerjaan-keluarga-group" style="display: none;">
            <div class="form-group">
                <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                <input type="text" id="nama_perusahaan" name="nama_perusahaan" class="form-control">
            </div>
            <div class="form-group">
                <label for="jabatan" class="form-label">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" class="form-control">
            </div>
            <div class="form-group">
                <label for="penghasilan" class="form-label">Penghasilan</label>
                <input type="number" id="penghasilan" name="penghasilan" class="form-control">
            </div>
            <div class="form-group">
                <label for="alamat_kerja" class="form-label">Alamat Kerja</label>
                <input type="text" id="alamat_kerja" name="alamat_kerja" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="no_hp_keluarga" class="form-label">No HP/WA</label>
            <input type="text" id="no_hp_keluarga" name="no_hp_keluarga" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="kerabat_kerja" class="form-label">Memiliki Kerabat yg Kerja di Perusahaan
                yang sama ?</label>
            <select id="kerabat_kerja" name="kerabat_kerja" class="form-select" required>
                <option value="">Pilih</option>
                <option value="ya">Ya</option>
                <option value="tidak">Tidak</option>
            </select>
        </div>
        <div id="kerabat-kerja-group" style="display: none;">
            <div class="form-group">
                <label for="nama_kerabat" class="form-label">Nama Kerabat</label>
                <input type="text" id="nama_kerabat" name="nama_kerabat" class="form-control">
            </div>
            <div class="form-group">
                <label for="alamat_kerabat" class="form-label">Alamat Kerabat</label>
                <textarea name="alamat_kerabat" id="alamat_kerabat" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="no_hp_kerabat" class="form-label">No HP/WA</label>
                <input type="text" id="no_hp_kerabat" name="no_hp_kerabat" class="form-control">
            </div>
            <div class="form-group">
                <label for="status_hubungan_kerabat" class="form-label">Status Hubungan</label>
                <input type="text" id="status_hubungan_kerabat" name="status_hubungan_kerabat"
                    class="form-control">
            </div>
            <div class="form-group">
                <label for="nama_perusahaan_kerabat" class="form-label">Perusahaan</label>
                <input type="text" id="nama_perusahaan_kerabat" name="nama_perusahaan_kerabat"
                    class="form-control">
            </div>
        </div>

        <div class="text-end">
            <button type="button" class="btn btn-secondary prev-btn">Previous</button>
            <button type="submit" class="btn btn-primary next-btn">Next</button>
        </div>
    </div>
