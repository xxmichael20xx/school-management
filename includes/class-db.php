<?php

class SM_DB {

    public $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );
    }

    /**
     * Defined to create tables
     * 
     * @return Void
     */
    public function create_tables() {
        $users_table = "users";
        $users_sql = "
            CREATE TABLE IF NOT EXISTS `training_school_cms`.`{$users_table}` ( 
                `id` INT(10) NOT NULL AUTO_INCREMENT , 
                `role_id` INT(10) NOT NULL , 
                `first_name` VARCHAR(255) NOT NULL , 
                `last_name` VARCHAR(255) NOT NULL , 
                `email` VARCHAR(255) NOT NULL , 
                `password` VARCHAR(255) NOT NULL , 
                `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , 
                `updated_at` TIMESTAMP NULL DEFAULT NULL , 
                `deleted_at` TIMESTAMP NULL DEFAULT NULL , 
                PRIMARY KEY (`id`), 
                UNIQUE (`email`)
            ) ENGINE = InnoDB;
        ";
        $this->mysqli->query( $users_sql );

        $roles_table = "roles";
        $roles_sql = "
            CREATE TABLE `training_school_cms`.`{$roles_table}` ( 
                `id` INT(10) NOT NULL AUTO_INCREMENT , 
                `name` VARCHAR(255) NOT NULL , 
                `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , 
                `updated_at` TIMESTAMP NULL DEFAULT NULL , 
                `deleted_at` TIMESTAMP NULL DEFAULT NULL , 
                PRIMARY KEY (`id`)
            ) ENGINE = InnoDB;
        ";
        $this->mysqli->query( $roles_sql );

        $schools_table = "schools";
        $schools_sql = "
            CREATE TABLE `training_school_cms`.`{$schools_table}` ( 
                `id` INT(10) NOT NULL AUTO_INCREMENT , 
                `name` VARCHAR(255) NOT NULL , 
                `address` TEXT NOT NULL , 
                `level` VARCHAR(255) NOT NULL , 
                `is_active` INT(1) NOT NULL DEFAULT '1' , 
                `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , 
                `updated_at` TIMESTAMP NULL DEFAULT NULL , 
                `deleted_at` TIMESTAMP NULL DEFAULT NULL , 
                PRIMARY KEY (`id`), UNIQUE (`name`)
            ) ENGINE = InnoDB;
        ";
        $this->mysqli->query( $schools_sql );

        $school_teachers_table = "school_teachers";
        $school_teachers_sql = "
            CREATE TABLE `training_school_cms`.`{$school_teachers_table}` ( 
                `id` INT(10) NOT NULL AUTO_INCREMENT , 
                `user_id` INT(10) NOT NULL , 
                `school_id` INT(10) NOT NULL , 
                `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , 
                `updated_at` TIMESTAMP NULL DEFAULT NULL , 
                `deleted_at` TIMESTAMP NULL DEFAULT NULL , 
                PRIMARY KEY (`id`)
            ) ENGINE = InnoDB;
        ";
        $this->mysqli->query( $school_teachers_sql );
    }

    /**
     * Defined to make the mysqli accessible
     * 
     * @return mysqli
     */
    public function sql() {
        return $this->mysqli;
    }

    /**
     * Calls the mysqli query with return fetch all
     * 
     * @return mysqli_results
     */
    public function init_query( $sql ) {
        $result = $this->mysqli->query( $sql );
        return $result->fetch_all( MYSQLI_ASSOC );
    }

}