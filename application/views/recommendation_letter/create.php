
<div class="row justify-content-center">
<form action="<?= site_url('guest/recommendation-letter/save/' . '?redirect=' . get_url_param('redirect')) ?>" method="POST" enctype="multipart/form-data" id="form-recommendation-letter">
    <?= _csrf() ?>
	<div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Surat Rekomendasi</h5>
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
			<h5 class="card-title">Isi Surat Rekomendasi</h5>
            <div class="form-group">
				<label for="nama_dosen">Nama Yang Memberikan Rekomendasi</label>
				<input type="text" class="form-control" id="nama_dosen" name="nama_dosen" maxlength="100"
				required placeholder="Masukkan nama permberi rekomendasi"><?= set_value('nama_dosen') ?>
				<?= form_error('nama_dosen') ?>
			</div>
			<div class="form-group">
				<label for="jabatan">Jabatan Yang Memberikan Rekomendasi</label>
				<input type="text" class="form-control" id="jabatan" name="jabatan" maxlength="100"
				required placeholder="Masukkan jabatan"><?= set_value('jabatan') ?>
				<?= form_error('jabatan') ?>
			</div>
			<div class="form-group">
				<label for="prodi">Program Studi/Fakultas/Institusi</label>
				<input type="text" class="form-control" id="prodi" name="prodi" maxlength="100"
				required placeholder="Masukkan prodi/fakultas/institusi"><?= set_value('prodi') ?>
				<?= form_error('prodi') ?>
			</div>
			<hr>
			<div class="form-group">
				<label for="nama_mahasiswa">Nama Mahasiswa Yang Direkomendasikan</label>
				<input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa" maxlength="100"
				required placeholder="Masukkan nama mahasiswa"><?= set_value('nama_mahasiswa') ?>
				<?= form_error('nama_mahasiswa') ?>
			</div>
			<div class="form-group">
				<label for="nim">NIM Mahasiswa Yang Direkomendasikan</label>
				<input type="text" class="form-control" id="nim" name="nim" maxlength="100"
				required placeholder="Masukkan nim mahasiswa"><?= set_value('nim') ?>
				<?= form_error('nim') ?>
			</div>
			<div class="form-group">
				<label for="rekomendasi">Rekomendasi Untuk Mengikuti</label>
				<input type="text" class="form-control" id="rekomendasi" name="rekomendasi" maxlength="100"
				required placeholder="Masukkan rekomendasi"><?= set_value('rekomendasi') ?>
				<?= form_error('rekomendasi') ?>
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
