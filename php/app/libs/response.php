<?php
namespace SlideViewShare;

class Response {
  public $method;
  public $split_path;
  public $params;
  public $controller;
  public $controller_method;

  public function __construct($method, $split_path, $params, $controller_name, $controller_method) {
    $this->method = $method;
    $this->split_path = $split_path;
    $this->params = $params;
    $this->controller_name = $controller_name;
    $this->controller_method = $controller_method;

    require_once __DIR__ . "/../controllers/$controller_name.php";
    $controller = "\\SlideViewShare\\controllers\\$controller_name";
    $this->controller = new $controller();
  }

  public function isMatch($request) {
    $request_length = count($request->split_path);

    if ($request->request_method !== $this->method ||
        $request_length !== count($this->split_path)) return false;

    foreach ($request->split_path as $index => $path) {
      if ($this->split_path[$index] !== $path) {
        return false;
      }
    }

    return true;
  }

  public static function create($method, $path, $controller_name, $controller_method) {
    $split_path = self::parsePath($path);
    $params = self::setParams($split_path);

    return new Response($method, $split_path, $params, $controller_name, $controller_method);
  }

  public static function parsePath($path) {
    $tmp_arr = explode("/", $path);
    array_shift($tmp_arr);

    return $tmp_arr;
  }

  public static function setParams($split_path) {
    $params = array();
    foreach ($split_path as $index => $path) {
      if (strpos($path, ':') !== false) {
        $params[] = array($index => substr($path, 1));
      }
    }

    return $params;
  }
}
