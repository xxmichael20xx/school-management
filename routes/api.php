<?php

class Api
{
    
    public $request, $path;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_URI'];
        $this->path = parse_url( $this->request )['path'];
    }

}