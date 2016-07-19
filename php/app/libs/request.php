<?php
namespace SlideViewShare;

class Request {
  public $request_method;
  public $params;
  public $request_params;
  public $split_path;

  public function __construct(array $server, array $request) {
    $this->request_method = $server['REQUEST_METHOD'];
    $this->split_path = $this->parsePathInfo($server['PATH_INFO']);
    $this->params = $this->parseQuery($server['QUERY_STRING']);
    $this->request_params = $request;
  }

  public function getMethod() {
    return $this->request_method;
  }

  public function getSplitPath() {
    return $this->split_path;
  }

  public function getParams() {
    return $this->params;
  }

  private function parsePathInfo($path_info) {
    $tmp_arr = explode("/", $path_info);
    array_shift($tmp_arr);

    return $tmp_arr;
  }

  private function parseQuery($query_string) {
    $querys = explode("&", $query_string);

    $params = array_map(function ($query) {
      return explode("=", $query);
    }, $querys);

    return $params;
  }
}
