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
  'POST', '/sign_in', array('IndexController', 'sign_in')
);
$routing_map[] = array(
  'GET', '/signout', array('IndexController', 'signout')
);
$routing_map[] = array(
  'GET', '/upload', array('SlidesController', 'upload')
);
$routing_map[] = array(
  'GET', '/question', array('QuestionsController', 'show_page')
);
$routing_map[] = array(
  'POST', '/question/answer', array('QuestionsController', 'answer')
);
$routing_map[] = array(
  'GET', '/question/show', array('QuestionsController', 'show')
);
$routing_map[] = array(
  'POST', '/slides/create', array('SlidesController', 'create')
);
$routing_map[] = array(
  'GET', '/slides/:id', array('SlidesController', 'show')
);
$routing_map[] = array(
  'POST', '/users/create', array('UsersController', 'create')
);
$routing_map[] = array(
  'GET', '/users/:username', array('UsersController', 'show')
);
$routing_map[] = array(
  'POST', '/comments/create', array('CommentsController', 'create')
);

$router = new Router($routing_map);
$request = new Request($_SERVER, $_REQUEST);
$response = $router->match($request);

// Timezoneの設定 (面倒なのでAsia/Tokyoオンリー)
date_default_timezone_set('Asia/Tokyo');

$controller = $response->controller;
$method = $response->controller_method;

$content = call_user_func($controller->$method(), $response->params);
header('Content-Type: text/html; charset=utf-8');
header('Content-Length: ' . strlen($content));
echo $content;
