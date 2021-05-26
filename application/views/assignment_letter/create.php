
<div class="row justify-content-center">
<form action="<?= site_url('guest/assignment-letter/save/' . '?redirect=' . get_url_param('redirect')) ?>" method="POST" enctype="multipart/form-data" id="form-assignment-letter">
    <?= _csrf() ?>
	<div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Surat Permohonan Surat Tugas</h5>
			<div class="form-group">
				<label for="email">Email Anda</label> <span class="small text-fade">(surat akan dikirimkan ke email anda)</span>
				<input type="email" class="form-control" id="email" name="email" required maxlength="100" size="100"
						value="<?= set_value('email') ?>" placeholder="Email">
				<?= form_error('email') ?>
			</div>
        </div>
    </div>
	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Isi Surat Permohonan Surat Tugas</h5>
            <div class="form-group">
				<label for="tujuan">Ketua Program Studi Pendidikan Fisika</label>
				<select class="form-control select2" name="tujuan" id="tujuan" style="width: 100%"
                required data-placeholder="Pilih tujuan">
					<option></option>
                    <option value="Kepala Bagian TU Fakultas Ilmu Tarbiyah dan Keguruan"<?= set_select('tujuan', "Kepala Bagian TU Fakultas Ilmu Tarbiyah dan Keguruan", get_url_param('tujuan') == "Kepala Bagian TU Fakultas Ilmu Tarbiyah dan Keguruan") ?>>
                        Kabag TU FITK
                    </option>
                    <option value="Dekan Fakultas Ilmu Tarbiyah dan Keguruan"<?= set_select('tujuan', "Dekan Fakultas Ilmu Tarbiyah dan Keguruan", get_url_param('tujuan') == "Dekan Fakultas Ilmu Tarbiyah dan Keguruan") ?>>
                        Dekan FITK
                    </option>
				</select>
				<?= form_error('tujuan') ?>
			</div>
			<div class="form-group">
				<label for="judul">Judul Kegiatan</label>
				<input type="text" class="form-control" id="judul" name="judul" maxlength="100" required
						placeholder="Masukkan judul kegiatan" value="<?= set_value('judul') ?>">
				<?= form_error('judul') ?>
			</div>
            <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai Kegiatan</label>
                <input type="text" class="form-control datepicker" name="tanggal_mulai" id="tanggal_mulai"
                required value="<?= set_value('tanggal_mulai') ?>" placeholder="Pilih tanggal mulai">
				<?= form_error('tanggal_mulai') ?>
            </div>
            
            <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai Kegiatan</label>
                <input type="text" class="form-control datepicker" name="tanggal_selesai" id="tanggal_selesai"
                required value="<?= set_value('tanggal_selesai') ?>" placeholder="Pilih tanggal selesai">
				<?= form_error('tanggal_selesai') ?>
            </div>
            <div class="form-group">
				<label for="tempat">Tempat Kegiatan</label>
				<input type="text" class="form-control" id="tempat" name="tempat" maxlength="100" required
						placeholder="Masukkan tempat kegiatan" value="<?= set_value('tempat') ?>">
				<?= form_error('tempat') ?>
			</div>
            <div class="form-group">
				<label for="penyelenggara">Penyelenggara Kegiatan</label>
				<input type="text" class="form-control" id="penyelenggara" name="penyelenggara" maxlength="100" required
						placeholder="Masukkan penyelenggara kegiatan" value="<?= set_value('penyelenggara') ?>">
				<?= form_error('penyelenggara') ?>
			</div>
			<div id="student-wrapper">
            <?php if(set_value('students')): ?>
                <?php foreach(set_value('students', []) as $index => $student): ?>
                <div class="row card-student">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nama_<?= $index ?>">Nama <?= $index + 1 ?></label>
                            <input type="text" class="form-control" id="nama_<?= $index ?>" name="students[<?= $index ?>][nama]" maxlength="100"
                                    value="<?= set_value("students[<?= $index ?>][nama]", $student['nama']) ?>" placeholder="Nama yang ditugaskan">
                            <?= form_error("students[<?= $index ?>][nama]") ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nip_<?= $index ?>">NIP <?= $index + 1 ?></label>
                            <input type="text" class="form-control" id="nip_<?= $index ?>" name="students[<?= $index ?>][nip]" maxlength="50"
                                    value="<?= set_value("students[<?= $index ?>][nip]", $student['nip']) ?>" placeholder="NIP">
                            <?= form_error("students[<?= $index ?>][nip]") ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jabatan_<?= $index ?>">Jabatan <?= $index + 1 ?></label>
                            <input type="text" class="form-control" id="jabatan_<?= $index ?>" name="students[<?= $index ?>][jabatan]" maxlength="50"
                                    value="<?= set_value("students[<?= $index ?>][jabatan]", $student['jabatan']) ?>" placeholder="Jabatan">
                            <?= form_error("students[<?= $index ?>][jabatan]") ?>
                        </div>
                    </div>
                    <?php if($index>0): ?>
                    <div class="col-md-1">
                        <div class="form-group">
                            <button class="btn btn-sm btn-outline-danger btn-remove-student" type="button">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach ?>
            <?php else: ?>
                <div class="row card-student">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nama_0">Nama</label>
                            <input type="text" class="form-control" id="nama_0" name="students[0][nama]" maxlength="100"
                                value="<?= set_value('students[0][nama]') ?>" placeholder="Nama yang ditugaskan">
                            <?= form_error('students[0][nama]') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nip_0">NIP</label>
                            <input type="text" class="form-control" id="nip_0" name="students[0][nip]" maxlength="50"
                                value="<?= set_value('students[0][nip]') ?>" placeholder="Nip">
                            <?= form_error('students[0][nip]') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jabatan_0">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan_0" name="students[0][jabatan]" maxlength="50"
                                value="<?= set_value('students[0][jabatan]') ?>" placeholder="Jabatan">
                            <?= form_error('students[0][jabatan]') ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <button class="btn btn-sm btn-info" id="btn-add-student" type="button">ADD</button>
            </div>
			<div class="form-group">
				<label for="kaprodi">Ketua Program Studi Pendidikan Fisika</label>
				<select class="form-control select2" name="kaprodi" id="kaprodi" style="width: 100%"
                required data-placeholder="Pilih Kaprodi">
					<option></option>
					<?php foreach ($kaprodis as $kaprodi): ?>
						<option value="<?= $kaprodi['id'] ?>"<?= set_select('kaprodi', $kaprodi['id'], get_url_param('kaprodi') == $kaprodi['id']) ?>>
							<?= $kaprodi['name'] ?>
						</option>
					<?php endforeach; ?>
				</select>
				<?= form_error('kaprodi') ?>
			</div>
		</div>
	</div>

	<div class="card mb-3">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">
				Submit
			</button>
        </div>
    </div>
