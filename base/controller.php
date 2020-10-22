<?
class Controller {

    public $model;
    public $view;

    function __construct()
    {
        $this->view = new View();
        $this->model = new Model();
    }

    function getRoute()
    {
        $action_name = 'index';
        $model_name = 'main';

        $routes = explode('/', $_SERVER['REQUEST_URI']);
        $prop = '';

        if ( !empty($routes[2]) )
        {
            $controller_name = $routes[2];
        }

        if ( !empty($routes[3]) )
        {
            $prop = $routes[3];
        }

        $model_name = 'Model_'.$controller_name;
        $controller_name = 'Controller_'.$controller_name;

        $model_file = strtolower($model_name).'.php';
        $controller_file = strtolower($controller_name).'.php';
        $model_path = 'test/base/models/'.$model_file;
        $controller_path = 'controllers/'.$controller_file;

        //TODO: file_exists do not working, do not know why)
        if(file_exists($model_path) || true)
        {
            include "models/".$model_file;
        }
        else
        {
            Controller::ErrorPage404();
        }

        //TODO: file_exists do not working, do not know why)
        if(file_exists($controller_path) || true)
        {
            include "controllers/".$controller_file;

            $controller = new $controller_name;
            $controller->action_index($prop);

        }
        else
        {
            Controller::ErrorPage404();
        }
    }

    function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/2.0 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }

    function action_index()
    {
    }
}