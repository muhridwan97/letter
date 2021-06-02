<form action="<?= site_url('skripsi/logbook/update/' . $logbook['id']) ?>" method="POST" enctype="multipart/form-data" id="form-logbook">
    <?= _csrf() ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Edit Logbook</h5>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="skripsi">Mahasiswa</label>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_ALL_ACCESS)!=true && !AuthorizationModel::isAuthorized(PERMISSION_LOGBOOK_VALIDATE)): ?>
                            <input type="hidden" name="skripsi" id="skripsi" value="<?= $logbook['id_skripsi'] ?>">
                            <input type="text" class="form-control" readonly name="judul_skripsi" id="judul_skripsi" value="<?= $logbook['judul'] ?>">
                        <?php else: ?>
                                <select class="form-control select2" name="skripsi" id="skripsi" required data-placeholder="Select skripsi">
                                <option value=""></option>
                                    <?php foreach ($skripsis as $skripsi): ?>
                                        <option value="<?= $skripsi['id'] ?>"<?= set_select('skripsi', $skripsi['id'], $skripsi['id'] == $logbook['id_skripsi']) ?>>
                                            <?= $skripsi['judul'] ?> - <?= $skripsi['nama_mahasiswa'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('skripsi') ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="tanggal">Tanggal Konsultasi</label>
                        <input type="text" class="form-control datepicker" id="tanggal" name="tanggal" required maxlength="100"
                               value="<?= set_value('tanggal', format_date($logbook['tanggal'], 'd F Y')) ?>" placeholder="Select date">
                        <?= form_error('tanggal') ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="konsultasi">Judul Konsultasi</label>
                <input type="text" class="form-control" id="konsultasi" name="konsultasi" required maxlength="50"
                        value="<?= set_value('konsultasi', $logbook['konsultasi']) ?>" placeholder="Input judul konsultasi">
                <?= form_error('konsultasi') ?>
            </div>
            <div class="form-group">
                <label for="description">Rincian description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500" required
                          placeholder="Enter logbook description"><?= set_value('description', $logbook['description']) ?></textarea>
                <?= form_error('description') ?>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
                <button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Updating...">Update Logbook</button>
            </div>
        </div>
    </div>
</form>

<?php $this->load->view('partials/modals/_alert') ?>