</form>
</div>
<script id="student-template" type="text/x-custom-template">
	<div class="row card-student">
		<div class="col-md-5">
			<div class="form-group">
				<label for="nama_{{index}}">Nama {{no}}</label>
				<input type="text" class="form-control" id="nama_{{index}}" name="students[{{index}}][nama]" required maxlength="100"
					value="<?= set_value('students[{{index}}0][nama]') ?>" placeholder="Nama yang ditugaskan">
				<?= form_error('students[{{index}}][nama]') ?>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="nip_{{index}}">NIP</label>
				<input type="text" class="form-control" id="nip_{{index}}" name="students[{{index}}][nip]" required maxlength="50"
					value="<?= set_value('students[{{index}}][nip]') ?>" placeholder="NIP">
				<?= form_error('students[{{index}}][nip]') ?>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="jabatan_{{index}}">Jabatan</label>
				<input type="text" class="form-control" id="jabatan_{{index}}" name="students[{{index}}][jabatan]" required maxlength="50"
					value="<?= set_value('students[{{index}}][jabatan]') ?>" placeholder="Jabatan">
				<?= form_error('students[{{index}}][jabatan]') ?>
			</div>
		</div>
        <div class="col-md-1">
            <div class="form-group">
                <button class="btn btn-sm btn-outline-danger btn-remove-student" type="button">
                    <i class="mdi mdi-trash-can-outline"></i>
                </button>
            </div>
        </div>
    </div>
</script>
