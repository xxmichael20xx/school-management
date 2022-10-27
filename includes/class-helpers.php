<?php

class SM_Helpers
{

    public function __construct()
    {
        
    }

    /**
     * Define die dump
     * 
     * @return Void
     */
    public function dd( $data ) {
        echo "<pre>";
        var_dump( $data );
        echo "</pre>";
    }

}