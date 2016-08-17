<?php
namespace SlideViewShare\controllers;

require_once __DIR__ . "/../helpers/functions.php";

require_once dirname(__FILE__) . "/../models/User.php";
require_once dirname(__FILE__) . "/../models/Slide.php";
require_once dirname(__FILE__) . "/../models/Answer.php";

use SlideViewShare\models\User as User;
use SlideViewShare\models\Slide as Slide;
use SlideViewShare\models\Answer as Answer;

class QuestionsController {

  public function show_page() {
    return function () {
      $users = User::all();
      $slides = Slide::all();

      // セッション認証
      session_name('SLIDEVIEWSHARESESSID');
      session_start();
      $session_user = null;
      if (isset($_SESSION['username'])) {
        $session_user = User::find($_SESSION['username']);
      }

      return renderResponse('question_page.tpl.html',
       array('session_user' => $session_user, 'users' => $users, 'slides' => $slides));
    };
  }

  public function answer() {
    return function (array $params) {
      $slide_id = $params['slide'];
      if (isset($params['design'])) {
        $is_design = $params['design'];
      }
      if (isset($params['content'])) {
        $is_content = $params['content'];
      }
      if (isset($params['user'])) {
        $is_user = $params['user'];
      }
      if (isset($params['opinion'])) {
        $opinion_text = $params['opinion'];
      }

      $answer = new Answer($slide_id, $is_design, $is_content, $is_user, $opinion_text);
      $answer->save();

      header('Location: ../question');
      exit;
    };
  }

  public function show() {
    return function () {
    };
  }
}
