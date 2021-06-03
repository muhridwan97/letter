<div class="row w-100" >
    <div class="col-lg-8 mx-auto">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-sm-flex justify-content-between">
                    <h5 class="card-title mb-sm-0">Data Signature Barcode</h5>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Tanda tangan untuk:</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            <?= $data['tujuan']; ?>
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Ditanda Tangani Oleh:</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            <?= $data['signature_by']; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
