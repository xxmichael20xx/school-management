<?php

class Api
{
    
    public $request, $path, $mysqli, $helpers;

    public function __construct()
    {
        $sm_db = new SM_DB();
        $this->helpers = new SM_Helpers();
        $this->mysqli = $sm_db->sql();
        $this->request = $_SERVER['REQUEST_URI'];
        $this->path = parse_url( $this->request )['path'];

        $this->defineApis();
    }

    /**
     * Defined to check if URL starts with /api
     * 
     * @return Boolean
     */
    public function checkIfApi() {
        return substr( $this->path, 0, 4 ) === '/api';
    }

    public function defineApis() {
        if ( ! $this->checkIfApi() ) return;

        $request = str_replace( '/api', '', $this->path );

        if ( $request == '/login' ) {
            $this->login();
            return;
        }

        if ( $request == '/schools/new' ) {
            $this->newSchool();
            return;
        }

        if ( $request == '/schools/update' ) {
            $this->updateSchool();
            return;
        }

        if ( $request == '/schools/delete' ) {
            $this->deleteSchool();
            return;
        }

        if ( $request == '/schools/restore' ) {
            $this->restoreSchool();
            return;
        }

        if ( $request == '/teachers/new' ) {
            $this->newTeacher();
            return;
        }

        if ( $request == '/teachers/update' ) {
            $this->updateTeacher();
            return;
        }

        return;
    }

    /**
     * Returns a json data to frontend
     * 
     * @param $data Contains the data for the response
     * @return Void
     */
    public function response( $data ) {
        echo json_encode( $data );
        exit();
    }

    public function login() {
        $email = $_POST['login_email'];
        $password = $_POST['login_password'];

        $emailExists = $this->mysqli->prepare( "SELECT * FROM `users` WHERE `email`=?" );
        $emailExists->bind_param( 's', $email );
        $emailExists->execute();
        $emailResult = $emailExists->get_result();
        $emailExistsCount = $emailResult->num_rows;

        if ( $emailExistsCount < 1 ) {
            $this->response( [
                'success' => false,
                'message' => 'Email or password is incorrect!'
            ] );
            exit();

        } else {
            $userData = $emailResult->fetch_all( MYSQLI_ASSOC )[0];
            $hashPassword = $userData['password'];

            if ( password_verify( $password, $hashPassword ) ) {
                $_SESSION['current_user_id'] = $userData['id'];
                $_SESSION['role_type'] = $userData['role_id'] == 1 ? 'admin' : 'teacher';

                $this->response( [
                    'success' => true,
                    'message' => 'Successfully logged in!'
                ] );
                exit();

            } else {
                $this->response( [
                    'success' => false,
                    'message' => 'Email or password is incorrect!'
                ] );
                exit();
            }
        }
    }

    public function newSchool() {
        $invalid = [];

        if ( ! isset( $_POST['school_name'] ) || empty( $_POST['school_name'] ) ) {
            $invalid['school_name'] = "<b>Name</b> is a required field";

        } else {
            $schoolExist = $this->mysqli->prepare( "SELECT * FROM `schools` WHERE `name`=?" );
            $schoolExist->bind_param( 's', $_POST['school_name'] );
            $schoolExist->execute();
            $schoolCount = $schoolExist->get_result()->num_rows;

            if ( $schoolCount > 0 ) {
                $invalid['school_name'] = "<b>Name</b> is a already taken.";
            }
        }

        if ( ! isset( $_POST['school_address'] ) || empty( $_POST['school_address'] ) ) {
            $invalid['school_address'] = "<b>Address</b> is a required field";
        }

        if ( ! isset( $_POST['school_level'] ) || empty( $_POST['school_level'] ) ) {
            $invalid['school_level'] = "<b>Level</b> is a required field";

        }

        $_POST['school_status'] = ! isset( $_POST['school_status'] ) ? 0 : 1;

        if ( count( $invalid ) > 0 ) {
            $this->response( [
                'success' => false,
                'type' => 'validation',
                'message' => 'Some form fields are invalid.',
                'data' => $invalid
            ] );
            exit();
        }

        foreach ( $_POST as $key => $value ) {
            $_POST[$key] = $this->mysqli->real_escape_string( $value );
        }

        $addSchool = $this->mysqli->prepare( "INSERT INTO `schools` (name, address, level, is_active) VALUES (?, ?, ?, ?)" );
        $addSchool->bind_param( 'ssss', $_POST['school_name'], $_POST['school_address'], $_POST['school_level'], $_POST['school_status'] );
        $addSchoolResult = $addSchool->execute();

        $title = $addSchoolResult ? 'Add Success': 'Add Failed';
        $text = $addSchoolResult ? 'New School has been created' : 'Failed to add new school';
        
        $this->response( [
            'success' => $addSchoolResult,
            'type' => 'add',
            'title' => $title,
            'text' => $text
        ] );
    }

