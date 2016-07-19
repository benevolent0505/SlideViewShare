<?php
namespace SlideViewShare;

require_once __DIR__ . '/app/libs/request.php';
require_once __DIR__ . '/app/libs/router.php';

error_reporting(-1);
ini_set('display_errors', 'On');

$routing_map = array();
$routing_map[] = array(
  'GET', '/', array('IndexController', 'show'),
);
$routing_map[] = array(
  'GET', '/signup', array('IndexController', 'signup')
);
$routing_map[] = array(
  'GET', '/signin', array('IndexController', 'signin')
);
$routing_map[] = array(
  'POST', '/users/create', array('UsersController', 'create')
);
$routing_map[] = array(
  'GET', '/users/:username', array('UsersController', 'show')
);

$router = new Router($routing_map);
$request = new Request($_SERVER, $_REQUEST);
$response = $router->match($request);

$controller = $response->controller;
$method = $response->controller_method;

$content = call_user_func($controller->$method(), $response->params);
header('Content-Type: text/html; charset=utf-8');
header('Content-Length: ' . strlen($content));
echo $content;
