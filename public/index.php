<?php
try {
    $secureName = preg_replace('/([^a-z0-9]+)/','_',ltrim(strtolower($_SERVER['SERVER_NAME']),'w.'));
    $securePath = dirname(__FILE__).'/../protected/config/domains/'.$secureName.'.json';
    if (!file_exists($securePath)) {
        print 'Secure file is missed';
        return;
    }
    $secure = json_decode(file_get_contents($securePath),1);
    $config = dirname(__FILE__) . '/../protected/config/main.php';
    $staticConfig = dirname(__FILE__) . '/../protected/config/static_config.php';

    $config = require_once $config;
    $staticConfig = require_once $staticConfig;
    require_once dirname(__FILE__) . '/../protected/merge.php';

    $mergedConfig = array_merge_config($staticConfig, $config);
    include '../vendor/autoload.php';

    $url = explode('?', ltrim($_SERVER['REQUEST_URI'], '/'), 2);
    $url = explode('/', $url[0]);
    $controller = (isset($url[0]) && $url[0] ? $url[0] : 'index');
    $action = (isset($url[1]) ? $url[1] : 'index');

    $controllerClass = ucfirst($controller) . 'ControllerQueue';
    $actionMethod = 'action' . ucfirst($action);
    if (!class_exists($controllerClass)) {
        throw new Exception('Controller not found: '.$controllerClass);
    }
    $controller = new $controllerClass($mergedConfig);
    if (!method_exists($controller, $actionMethod)) {
        throw new Exception('Action not found:'.$actionMethod);
    }

    $controller->$actionMethod();
} catch (Exception $e) {
    header('HTTP/1.0 500 Internal Server Error');
    header('Error:'.$e->getMessage());
    print $e->getMessage();

}