
<div class="row justify-content-center">
<form action="<?= site_url('guest/course-elimination/save/' . '?redirect=' . get_url_param('redirect')) ?>" method="POST" enctype="multipart/form-data" id="form-course-elimination">
    <?= _csrf() ?>
	<div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Surat Penghapusan Mata Kuliah</h5>
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
			<h5 class="card-title">Isi Surat Pengajuan Penghapusan Mata Kuliah</h5>
            <div class="form-group">
				<label for="nama">Nama</label>
				<input type="text" class="form-control" id="nama" name="nama" maxlength="100" required
						placeholder="Masukkan nama anda" value="<?= set_value('nama') ?>">
				<?= form_error('nama') ?>
			</div>
            <div class="form-group">
				<label for="nim">Nim</label>
				<input type="text" class="form-control" id="nim" name="nim" maxlength="100" required
						placeholder="Masukkan nim anda" value="<?= set_value('nim') ?>">
				<?= form_error('nim') ?>
			</div>
			<div class="form-group">
				<label for="sks">Total SKS</label>
				<input type="number" class="form-control" id="sks" name="sks" required
						placeholder="Masukkan total sks terakhir" value="<?= set_value('sks') ?>">
				<?= form_error('sks') ?>
			</div>
            <div class="form-group">
				<label for="sks_pilihan">Total SKS Pilihan</label>
				<input type="number" class="form-control" id="sks_pilihan" name="sks_pilihan" required
						placeholder="Masukkan total sks pilihan" value="<?= set_value('sks_pilihan') ?>">
				<?= form_error('sks_pilihan') ?>
			</div>
            <h5 class="card-title">List Mata Kuliah Yang Akan Dihapus</h5>
			<div id="course-wrapper">
            <?php if(set_value('courses')): ?>
                <?php foreach(set_value('courses', []) as $index => $course): ?>
                <div class="row card-course">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nama_<?= $index ?>">Nama Mata Kuliah <?= $index + 1 ?></label>
                            <input type="text" class="form-control" id="nama_<?= $index ?>" name="courses[<?= $index ?>][nama]" required maxlength="100"
                                    value="<?= set_value("courses[<?= $index ?>][nama]", $course['nama']) ?>" placeholder="Nama mata kuliah">
                            <?= form_error("courses[<?= $index ?>][nama]") ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sks_<?= $index ?>">SKS <?= $index + 1 ?></label>
                            <input type="number" class="form-control" id="sks_<?= $index ?>" name="courses[<?= $index ?>][sks]" required 
                                    value="<?= set_value("courses[<?= $index ?>][sks]", $course['sks']) ?>" placeholder="Beban SKS MatKul Tersebut">
                            <?= form_error("courses[<?= $index ?>][sks]") ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nilai_<?= $index ?>">Nilai <?= $index + 1 ?></label>
                            <input type="text" class="form-control" id="nilai_<?= $index ?>" name="courses[<?= $index ?>][nilai]" required maxlength="50"
                                    value="<?= set_value("courses[<?= $index ?>][nilai]", $course['nilai']) ?>" placeholder="Nilai Matkul Tersebut">
                            <?= form_error("courses[<?= $index ?>][nilai]") ?>
                        </div>
                    </div>
                    <?php if($index>0): ?>
                    <div class="col-md-1">
                        <div class="form-group">
                            <button class="btn btn-sm btn-outline-danger btn-remove-course" type="button">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach ?>
            <?php else: ?>
                <div class="row card-course">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nama_0">Nama Mata Kuliah</label>
                            <input type="text" class="form-control" id="nama_0" name="courses[0][nama]" required maxlength="100"
                                value="<?= set_value('courses[0][nama]') ?>" placeholder="Nama mata kuliah">
                            <?= form_error('courses[0][nama]') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sks_0">SKS</label>
                            <input type="number" class="form-control" id="sks_0" name="courses[0][sks]" required
                                value="<?= set_value('courses[0][sks]') ?>" placeholder="Beban SKS MatKul Tersebut">
                            <?= form_error('courses[0][sks]') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nilai_0">Nilai</label>
                            <input type="text" class="form-control" id="nilai_0" name="courses[0][nilai]" required maxlength="50"
                                value="<?= set_value('courses[0][nilai]') ?>" placeholder="Nilai Matkul Tersebut">
                            <?= form_error('courses[0][nilai]') ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <button class="btn btn-sm btn-info" id="btn-add-course" type="button">Tambah Mata Kuliah</button>
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
            <div class="form-group">
				<label for="pembimbing">Dosen Pembimbing Akademik</label>
				<select class="form-control select2" name="pembimbing" id="pembimbing" style="width: 100%"
				required data-placeholder="Pilih Pembimbing">
					<option></option>
					<?php foreach ($pembimbings as $pembimbing): ?>
						<option value="<?= $pembimbing['id'] ?>"<?= set_select('pembimbing', $pembimbing['id'], get_url_param('pembimbing') == $pembimbing['id']) ?>>
							<?= $pembimbing['name'] ?>
						</option>
					<?php endforeach; ?>
				</select>
				<?= form_error('pembimbing') ?>
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
<script id="course-template" type="text/x-custom-template">
	<div class="row card-course">
		<div class="col-md-5">
			<div class="form-group">
				<label for="nama_{{index}}">Nama {{no}}</label>
				<input type="text" class="form-control" id="nama_{{index}}" name="courses[{{index}}][nama]" required maxlength="100"
					value="<?= set_value('courses[{{index}}0][nama]') ?>" placeholder="Nama mata kuliah">
				<?= form_error('courses[{{index}}][nama]') ?>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="sks_{{index}}">SKS</label>
				<input type="number" class="form-control" id="sks_{{index}}" name="courses[{{index}}][sks]" required
					value="<?= set_value('courses[{{index}}][sks]') ?>" placeholder="Beban SKS MatKul Tersebut">
				<?= form_error('courses[{{index}}][sks]') ?>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="nilai_{{index}}">Nilai</label>
				<input type="text" class="form-control" id="nilai_{{index}}" name="courses[{{index}}][nilai]" required maxlength="50"
					value="<?= set_value('courses[{{index}}][nilai]') ?>" placeholder="Nilai MatKul Tersebut">
				<?= form_error('courses[{{index}}][nilai]') ?>
			</div>
		</div>
        <div class="col-md-1">
            <div class="form-group">
                <button class="btn btn-sm btn-outline-danger btn-remove-course" type="button">
                    <i class="mdi mdi-trash-can-outline"></i>
                </button>
            </div>
        </div>
    </div>
</script>
