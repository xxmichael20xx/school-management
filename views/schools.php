<div class="row">
    <?php if ( ! isset( $_GET['school_id'] ) ) : ?>
        <div class="col-12">
            <div class="card p-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-2">
                        <h6 class="h5 mb-0">List of Schools</h6>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newSchoolModal">Add New School</button>

                        <div class="modal fade" id="newSchoolModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="/" method="POST" id="schoolForm">
                                        <div class="modal-header">
                                            <h5 class="modal-title font-weight-normal" id="newSchoolModalLabel">New School Form</h5>
                                            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <div class="input-group input-group-outline">
                                                        <label for="school_name" class="form-label">Name</label>
                                                        <input type="text" class="form-control" name="school_name" id="school_name" required>
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <div class="input-group input-group-outline">
                                                        <label for="school_address" class="form-label">Address</label>
                                                        <input type="text" class="form-control" name="school_address" id="school_address" required>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group input-group-static">
                                                        <label for="school_level" class="text-primary ms-0">Select Level</label>
                                                        <select class="form-control" name="school_level" id="school_level" required>
                                                            <option value="" selected disabled>Select an option</option>
                                                            <?php foreach( $levels as $level ) : ?>
                                                                <option value="<?= $level ?>"><?= ucfirst( $level ) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6 d-flex align-items-center">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input bg-primary" type="checkbox" name="school_status" id="school_status" checked>
                                                        <label class="form-check-label" id="school_status_text" for="school_status">Status: Active</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn bg-gradient-secondary shadow-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn bg-gradient-primary" id="schoolFormSubmit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Address</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">No. of Teachers</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Date Added</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ( $all_schools->num_rows > 0 ) : ?>
                                    <?php foreach( $all_schools->fetch_all( MYSQLI_ASSOC ) as $school ) : ?>
                                        <?php
                                            $assigned_teachers = $sm_schools->getAssignTeachers( $school['id'] );
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column justify-space-center">
                                                    <h6 class="mb-0"><?= $school['name'] ?></h6>
                                                    <p class="font-weight-bold text-<?= $school['is_active'] ? 'success' : 'danger' ?> mb-0"><?= $school['is_active'] ? 'Active' : 'Inactive' ?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="mb-0 font-weight-normal"><?= $school['address'] ?></p>
                                            </td>
                                            <td>
                                                <p class="font-weight-normal mb-0">
                                                    <?= $assigned_teachers->num_rows ?>
                                                </p>
                                            </td>
                                            <td class="align-middle">
                                                <a href="/schools?school_id=<?= $school['id'] ?>" class="btn btn-primary mb-0">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">No result(s)</h6>
                                        </td>
                                        <td>
                                            <p class="mb-0 font-weight-normal">No result(s)</p>
                                        </td>
                                        <td>
                                            <p class="font-weight-normal mb-0">No result(s)</p>
                                        </td>
                                        <td>
                                            <p class="font-weight-normal mb-0">No result(s)</p>
                                        </td>
                                        <td class="align-middle"></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="col-6">
            <div class="card p-2">
                <div class="card-body">
                    <h5 class="card-title mb-4">School Details</h5>

                    <?php if ( $school->num_rows > 0 ) : ?>
                        <?php
                            $school = $school->fetch_all( MYSQLI_ASSOC )[0];
                        ?>
                        <form action="/" method="POST" id="schoolForm">
                            <input type="hidden" name="school_id" id="school_id" value="<?=  $school['id'] ?>">
                            <div class="row mb-4">
                                <div class="col-12 mb-3">
                                    <div class="input-group input-group-outline is-filled">
                                        <label for="u_school_name" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="u_school_name" id="u_school_name" value="<?= $school['name'] ?>" required>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="input-group input-group-outline is-filled">
                                        <label for="u_school_address" class="form-label">Address</label>
                                        <input type="text" class="form-control" name="u_school_address" id="u_school_address" value="<?= $school['address'] ?>" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group input-group-static">
                                        <label for="u_school_level" class="text-primary ms-0">Select Level</label>
                                        <select class="form-control" name="u_school_level" id="u_school_level" required>
                                            <option value="" selected disabled>Select an option</option>
                                            <?php foreach( $levels as $level ) : ?>
                                                <option 
                                                    value="<?= $level ?>"
                                                    <?= $school['level'] == $level ? 'selected' : '' ?>
                                                >
                                                    <?= ucfirst( $level ) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6 d-flex align-items-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input<?= $school['is_active'] ? ' bg-primary': '' ?>" type="checkbox" name="u_school_status" id="u_school_status" <?= $school['is_active'] ? 'checked': '' ?>>
                                        <label class="form-check-label" id="u_school_status_text" for="u_school_status">Status: Active</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <?php if ( $school['is_active'] ) : ?>
                                        <button type="button" class="btn bg-gradient-danger" id="schoolDelete" data-id="<?= $_GET['school_id'] ?>">Delete</button>
                                    <?php else : ?>
                                        <button type="button" class="btn bg-gradient-success" id="schoolRestore" data-id="<?= $_GET['school_id'] ?>">Restore</button>
                                    <?php endif; ?>
                                    <button type="submit" class="btn bg-gradient-primary" id="schoolFormSubmit">Update</button>
                                </div>
                            </div>
                        </form>
                    <?php else : ?>
                        <div class="row py-5">
                            <div class="col-12 text-center">School details not found!</div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    <?php endif; ?>
</div>