    public function updateSchool() {
        $invalid = [];

        if ( ! isset( $_POST['u_school_name'] ) || empty( $_POST['u_school_name'] ) ) {
            $invalid['u_school_name'] = "<b>Name</b> is a required field";

        } else {
            $schoolExist = $this->mysqli->prepare( "SELECT * FROM `schools` WHERE `name`=? AND `id`!=?" );
            $schoolExist->bind_param( 'ss', $_POST['u_school_name'], $_POST['school_id'] );
            $schoolExist->execute();
            $schoolCount = $schoolExist->get_result()->num_rows;

            if ( $schoolCount > 0 ) {
                $invalid['u_school_name'] = "<b>Name</b> is a already taken.";
            }
        }

        if ( ! isset( $_POST['u_school_address'] ) || empty( $_POST['u_school_address'] ) ) {
            $invalid['u_school_address'] = "<b>Address</b> is a required field";
        }

        if ( ! isset( $_POST['u_school_level'] ) || empty( $_POST['u_school_level'] ) ) {
            $invalid['u_school_level'] = "<b>Level</b> is a required field";

        }

        if ( ! isset( $_POST['u_school_status'] ) ) {
            $_POST['u_school_status'] = 0;

        } else {
            $_POST['u_school_status'] = 1;
        }

        if ( count( $invalid ) > 0 ) {
            $this->response( [
                'success' => false,
                'type' => 'validation',
                'message' => 'Some form fields are invalid.',
                'data' => $invalid
            ] );
            exit();
        }

        foreach ( $_POST as $key => $value ) {
            $_POST[$key] = $this->mysqli->real_escape_string( $value );
        }

        $updateSchool = $this->mysqli->prepare( "UPDATE `schools` SET `name`=?, `address`=?, `level`=?, `is_active`=? WHERE `id`=?" );
        $updateSchool->bind_param( 'sssss', $_POST['u_school_name'], $_POST['u_school_address'], $_POST['u_school_level'], $_POST['u_school_status'], $_POST['school_id'] );
        $updateSchoolResult = $updateSchool->execute();

        $title = $updateSchoolResult ? 'Update Success': 'Update Failed';
        $text = $updateSchoolResult ? 'School has been updated' : 'Failed to update school';
        $this->response( [
            'success' => $updateSchoolResult,
            'type' => 'update',
            'title' => $title,
            'text' => $text
        ] );
    }

    public function deleteSchool() {
        $deleteSchool = $this->mysqli->prepare( "UPDATE `schools` SET `is_active`=0 WHERE `id`=?" );
        $deleteSchool->bind_param( 's', $_POST['id'] );
        $deleteSchoolResult = $deleteSchool->execute();

        $title = $deleteSchoolResult ? 'Delete Success': 'Delete Failed';
        $text = $deleteSchoolResult ? 'School has been deleted' : 'Failed to delete school';
        $this->response( [
            'success' => $deleteSchoolResult,
            'type' => 'delete',
            'title' => $title,
            'text' => $text
        ] );
    }

    public function restoreSchool() {
        $restoreSchool = $this->mysqli->prepare( "UPDATE `schools` SET `is_active`=1 WHERE `id`=?" );
        $restoreSchool->bind_param( 's', $_POST['id'] );
        $restoreSchoolResult = $restoreSchool->execute();

        $title = $restoreSchoolResult ? 'Restore Success': 'Restore Failed';
        $text = $restoreSchoolResult ? 'School has been restored' : 'Failed to restore school';
        $this->response( [
            'success' => $restoreSchoolResult,
            'type' => 'restore',
            'title' => $title,
            'text' => $text
        ] );
    }

