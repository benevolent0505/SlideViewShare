<?php
namespace SlideViewShare\controllers;

require_once __DIR__ . "/../helpers/functions.php";
require_once dirname(__FILE__) . "/../models/User.php";
use SlideViewShare\models\User as User;
use SlideViewShare\models\Slide as Slide;

class UsersController {

  public function __construct() {
  }

  public function show() {
    return function ($username) {
      $user = User::find($username);
      $slides = Slide::findAllBy($user->id);

      // セッション処理
      session_name('SLIDEVIEWSHARESESSID');
      session_start();
      $session_user = null;
      if (isset($_SESSION['username'])) {
        $session_user = User::find($_SESSION['username']);
      }

      return renderResponse('show.tpl.html',
       array('session_user' => $session_user, 'user' => $user, 'slides' => $slides));
    };
  }

  public function create() {
    return function(array $params) {
      $username = array_shift($params);
      $password = array_shift($params);
      $password_confirmation = array_shift($params);

      // usernameが英数字以外の時の処理
      if (!ctype_alnum($username)) {
        header('Location: ../signup');
        exit;
      }
      // usernameが被っていた場合の処理
      if (User::find($username) != null) {
        header('Location: ../signup');
        exit;
      }
      // パスワードの長さが短い時の処理
      if (strlen($password) < 7) {
        header('Location: ../signup');
        exit;
      }
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
