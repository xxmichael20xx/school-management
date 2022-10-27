<?php

class Web {

    public $request, $path;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_URI'];
        $this->path = parse_url( $this->request )['path'];
    }

    /**
     * Render the page view
     * 
     * @return Void
     */
    public function renderDisplay() {
        ob_start();

        $view = sprintf( "/views/%s.php", $this->checkRoute()  );
        require_once ROOT_DIR . $view;

        exit();
        echo ob_get_contents();
    }

    /**
     * Check the current URL path to display the page
     * 
     * @return String
     */
    public function checkRoute() {
        switch ( $this->path ) {
            case '/':
                $view = "dashboard";
                break;

            case '/users':
                $view = "users";
                break;
            
            default:
                $view = "404";
                break;
        }

        return $view;
    }

}
