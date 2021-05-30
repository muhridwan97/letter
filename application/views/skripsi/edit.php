<form action="<?= site_url('skripsi/update/' . $skripsi['id']) ?>" method="POST" enctype="multipart/form-data" id="form-skripsi">
    <?= _csrf() ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Edit Skripsi</h5>
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_ALL_ACCESS)!=true && !AuthorizationModel::isAuthorized(PERMISSION_SKRIPSI_VALIDATE)): ?>
                <input type="hidden" name="student" id="student" value="<?= $skripsi['id_student'] ?>">
            <?php else: ?>
                <div class="form-group">
                    <label for="student">Mahasiswa</label>
                    <select class="form-control select2" name="student" id="student" required data-placeholder="Select student">
                        <option value="">-- Select student --</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student['id'] ?>"<?= set_select('student', $student['id'], $student['id'] == $skripsi['id_student']) ?>>
                                <?= $student['no_student'] ?> - <?= $student['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?= form_error('student') ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="pembimbing">Pembimbing</label>
                <input type="hidden" id="id_pembimbing" name="id_pembimbing" value="<?= set_value('id_pembimbing', $skripsi['id_lecturer']) ?>">
                <input type="text" class="form-control" id="pembimbing" name="pembimbing" readonly maxlength="50"
                        value="<?= set_value('pembimbing', $skripsi['nama_pembimbing']) ?>" placeholder="Nama pembimbing">
                <?= form_error('pembimbing') ?>
            </div>
            <div class="form-group">
                <label for="judul">Judul</label>
                <textarea class="form-control" id="judul" name="judul" maxlength="500" required
                          placeholder="Enter skripsi judul"><?= set_value('judul', $skripsi['judul']) ?></textarea>
                <?= form_error('judul') ?>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
                <button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Updating...">Update Skripsi</button>
            </div>
        </div>
    </div>
</form>

<?php $this->load->view('partials/modals/_alert') ?>
