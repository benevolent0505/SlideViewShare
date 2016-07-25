<?php
namespace SlideViewShare\controllers;

require_once __DIR__ . "/../helpers/functions.php";
require_once __DIR__ . "/../helpers/users_helper.php";
require_once dirname(__FILE__) . "/../models/User.php";
use SlideViewShare\models\User as User;

class IndexController {

  public function __construct(){
  }

  public function show() {
    return function () {

      // セッション認証
      session_name('SLIDEVIEWSHARESESSID');
      session_start();
      $session_user = null;
      if (isset($_SESSION['username'])) {
        $session_user = User::find($_SESSION['username']);
      }

      return renderResponse('index.tpl.html', array('session_user' => $session_user));
    };
  }

  public function signup() {
    return function () {
      return renderResponse('signup.tpl.html');
    };
  }

  // GETでSign inページを表示するときの処理
  public function signin() {
    return function () {
      return renderResponse('signin.tpl.html');
    };
  }

  // POST処理
  public function sign_in() {
    return function (array $params) {
      $username = array_shift($params);
      $password = array_shift($params);

      $user = User::find($username);
      if ($user != null) {
        if (my_password_verify($password, $user->password)) {
          // セッション処理
          session_name('SLIDEVIEWSHARESESSID');
          session_start();
          session_regenerate_id(true);
          $_SESSION['username'] = $username;

          header('Location: ./');
          exit;
        }
      }

      // 認証失敗
      header('Location: ./signin');
      exit;
    };
  }

  public function signout() {
    return function () {

      // セッション破棄
      session_name('SLIDEVIEWSHARESESSID');
      session_start();
      if (isset($_SESSION['username'])) {
        $_SESSION = array();
        setcookie(session_name(), '', 1);
        session_destroy();
      }

      header('Location: ./');
      exit();
    };
  }
}
