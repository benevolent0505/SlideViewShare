<?php
namespace SlideViewShare;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/libs/request.php';
require_once __DIR__ . '/app/libs/router.php';

// Requestを受けとり
dumpArray($_SERVER);

$request = new Request($_SERVER);

$routing_map = array(
  array('GET', '/', ['IndexController', 'index']),
);

$loader = new \Twig_Loader_Filesystem(__DIR__ . '/app/views');
$twig = new \Twig_Environment($loader);

echo $twig->render('index.tpl.html');



function dumpArray(array $arr) {
  foreach($arr as $key => $value) {
      var_dump($key);
      echo(" : ");
      var_dump($value);
      echo("<br>");
  }
}
