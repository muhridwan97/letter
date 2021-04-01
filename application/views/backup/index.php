<div class="card border-primary border-primary grid-margin">
    <div class="card-body py-3">
        <div class="d-sm-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">Database</h4>
                <p class="mb-sm-0 text-muted"><?= $this->config->item('app_name') ?>'s database file</p>
            </div>
            <a href="<?= site_url("utility/backup/database") ?>" class="btn btn-sm btn-primary">
                Backup Now
            </a>
        </div>
    </div>
    <div class="card-footer small">
        <strong>Live on</strong>
        <?= $this->db->hostname ?><?= empty($this->db->port) ? '' : ':' . $this->db->port ?>/<?= $this->db->database ?>
    </div>
</div>

<div class="card border-primary border-primary grid-margin">
    <div class="card-body py-3">
        <div class="d-sm-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">Upload</h4>
                <p class="mb-sm-0 text-muted"><?= $this->config->item('app_name') ?>'s uploaded file</p>
            </div>
            <a href="<?= site_url("utility/backup/upload") ?>" class="btn btn-sm btn-primary">
                Backup Now
            </a>
        </div>
    </div>
    <div class="card-footer small">
        <strong>Live on</strong>
        app/uploads
    </div>
</div>

<div class="card border-primary grid-margin">
    <div class="card-body py-3">
        <div class="d-sm-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title mb-0">Application</h4>
                <p class="mb-sm-0 text-muted"><?= $this->config->item('app_name') ?>'s source file</p>
            </div>
            <a href="<?= site_url("utility/backup/app") ?>" class="btn btn-sm btn-primary">
                Backup Now
            </a>
        </div>
    </div>
    <div class="card-footer small">
        <strong>Live on</strong>
        app
    </div>
</div>
