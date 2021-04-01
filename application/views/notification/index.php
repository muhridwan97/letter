<div class="card grid-margin">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">
                <span>Notification</span>
            </h4>
            <a class="small" href="<?= site_url('notification/read_all') ?>">
                Mark All as Read
            </a>
        </div>

        <?php foreach ($notifications as $notification): ?>
            <div class="d-flex flex-column mb-2">
                <div class="d-sm-flex justify-content-sm-between flex-grow-1">
                    <div>
                        <a href="<?= site_url('notification/read/'.$notification['id'].'?redirect=') ?><?= get_if_exist($notification['data'], 'url', '#') ?>" class="mb-0 d-block <?= $notification['is_read'] ? 'text-dark' : 'text-primary' ?>">
                            <?= $notification['data']['message'] ?>
                        </a>
                        <p class="text-muted small mb-0"><?= get_if_exist($notification['data'], 'description', '') ?></p>
                    </div>
                    <small class="text-muted text-right" style="min-width: 170px">
                        <?= relative_time($notification['created_at']) ?>
                    </small>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(empty($notifications)): ?>
            <p class="text-muted">No notification available.</p>
        <?php endif; ?>
    </div>
</div>