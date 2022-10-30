<div class="row">
    <div class="col-12">
        <div class="card p-2">
            <div class="d-flex justify-content-between px-2">
                <h6 class="mb-0">List of Users</h6>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newUserModal">Add new user</button>

                <div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="/" method="POST" id="newUserForm">
                                <div class="modal-header">
                                    <h5 class="modal-title font-weight-normal" id="newUserModalLabel">New User Form</h5>
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
                                                <label for="new_role" class="ms-0">Select role</label>
                                                <select class="form-control" name="new_role" id="new_role" required>
                                                    <option value="" selected disabled>Select an option</option>
                                                    <option value="1">Admin</option>
                                                    <option value="2">Teacher</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn bg-gradient-secondary shadow-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn bg-gradient-primary" id="newUserSubmit">Submit</button>
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
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date Added</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( count( $all_users ) > 0 ) : ?>
                            <?php foreach( $all_users as $user ) : ?>
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-xs"><?= $user['first_name'] . ' ' . $user['last_name'] ?></h6>
                                            <p class="text-xs text-secondary mb-0"><?= $user['role'] ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-normal mb-0">
                                            <?= date( DEFAULT_DATE_FORMAT, strtotime( $user['created_at'] ) ) ?>
                                        </p>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-primary mb-0">Edit</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>