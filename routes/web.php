<?php

class Web {

    public $request, $path;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_URI'];
        $this->path = parse_url( $this->request )['path'];
    }

    public function checkRoute() {
        ob_start();

        

        echo ob_get_contents();
    }

}
