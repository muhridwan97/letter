<style>
    ul.timeline {
    list-style-type: none;
    position: relative;
}
ul.timeline:before {
    content: ' ';
    background: #d4d9df;
    display: inline-block;
    position: absolute;
    left: 29px;
    width: 2px;
    height: 100%;
    z-index: 400;
}
ul.timeline > li {
    margin: 20px 0;
    padding-left: 20px;
}
ul.timeline > li:before {
    content: ' ';
    background: white;
    display: inline-block;
    position: absolute;
    border-radius: 50%;
    border: 3px solid #22c0e8;
    left: 20px;
    width: 20px;
    height: 20px;
    z-index: 400;
}
</style>
<div class="form-plaintext">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">View Skripsi</h5>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">
                                <?= if_empty($skripsi['nama_mahasiswa'], 'No name') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">NIM</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">
                                <?= if_empty($skripsi['no_student'], '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Pembimbing</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">
                                <?= if_empty($skripsi['nama_pembimbing'], 'Tidak ada Pembimbing') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">NIP</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">
                                <?= if_empty($skripsi['no_lecturer'], 'NO NIP') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Judul</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">
                                <?= if_empty($skripsi['judul'], 'NO Judul   ') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">
                                <?php
                                $statuses = [
                                    SkripsiModel::STATUS_ACTIVE => 'success',
                                    SkripsiModel::STATUS_PENDING => 'secondary',
                                    SkripsiModel::STATUS_REJECTED => 'danger',
                                ]
                                ?>
                                <label class="mb-0 small badge badge-<?= $statuses[$skripsi['status']] ?>">
                                    <?= $skripsi['status'] ?>
                                </label>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3 ">
        <div class="card-body">
            <div class="container mt-5 mb-5">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <h4>Latest Consultation</h4>
                        <ul class="timeline">
                        <?php foreach ($logbooks as $key => $logbook) : ?>
                            <li>
                                <a href="<?= base_url().'skripsi/logbook/view/'.$logbook['id'] ?>"><?= $logbook['konsultasi'] ?></a>
                                <a href="#" class="float-right"><?= format_date($logbook['tanggal'],'d F Y') ?></a>
                                <p><?= $logbook['description'] ?></p>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if(!$this->config->item('sso_enable')): ?>
                <?php if(AuthorizationModel::isAuthorized(PERMISSION_SKRIPSI_EDIT)): ?>
                    <a href="<?= site_url('skripsi/skripsi/edit/' . $skripsi['id']) ?>" class="btn btn-primary">
                        Edit Skripsi
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    
    
</div>
