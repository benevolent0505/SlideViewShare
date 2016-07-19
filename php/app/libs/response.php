<?php
namespace SlideViewShare;

class Response {
  public $method;
  public $split_path;
  public $param_pos;
  public $params;
  public $controller;
  public $controller_method;

  public function __construct($method, $split_path, $param_pos, $controller_name, $controller_method) {
    $this->method = $method;
    $this->split_path = $split_path;
    $this->param_pos = $param_pos;
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
        if (isset($this->param_pos[$index])) {
          continue;
        } else {
          return false;
        }
      }
    }

    return true;
  }

  public function setParams(array $split_path, array $request_params) {
    $method = $this->method;

    if ($method == 'GET') {
      foreach ($split_path as $index => $path) {
        if ($this->split_path[$index] !== $path) {
          $this->params = $path;
        }
      }
    } else if ($method == 'POST') {
      $this->params = $request_params;
    }
  }

  public static function create($method, $path, $controller_name, $controller_method) {
    $split_path = self::parsePath($path);
    $param_pos = self::setParamPos($split_path);

    return new Response($method, $split_path, $param_pos, $controller_name, $controller_method);
  }

  public static function parsePath($path) {
    $tmp_arr = explode("/", $path);
    array_shift($tmp_arr);

    return $tmp_arr;
  }

  public static function setParamPos($split_path) {
    $param_pos = array();
    foreach ($split_path as $index => $path) {
      if (strpos($path, ':') !== false) {
        $param_pos[$index] = substr($path, 1);
      }
    }

    return $param_pos;
  }
}
