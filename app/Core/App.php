<?php

class App {
    protected $controller = 'Auth';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // Controller
        if(isset($url[0])) {
            if(file_exists('../app/Controllers/' . ucfirst($url[0]) . '.php')) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }
        }

        require_once '../app/Controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Method
        if(isset($url[1])) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Params
        if(!empty($url)) {
            $this->params = array_values($url);
        }

        // Run Controller & Method, sending params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        } else {
            // Fallback for PHP Built-in Server (or when .htaccess is ignored)
            $request_uri = $_SERVER['REQUEST_URI'];
            // Remove query string
            if (($pos = strpos($request_uri, '?')) !== false) {
                $request_uri = substr($request_uri, 0, $pos);
            }
            // Remove script name if present (e.g. /index.php/auth/login)
            $script_name = $_SERVER['SCRIPT_NAME'];
            if (strpos($request_uri, $script_name) === 0) {
                $request_uri = substr($request_uri, strlen($script_name));
            }
            
            $url = trim($request_uri, '/');
            if(!empty($url)) {
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                return $url;
            }
        }
        return [];
    }
}
