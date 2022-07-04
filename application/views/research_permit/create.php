
<div class="row justify-content-center">
<form action="<?= site_url('guest/research-permit/save/' . '?redirect=' . get_url_param('redirect')) ?>" method="POST" enctype="multipart/form-data" id="form-research-permit">
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
			<h5 class="card-title">Isi Surat</h5>
			<div class="form-group">
				<label for="terhormat">Yang Terhormat</label>
				<input type="text" class="form-control" id="terhormat" name="terhormat" maxlength="100"
						required placeholder="Masukkan tujuan surat"><?= set_value('terhormat') ?>
				<?= form_error('terhormat') ?>
			</div>
			<div class="form-group">
				<label for="judul">Judul Skripsi/Penelitian</label>
				<input type="text" class="form-control" id="judul" name="judul" maxlength="100"
						required placeholder="Masukkan judul anda"><?= set_value('judul') ?>
				<?= form_error('judul') ?>
			</div>
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
				<label for="pengambilan_data">Untuk pengambilan data</label>
				<input type="text" class="form-control" id="pengambilan_data" name="pengambilan_data" maxlength="100"
						required placeholder="Masukkan data yang akan diambil"><?= set_value('pengambilan_data') ?>
				<?= form_error('pengambilan_data') ?>
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
            <button type="submit" class="btn btn-success">
				Submit
			</button>
        </div>
    </div>
</form>
</div>
