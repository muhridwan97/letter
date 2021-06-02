<div class="row w-100" >
    <div class="col-lg-8 mx-auto">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-sm-flex justify-content-between">
                    <h5 class="card-title mb-sm-0">Data Repository</h5>
                </div>
                <div class="<?= $repositories['total_data'] > 3 ? 'table-responsive' : '' ?>">
                    <table class="table table-hover table-sm mt-3 responsive" id="table-repository">
                        <thead>
                        <tr>
                            <th class="text-md-center" style="width: 60px">No</th>
                            <th>Nama Surat</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = isset($repositories) ? ($repositories['current_page'] - 1) * $repositories['per_page'] : 0 ?>
                        <?php foreach ($repositories['data'] as $repository): ?>
                            <tr>
                                <td class="text-md-center"><?= ++$no ?></td>
                                <td><a href="<?= base_url().'uploads/'.$repository['src']?>"><?= $repository['name'] ?></a></td>
                                <td><?= if_empty($repository['description'], '-')?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(empty($repositories['data'])): ?>
                            <tr>
                                <td colspan="3">No repositories data available</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php $this->load->view('partials/_pagination', ['pagination' => $repositories]) ?>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('repository/_modal_filter') ?>
