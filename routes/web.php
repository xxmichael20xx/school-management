<?php

class Web {

    public $request, $path, $controller;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_URI'];
        $this->path = parse_url( $this->request )['path'];
        $this->controller = new SM_Controller();
    }

    /**
     * Render the page view
     * 
     * @return Void
     */
    public function renderDisplay() {
        has_session();
        $this->controller->renderView( $this->checkRoute() );
    }

    /**
     * Check the current URL path to display the page
     * 
     * @return String
     */
    public function checkRoute() {
        switch ( $this->path ) {
            case '/':
            case 'dashboard':
                $view = "dashboard";
                break;

            case '/schools':
                $view = "schools";
                break;

            case '/teachers':
                $view = "teachers";
                break;

            case '/login':
                $view = "login";
                break;
            
            default:
                $view = "404";
                break;
        }

        return $view;
    }

}
