<div class="row">
    <?php if ( ! isset( $_GET['teacher_id'] ) ) : ?>
        <div class="col-12">
            <div class="card p-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between px-2">
                        <h6 class="h5 mb-0">List of Teachers</h6>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newTeacherModal">Add New Teacher</button>

                        <div class="modal fade" id="newTeacherModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <form action="/" method="POST" id="teacherForm">
                                        <div class="modal-header">
                                            <h5 class="modal-title font-weight-normal" id="newTeacherModalLabel">New Teacher Form</h5>
                                            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <div class="input-group input-group-outline">
                                                        <label for="new_first_name" class="form-label">First Name</label>
                                                        <input type="text" class="form-control" name="new_first_name" id="new_first_name" required>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div class="input-group input-group-outline">
                                                        <label for="new_last_name" class="form-label">Last Name</label>
                                                        <input type="text" class="form-control" name="new_last_name" id="new_last_name" required>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div class="input-group input-group-outline">
                                                        <label for="new_email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" name="new_email" id="new_email" required>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <div class="input-group input-group-outline">
                                                        <label for="new_password" class="form-label">Password</label>
                                                        <input type="password" class="form-control" name="new_password" id="new_password" required>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="input-group input-group-static">
                                                        <label for="assigned_school" class="text-primary ms-0">Assigned School</label>
                                                        <select class="form-control" name="assigned_school" id="assigned_school" required>
                                                            <option value="0" selected>Unassigned</option>
                                                            <?php foreach( $available_schools->fetch_all( MYSQLI_ASSOC ) as $available_school ) : ?>
                                                                <option value="<?= $available_school['id'] ?>"><?= ucfirst( $available_school['name'] ) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn bg-gradient-secondary shadow-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn bg-gradient-primary" id="teacherFormSubmit">Submit</button>
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
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Email</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Assigned School</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Create Added</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ( $all_teachers->num_rows > 0 ) : ?>
                                    <?php foreach( $all_teachers->fetch_all( MYSQLI_ASSOC ) as $teacher ) : ?>
                                        <tr>
                                            <td>
                                                <h6 class="mb-0"><?= $teacher['first_name'] . ' ' . $teacher['last_name'] ?></h6>
                                            </td>
                                            <td>
                                                <p class="mb-0 font-weight-normal"><?= $teacher['email'] ?></p>
                                            </td>
                                            <td>
                                                <?php
                                                    $assigned_school = $sm_teachers->getTeacherSchool( $teacher['id'] );

                                                    if ( $assigned_school->num_rows > 0 ) {
                                                        $assigned_school = $assigned_school->fetch_all( MYSQLI_ASSOC )[0];

                                                        if ( $assigned_school['school_id'] == '0' || $assigned_school['school_id'] == 0 ) {
                                                            echo "Unassigned";
                                                        } else {
                                                            echo $assigned_school['name'];
                                                        }

                                                    } else {
                                                        echo "Unassigned";
                                                    }
                                                ?>
                                            </td>
                                            <td class="align-middle">
                                                <a href="/teachers?teacher_id=<?= $teacher['id'] ?>" class="btn btn-primary mb-0">View</a>
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
                    <h5 class="card-title mb-4">Teacher Details</h5>

                    <?php if ( $teachers->num_rows > 0 ) : ?>
                        <?php
                            $teacher = $teachers->fetch_all( MYSQLI_ASSOC )[0];
                        ?>
                        <form action="/" method="POST" id="teacherForm">
                            <input type="hidden" name="teacher_id" id="teacher_id" value="<?=  $teacher['id'] ?>">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="input-group input-group-outline is-filled">
                                        <label for="u_first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="u_first_name" id="u_first_name" value="<?= $teacher['first_name'] ?>" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group input-group-outline is-filled">
                                        <label for="u_last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="u_last_name" id="u_last_name" value="<?= $teacher['last_name'] ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="input-group input-group-outline is-filled">
                                        <label for="u_email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="u_email" id="u_email" value="<?= $teacher['email'] ?>" readonly required>
                                    </div>
                                    <p class="text-xs mt-2 mb-0">Read only</p>
                                </div>
                                <div class="col-6">
                                    <div class="input-group input-group-static">
                                        <?php
                                            if ( $assigned_school->num_rows > 0 ) {
                                                $assigned_school = $assigned_school->fetch_all( MYSQLI_ASSOC )[0];
                                            }
                                        ?>
                                        <label for="u_assigned_school" class="text-primary ms-0">Assigned School</label>
                                        <select class="form-control" name="u_assigned_school" id="u_assigned_school" required>
                                            <option value="0"<?= $assigned_school['school_id'] == '0' ? 'selected' : '' ?>>Unassigned</option>
                                            <?php foreach( $available_schools->fetch_all( MYSQLI_ASSOC ) as $available_school ) : ?>
                                                <option value="<?= $available_school['id'] ?>"<?= $assigned_school['school_id'] == $available_school['id'] ? 'selected' : '' ?>><?= ucfirst( $available_school['name'] ) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn bg-gradient-primary" id="teacherFormSubmit">Update</button>
                                </div>
                            </div>
                        </form>
                    <?php else : ?>
                        <div class="row py-5">
                            <div class="col-12 text-center">Teacher details not found!</div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    <?php endif; ?>
</div>