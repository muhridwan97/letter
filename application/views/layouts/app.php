<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?= $this->security->get_csrf_hash() ?>">
    <meta name="base-url" content="<?= site_url() ?>">
    <meta name="user-id" content="<?= UserModel::loginData('id') ?>">
    <meta name="theme-color" content="#5983e8">
    <title><?= $this->config->item('app_name') ?> | <?= isset($title) ? $title : 'Home' ?></title>
    <link rel="stylesheet" href="<?= base_url(get_asset('vendors.css')) ?>">
    <link rel="stylesheet" href="<?= base_url(get_asset('app.css')) ?>">
    <link rel="icon" href="<?= base_url('assets/dist/img/layouts/icon.png') ?>" type="image/x-icon">
    <script src="https://js.pusher.com/4.3/pusher.min.js"></script>
</head>
<body>
<div class="container-scroller">
    <?php $this->load->view('partials/_header') ?>

    <div class="container-fluid page-body-wrapper">
        <?php $this->load->view('partials/_sidebar') ?>

        <div class="main-panel">
            <div class="content-wrapper">

                <?php $this->load->view('partials/_alert') ?>
                <?php $this->load->view($page, $data) ?>

            </div>

            <?php $this->load->view('partials/_footer') ?>
        </div>
    </div>
</div>

<script src="<?= base_url(get_asset('runtime.js')) ?>"></script>
<script src="<?= base_url(get_asset('vendors.js')) ?>"></script>
<script src="<?= base_url(get_asset('app.js')) ?>"></script>
</body>

</html>