    public function newTeacher() {
        $invalid = [];

        if ( ! isset( $_POST['new_first_name'] ) || empty( $_POST['new_first_name'] ) ) {
            $invalid['new_first_name'] = "<b>First name</b> is a required field";
        }

        if ( ! isset( $_POST['new_last_name'] ) || empty( $_POST['new_last_name'] ) ) {
            $invalid['new_last_name'] = "<b>Last name</b> is a required field";
        }

        if ( ! isset( $_POST['new_email'] ) || empty( $_POST['new_email'] ) ) {
            $invalid['new_email'] = "<b>Last name</b> is a required field";

        } else {
            $schoolExist = $this->mysqli->prepare( "SELECT * FROM `users` WHERE `email`=?" );
            $schoolExist->bind_param( 's', $_POST['new_email'] );
            $schoolExist->execute();
            $schoolCount = $schoolExist->get_result()->num_rows;

            if ( $schoolCount > 0 ) {
                $invalid['new_email'] = "<b>Name</b> is a already taken.";
            }
        }

        if ( ! isset( $_POST['new_password'] ) || empty( $_POST['new_password'] ) ) {
            $invalid['new_password'] = "<b>Password</b> is a required field";

        } else {
            $_POST['new_password'] = password_hash( $_POST['new_password'], PASSWORD_BCRYPT );
        }

        if ( count( $invalid ) > 0 ) {
            $this->response( [
                'success' => false,
                'type' => 'validation',
                'message' => 'Some form fields are invalid.',
                'data' => $invalid
            ] );
            exit();
        }

        foreach ( $_POST as $key => $value ) {
            if ( $key == 'new_password' ) continue;
            $_POST[$key] = $this->mysqli->real_escape_string( $value );
        }

        $role = 2;
        $addTeacher = $this->mysqli->prepare( "INSERT INTO `users` (role_id, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)" );
        $addTeacher->bind_param( 'issss', $role, $_POST['new_first_name'], $_POST['new_last_name'], $_POST['new_email'], $_POST['new_password'] );
        $addTeacherResult = $addTeacher->execute();

        $title = $addTeacherResult ? 'Add Success': 'Add Failed';
        $text = $addTeacherResult ? 'New Teacher has been added' : 'Failed to add new teacher';

        if ( $addTeacherResult ) {
            $user_id = $addTeacher->insert_id;
            
            $assignTeacher = $this->mysqli->prepare( "INSERT INTO `school_teachers` (user_id, school_id) VALUES (?, ?)" );
            $assignTeacher->bind_param( 'is', $user_id, $_POST['assigned_school'] );
            $assignTeacher->execute();
        }

        $this->response( [
            'success' => $addTeacherResult,
            'type' => 'add',
            'title' => $title,
            'text' => $text,
        ] );
    }

    public function updateTeacher() {
        $invalid = [];

        if ( ! isset( $_POST['u_first_name'] ) || empty( $_POST['u_first_name'] ) ) {
            $invalid['u_first_name'] = "<b>First name</b> is a required field";
        }

        if ( ! isset( $_POST['u_last_name'] ) || empty( $_POST['u_last_name'] ) ) {
            $invalid['u_last_name'] = "<b>Last name</b> is a required field";
        }

        if ( count( $invalid ) > 0 ) {
            $this->response( [
                'success' => false,
                'type' => 'validation',
                'message' => 'Some form fields are invalid.',
                'data' => $invalid
            ] );
            exit();
        }

        foreach ( $_POST as $key => $value ) {
            $_POST[$key] = $this->mysqli->real_escape_string( $value );
        }

        $updateTeacher = $this->mysqli->prepare( "UPDATE `users` SET `first_name`=?, `last_name`=? WHERE `id`=?" );
        $updateTeacher->bind_param( 'ssi', $_POST['u_first_name'], $_POST['u_last_name'], $_POST['teacher_id'] );
        $updateTeacherResult = $updateTeacher->execute();

        $title = $updateTeacherResult ? 'Update Success': 'Update Failed';
        $text = $updateTeacherResult ? 'Teacher has been updated' : 'Failed to udpate teacher';

        if ( $updateTeacherResult ) {
            $updateAssignedTeacher = $this->mysqli->prepare( "UPDATE `school_teachers` SET `school_id`=? WHERE `user_id`=?" );
            $updateAssignedTeacher->bind_param( 'ii', $_POST['u_assigned_school'], $_POST['teacher_id'] );
            $updateAssignedTeacher->execute();
        }

        $this->response( [
            'success' => $updateTeacherResult,
            'type' => 'add',
            'title' => $title,
            'text' => $text,
        ] );
    }

}
