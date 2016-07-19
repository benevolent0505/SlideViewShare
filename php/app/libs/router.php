<?php
namespace SlideViewShare;

require_once 'response.php';

class Router {

  public $responses;

  public function __construct(array $routing_map) {
    foreach ($routing_map as $key => $value) {
      $this->setResponse($value);
    }
  }

  public function match($request) {
    foreach ($this->responses as $response) {
      if ($response->isMatch($request)) {
        $response->setParams($request->split_path, $request->request_params);
        return $response;
      }
    }

    return null;
  }

  private function setResponse($request_tuple) {
    $method = array_shift($request_tuple);
    $path = array_shift($request_tuple);
    list($controller_name, $controller_method) = array_shift($request_tuple);

    $this->responses[] = Response::create($method, $path, $controller_name, $controller_method);
  }
}
