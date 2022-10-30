<?php

class SM_Users
{

    private $mysqli, $table, $helpers;

    public function __construct()
    {
        $this->mysqli = new SM_DB();
        $this->table = 'users';
        $this->helpers = new SM_Helpers();
    }

    public function getUsers( $user_id = NULL ) {
        $query = "
            SELECT u.*, r.name as role FROM `users` as `u`
            JOIN `roles` as `r`
            ON u.role_id=r.id
        ";

        if ( $user_id ) {
            $query .= " WHERE u.id={$user_id}";
        }

        $users = $this->mysqli->init_query( $query );
        return $users;
    }

}