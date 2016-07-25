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

      // セッション処理
      session_name('SLIDEVIEWSHARESESSID');
      session_start();
      $session_user = null;
      if (isset($_SESSION['username'])) {
        $session_user = User::find($_SESSION['username']);
      }

      return renderResponse('show.tpl.html', array('session_user' => $session_user, 'user' => $user));
    };
  }

  public function create() {
    return function(array $params) {
      $username = array_shift($params);
      $password = array_shift($params);
      $password_confirmation = array_shift($params);

      // パスワード不一致の時の処理
      if ($password != $password_confirmation) {
        // flash message送信の実装
        header('Location: ../signup');
        exit;
      }

      $user = new User($username, $password);
      $user->save();

      // セッション処理
      session_name('SLIDEVIEWSHARESESSID');
      session_start();
      session_regenerate_id(true);
      $_SESSION['username'] = $username;

      header('Location: ../');
      exit;
    };
  }

  public function destory() {
  }
}
