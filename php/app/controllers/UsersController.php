<?php
namespace SlideViewShare\controllers;

require_once __DIR__ . "/../helpers/functions.php";
require_once dirname(__FILE__) . "/../models/User.php";
use SlideViewShare\models\User as User;

class UsersController {

  public function __construct() {
  }

  public function show() {
    return function ($username) {
      $user = User::find($username);

      return renderResponse('show.tpl.html', array('user' => $user));
    };
  }

  public function create() {
    return function(array $params) {
      $username = array_shift($params);
      $password = array_shift($params);
      $password_confirmation = array_shift($params);

      // TODO: パスワード不一致の時の処理
      if ($password != $password_confirmation) {}

      $user = new User($username, $password);
      $user->save();

      header('Location: ../');
      exit;
    };
  }

  public function destory() {
  }
}
