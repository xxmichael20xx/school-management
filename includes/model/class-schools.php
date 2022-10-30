<?php

class SM_Schools
{

    private $mysqli, $table, $helpers;

    public function __construct()
    {
        $this->table = 'schools';
        $this->mysqli = new SM_DB();
        $this->helpers = new SM_Helpers();
    }

    public function getSchools( $school_id = NULL ) {
        $sql = "SELECT * FROM `{$this->table}`";
        if ( $school_id ) $sql .= " WHERE `id`=?";

        $schools = $this->mysqli->sql()->prepare( $sql );
        if ( $school_id ) $schools->bind_param( 's', $school_id );

        $schools->execute();
        return $schools->get_result();
    }

    public function getAssignTeachers( $school_id ) {
        $sql = "SELECT * FROM `school_teachers` WHERE `school_id`=?";

        $schools = $this->mysqli->sql()->prepare( $sql );
        $schools->bind_param( 'i', $school_id );

        $schools->execute();
        return $schools->get_result();
    }

}