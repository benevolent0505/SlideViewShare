<?php

require_once __DIR__ . "/../../vendor/autoload.php";

function renderResponse($filename, array $params=array()) {
  $loader = new \Twig_Loader_Filesystem(__DIR__ . "/../views");
  $twig = new \Twig_Environment($loader);

  return $twig->render($filename, $params);
}
