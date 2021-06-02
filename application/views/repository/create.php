<form action="<?= site_url('repository/save') ?>" method="POST" enctype="multipart/form-data" id="form-repository">
    <?= _csrf() ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Create New Repository</h5>
            
            <div class="form-group">
                <label for="name">Nama surat</label>
                <input type="text" class="form-control" id="name" name="name" required maxlength="50"
                        value="<?= set_value('name') ?>" placeholder="Input nama surat">
                <?= form_error('name') ?>
            </div>
            
            <div class="form-group">
                <label>File surat</label>
                <input type="file" id="file_surat" name="file_surat" class="file-upload-default" data-max-size="10000000" required>
                <div class="input-group col-xs-12">
                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload file">
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-success btn-simple-upload" type="button">
                            Upload
                        </button>
                    </span>
                </div>
                <?= form_error('file_surat') ?>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500" 
                          placeholder="Enter repositories description"><?= set_value('description') ?></textarea>
                <?= form_error('description') ?>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
                <button type="submit" class="btn btn-success mr-2" data-toggle="one-touch" data-touch-message="Saving...">Save Repositories</button>
            </div>
        </div>
    </div>
</form>

<?php $this->load->view('partials/modals/_alert') ?>
