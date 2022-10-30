<?php

class SM_Controller
{

    private $mysqli, $helpers;

    public function __construct()
    {
        $sm_db = new SM_DB();
        $this->mysqli = $sm_db->sql();
        $this->helpers = new SM_Helpers();
    }

    public function setView( $view ) {
        $view = sprintf( "/views/%s.php", $view );
        return $view;
    }

    public function renderView( $view ) {
        if ( ! $view ) return;

        if ( $view == 'dashboard' || $view == '/' ) {
            $this->dashboardView();
            return;
        }

        if ( $view == 'schools' ) {
            $this->schoolsView();
            return;
        }

        if ( $view == 'teachers' ) {
            $this->teachersView();
            return;
        }

        if ( $view == 'login' ) {
            $this->loginView();
            return;
        }
    }

    public function dashboardView( $view = 'dashboard' ) {
        require_once ROOT_DIR . $this->setView( $view );
    }

    public function schoolsView( $view = 'schools' ) {
        $sm_schools = new SM_Schools();
        $levels = [
            'primary',
            'secondary',
            'tertiary'
        ];

        if ( ! isset( $_GET['school_id'] ) ) {
            $all_schools = $sm_schools->getSchools();

        } else {
            $school = $sm_schools->getSchools( $_GET['school_id'] );
        }

        require_once ROOT_DIR . $this->setView( $view );
    }

    public function teachersView( $view = 'teachers' ) {
        $sm_teachers = new SM_Teachers();
        $sm_schools = new SM_Schools();

        $available_schools = $sm_schools->getSchools();

        if ( ! isset( $_GET['teacher_id'] ) ) {
            $all_teachers = $sm_teachers->getTeachers();

        } else {
            $teachers = $sm_teachers->getTeachers( $_GET['teacher_id'] );
            $assigned_school = $sm_teachers->getTeacherSchool( $_GET['teacher_id'] );
        }

        require_once ROOT_DIR . $this->setView( $view );
    }

    public function loginView( $view = 'login' ) {
        require_once ROOT_DIR . $this->setView( $view );
    }

}