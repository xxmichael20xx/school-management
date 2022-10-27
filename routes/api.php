<?php

class Api
{
    
    public $request, $path;

    public function __construct()
    {
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
    }

}
