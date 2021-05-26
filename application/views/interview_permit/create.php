
<div class="row justify-content-center">
<form action="<?= site_url('guest/interview-permit/save/' . '?redirect=' . get_url_param('redirect')) ?>" method="POST" enctype="multipart/form-data" id="form-interview-permit">
    <?= _csrf() ?>
	<div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Surat Izin Penelitian</h5>
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
			<h5 class="card-title">Isi Surat Izin Wawancara</h5>
            <div class="form-group">
				<label for="terhormat">Yang Terhormat</label>
				<input type="text" class="form-control" id="terhormat" name="terhormat" maxlength="100"
				required placeholder="Masukkan tujuan surat"><?= set_value('terhormat') ?>
				<?= form_error('terhormat') ?>
			</div>
			<div class="form-group">
				<label for="judul">Judul Proposal Skripsi / Tugas</label>
				<input type="text" class="form-control" id="judul" name="judul" maxlength="100"
				required placeholder="Masukkan judul proposal"><?= set_value('judul') ?>
				<?= form_error('judul') ?>
			</div>
			<h5 class="card-title">List nama yang mewawancarai</h5>
			<div id="student-wrapper">
            <?php if(set_value('students')): ?>
                <?php foreach(set_value('students', []) as $index => $student): ?>
                <div class="row card-student">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_<?= $index ?>">Nama <?= $index + 1 ?></label>
                            <input type="text" class="form-control" id="nama_<?= $index ?>" name="students[<?= $index ?>][nama]" required maxlength="100"
                                    value="<?= set_value("students[<?= $index ?>][nama]", $student['nama']) ?>" placeholder="Nama yang ditugaskan">
                            <?= form_error("students[<?= $index ?>][nama]") ?>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nim_<?= $index ?>">NIM <?= $index + 1 ?></label>
                            <input type="text" class="form-control" id="nim_<?= $index ?>" name="students[<?= $index ?>][nim]" required maxlength="50"
                                    value="<?= set_value("students[<?= $index ?>][nim]", $student['nim']) ?>" placeholder="nim">
                            <?= form_error("students[<?= $index ?>][nim]") ?>
                        </div>
                    </div>
                    <?php if($index>0): ?>
                    <div class="col-md-1">
                        <div class="form-group">
                            <button class="btn btn-sm btn-outline-danger btn-remove-location" type="button">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach ?>
            <?php else: ?>
                <div class="row card-student">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_0">Nama</label>
                            <input type="text" class="form-control" id="nama_0" name="students[0][nama]" required maxlength="100"
                                value="<?= set_value('students[0][nama]') ?>" placeholder="Nama yang ditugaskan">
                            <?= form_error('students[0][nama]') ?>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="nim_0">NIM</label>
                            <input type="text" class="form-control" id="nim_0" name="students[0][nim]" required maxlength="50"
                                value="<?= set_value('students[0][nim]') ?>" placeholder="nim">
                            <?= form_error('students[0][nim]') ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <button class="btn btn-sm btn-info" id="btn-add-student" type="button"> <span class="mdi mdi-plus"></span>TAMBAH NAMA</button>
            </div>
			<div class="form-group">
				<label for="wawancara">Keperluan wawancara</label>
				<input type="text" class="form-control" id="wawancara" name="wawancara" maxlength="100"
				required placeholder="Masukkan keperluan wawancara"><?= set_value('wawancara') ?>
				<?= form_error('wawancara') ?>
			</div>
			<div class="form-group">
				<label for="metode">Metode pengumpulan data</label>
				<input type="text" class="form-control" id="metode" name="metode" maxlength="100"
				required placeholder="Masukkan metode yang anda gunakan untuk pengumpulan data"><?= set_value('metode') ?>
				<?= form_error('metode') ?>
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
<script id="student-template" type="text/x-custom-template">
	<div class="row card-student">
		<div class="col-md-6">
			<div class="form-group">
				<label for="nama_{{index}}">Nama {{no}}</label>
				<input type="text" class="form-control" id="nama_{{index}}" name="students[{{index}}][nama]" required maxlength="100"
					value="<?= set_value('students[{{index}}0][nama]') ?>" placeholder="Nama yang ditugaskan">
				<?= form_error('students[{{index}}][nama]') ?>
			</div>
		</div>
		<div class="col-md-5">
			<div class="form-group">
				<label for="nim_{{index}}">NIM</label>
				<input type="text" class="form-control" id="nim_{{index}}" name="students[{{index}}][nim]" required maxlength="50"
					value="<?= set_value('students[{{index}}][nim]') ?>" placeholder="nim">
				<?= form_error('students[{{index}}][nim]') ?>
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
