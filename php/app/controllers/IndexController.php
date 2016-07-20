<?php
namespace SlideViewShare\controllers;

require_once __DIR__ . "/../helpers/functions.php";

class IndexController {

  public function __construct(){
  }

  public function show() {
    return function () {
      return renderResponse('index.tpl.html');
    };
  }

  public function signup() {
    return function () {
      return renderResponse('signup.tpl.html');
    };
  }

  public function signin() {
    return function () {
      return renderResponse('signin.tpl.html');
    };
  }

  public function logout() {
    return function (array $params) {
      session_start();

      session_unset();
      header('Location: ./');
      exit();
    };
  }
}
