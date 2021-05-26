
<div class="row justify-content-center">
<form action="<?= site_url('guest/application-letter/save/' . '?redirect=' . get_url_param('redirect')) ?>" method="POST" enctype="multipart/form-data" id="form-interview-permit">
    <?= _csrf() ?>
	<div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Surat Permohonan Habis Teori</h5>
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
			<h5 class="card-title">Isi Surat Permohonan Habis Teori</h5>
            <div class="form-group">
				<label for="nama">Nama</label>
				<input type="text" class="form-control" id="nama" name="nama" maxlength="100"
				required placeholder="Masukkan nama anda"><?= set_value('nama') ?>
				<?= form_error('nama') ?>
			</div>
			<div class="form-group">
				<label for="nim">NIM</label>
				<input type="text" class="form-control" id="nim" name="nim" maxlength="100"
				required placeholder="Masukkan nim anda"><?= set_value('nim') ?>
				<?= form_error('nim') ?>
			</div>
			<div class="form-group">
				<label for="semester">Semester</label>
				<input type="text" class="form-control" id="semester" name="semester" maxlength="100"
				required placeholder="Masukkan semester kuliah anda"><?= set_value('semester') ?>
				<?= form_error('semester') ?>
			</div>
			<div class="form-group">
				<label for="alamat">Alamat</label>
				<input type="text" class="form-control" id="alamat" name="alamat" maxlength="500"
				required placeholder="Masukkan alamat anda"><?= set_value('alamat') ?>
				<?= form_error('alamat') ?>
			</div>
			<div class="form-group">
				<label for="no_telepon">No. Telepon/HP</label>
				<input type="text" class="form-control" id="no_telepon" name="no_telepon" maxlength="100"
				required placeholder="Masukkan no telepon anda"><?= set_value('no_telepon') ?>
				<?= form_error('no_telepon') ?>
			</div>
			<div class="form-group">
				<label for="kaprodi">Ketua program studi pendidikan fisika</label>
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
