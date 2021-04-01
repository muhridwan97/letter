<div class="modal fade" id="modal-set-category" tabindex="-1" role="dialog" aria-labelledby="Set category Requisition" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="#" method="post">
                <?= _csrf() ?>
                <?= _method('put') ?>
                <div class="modal-header">
                    <h5 class="modal-title">Set Requisition Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Requisition <strong id="requisition-title"></strong> (<strong id="requisition-description"></strong>)
                        set to:
                    </p>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="custom-select" name="category" id="category">
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"<?= set_select('category', $category['id']) ?>>
                                    <?= $category['category'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success" data-toggle="one-touch" data-touch-message="Updating...">Set Category</button>
                </div>
            </form>
        </div>
    </div>
</div>