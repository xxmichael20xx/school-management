<?php

class SM_Teachers
{

    private $mysqli, $table, $helpers;

    public function __construct()
    {
        $this->table = 'users';
        $this->mysqli = new SM_DB();
        $this->helpers = new SM_Helpers();
    }

    public function getTeachers( $teacher_id = NULL ) {
        $sql = "SELECT u.*, r.name AS role FROM `{$this->table}` AS `u`
                JOIN `roles` AS r
                ON `u`.`role_id`=`r`.`id`
                WHERE `u`.`role_id`=2
                ";

        if ( $teacher_id ) $sql .= " AND `u`.`id`=?";

        $teachers = $this->mysqli->sql()->prepare( $sql );
        if ( $teacher_id ) $teachers->bind_param( 's', $teacher_id );

        $teachers->execute();
        return $teachers->get_result();
    }

    public function getTeacherSchool( $teacher_id ) {
        $sql = "SELECT `s`.`name`, `st`.`school_id` FROM `schools` AS `s`
                JOIN `school_teachers` AS `st`
                WHERE `st`.`user_id`=?
                ";

        $teachers = $this->mysqli->sql()->prepare( $sql );
        $teachers->bind_param( 'i', $teacher_id );

        $teachers->execute();
        return $teachers->get_result();
    }

}