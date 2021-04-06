<div class="card mb-3">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <h5 class="card-title mb-sm-0">Data Departments</h5>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                <?php if(!$this->config->item('sso_enable')): ?>
                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_CREATE)): ?>
                        <a href="<?= site_url('master/department/create') ?>" class="btn btn-sm btn-primary">
                            <i class="mdi mdi-plus-box-outline mr-2"></i>CREATE
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
		<table class="table table-hover table-sm mt-3 responsive position-relative">
			<thead>
			<tr class="toggle-row" data-button="toggle-department">
				<th class="text-md-center" style="width: 80px">
					<button type="button" data-toggle="tooltip" data-title="Toggle All" class="btn btn-sm py-0 px-1 mr-2 position-absolute btn-outline-secondary d-none d-md-inline-block btn-toggle-expand-all btn-toggle-auto-hide state-collapse"
							style="left: 5px" data-target="toggle-department">
						<i class="mdi mdi-plus" style="margin: 0"></i>
					</button>
					<span class="ml-md-2">No</span>
				</th>
				<th>Department</th>
				<th>Total Employee</th>
				<th>Total Curriculum</th>
				<th>Description</th>
				<th style="min-width: 120px" class="text-md-right">Action</th>
			</tr>
			</thead>
			<tbody>
			<?php $no = isset($departments) ? ($departments['current_page'] - 1) * $departments['per_page'] : 0 ?>
			<?php foreach ($departments['data'] as $department): ?>
				<tr class="toggle-row" data-button="department-<?= $department['id'] ?>">
					<td class="text-md-center">
						<?php if (!empty($department['curriculums'])): ?>
							<button type="button" class="btn btn-sm py-0 px-1 mr-2 position-absolute btn-outline-secondary d-none d-md-inline-block toggle-department btn-toggle-expand btn-toggle-auto-hide state-collapse"
									style="left: 5px" data-target="department-<?= $department['id'] ?>" data-id="department-<?= $department['id'] ?>">
								<i class="mdi mdi-plus" style="margin: 0"></i>
							</button>
						<?php endif; ?>
						<span class="ml-md-1"><?= ++$no ?></span>
					</td>
					<td><?= $department['department'] ?></td>
					<td><?= $department['total_employee'] ?></td>
					<td><?= $department['total_curriculums'] ?></td>
					<td><?= if_empty($department['description'], 'No description') ?></td>
					<td class="text-md-right">
						<div class="dropdown">
							<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionButton" data-toggle="dropdown">
								Action
							</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionButton">
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_VIEW)): ?>
									<a class="dropdown-item"
									   href="<?= site_url('master/department/view/' . $department['id']) ?>">
										<i class="mdi mdi-eye-outline mr-2"></i> View
									</a>
								<?php endif; ?>
								<?php if(!$this->config->item('sso_enable')): ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_EDIT)): ?>
										<a class="dropdown-item"
										   href="<?= site_url('master/department/edit/' . $department['id']) ?>">
											<i class="mdi mdi-square-edit-outline mr-2"></i> Edit
										</a>
									<?php endif; ?>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_CURRICULUM_CREATE)): ?>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/curriculum/create/' . $department['id']) ?>">
										<i class="mdi mdi-plus mr-2"></i> Add Curriculum
									</a>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_CURRICULUM_EDIT)): ?>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/curriculum/sort/' . $department['id']) ?>">
										<i class="mdi mdi-sort-variant mr-2"></i> Sort Curriculums
									</a>
								<?php endif; ?>
								<?php if(!$this->config->item('sso_enable')): ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_DELETE)): ?>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
										   data-id="<?= $department['id'] ?>" data-label="<?= $department['department'] ?>" data-title="Department"
										   data-url="<?= site_url('master/department/delete/' . $department['id']) ?>">
											<i class="mdi mdi-trash-can-outline mr-2"></i> Delete
										</a>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						</div>
						<?php if (!empty($department['curriculums'])): ?>
							<button type="button" class="btn btn-sm btn-outline-secondary d-inline-block d-md-none btn-toggle-expand state-collapse"
									style="left: 3px" data-target="department-<?= $department['id'] ?>" data-id="department-<?= $department['id'] ?>">
								<i class="mdi mdi-plus"></i>
							</button>
						<?php endif; ?>
					</td>
				</tr>
				<?php foreach ($department['curriculums'] as $curriculumIndex => $curriculum): ?>
					<?php if ($curriculumIndex == 0): ?>
						<tr class="ml-4 ml-md-0 text-primary bg-primary-fade row-header row-header-<?= $department['id'] ?>" data-parent="department-<?= $department['id'] ?>" style="display: none">
							<th class="d-none d-md-table-cell"><span class="d-inline-block d-md-none">No</span></th>
							<th>Curriculum</th>
							<th>Course</th>
							<th>Exam</th>
							<th>Last Updated</th>
							<th class="text-md-right">Status</th>
						</tr>
					<?php endif; ?>
					<tr class="ml-4 ml-md-0 text-fade" data-header="row-header-<?= $curriculum['id'] ?>" data-parent="department-<?= $department['id'] ?>" style="display: none">
						<td><span class="d-md-none"><?= $curriculumIndex + 1 ?></span></td>
						<td>
							<img src="<?= empty($curriculum['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($curriculum['cover_image']) ?>"
								 alt="Image cover" id="image-cover" class="rounded mr-2" style="height: 30px; width: 30px; object-fit: cover">
							<a href="<?= site_url('syllabus/curriculum/view/' . $curriculum['id']) ?>" class="text-link">
								<?= $curriculum['curriculum_title'] ?>
							</a>
						</td>
						<td><?= $curriculum['total_courses'] ?></td>
						<td><?= $curriculum['total_exams'] ?></td>
						<td><?= format_date(if_empty($curriculum['updated_at'], $curriculum['created_at']), 'd M Y H:i') ?></td>
						<td class="text-md-right"><?= $curriculum['status'] ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
			<?php if(empty($departments['data'])): ?>
				<tr>
					<td colspan="5">No departments data available</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
        <?php $this->load->view('partials/_pagination', ['pagination' => $departments]) ?>
    </div>
</div>

<?php $this->load->view('department/_modal_filter') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>
