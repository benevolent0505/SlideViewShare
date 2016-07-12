<?php
namespace SlideViewShare;

require_once __DIR__ . '/app/libs/request.php';
require_once __DIR__ . '/app/libs/router.php';

$routing_map = array();
$routing_map[] = array(
  'GET', '/', array('IndexController', 'show'),
);
$routing_map[] = array(
  'GET', '/signup', array('IndexController', 'signup')
);

$router = new Router($routing_map);
$request = new Request($_SERVER);
$response = $router->match($request);

$controller = $response->controller;
$method = $response->controller_method;

$content = call_user_func($controller->$method());

echo $content;
