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

    /**
     * Define custom console
     * 
     * @param data Data to log on dev console
     * @return Void
     */
    public function console( $data ) {
        echo sprintf( "<script>console.log( %s )</script>", json_encode( $data ) );
    }

}