<?php
try {
    $config = dirname(__FILE__) . '/config/main.php';
    $staticConfig = dirname(__FILE__) . '/config/static_config.php';

    $config = require_once $config;
    $staticConfig = require_once $staticConfig;
    require_once dirname(__FILE__) . '/merge.php';

    $mergedConfig = array_merge_config($staticConfig, $config);

    include '../autoload.php';

    $params = array();
    foreach (array_slice($argv,1) as $arg) {
        if (!in_array($arg,array(__FILE__,pathinfo(__FILE__,PATHINFO_FILENAME)))) {
            if (substr($arg,0,2)=='--') {
                $arg = explode('=',ltrim($arg,'-'),2);
                $params[$arg[0]] = $arg[1];
            } else {
                $params['command'] = $arg;
            }
        }
    }
    if (!isset($params['command'])) {
        throw new Exception('Command is missed');
    }

    $controllerClass = ucfirst($params['command']) . 'Command';
    if (!class_exists($controllerClass)) {
        throw new Exception('Command class not found:'.$controllerClass);
    }
    $controller = new $controllerClass($mergedConfig, $params);

    $controller->run();
} catch (Exception $e) {
    print "Exited with error:\n";
    print $e->getMessage();

}