<?php
namespace SlideViewShare\controllers;

require_once dirname(__FILE__) . "/../models/Comment.php";

use SlideViewShare\models\User as User;
use SlideViewShare\models\Slide as Slide;
use SlideViewShare\models\Comment as Comment;

class CommentsController {

  public function create() {
    return function (array $params) {
      $user_id = array_shift($params);
      $slide_id = array_shift($params);
      $content = array_shift($params);

      if (!is_numeric($user_id) || !is_numeric($slide_id) || !isset($content)) {
        header('HTTP', true, 400);
        exit;
      }

      if ($user_id != 0) {
        $user = User::findBy(array('id', $user_id));
        if (!isset($user)) {
          header('HTTP', true, 400);
          exit;
        }
      }

      $slide = Slide::findBy(array('id', $slide_id));
      if (!isset($slide)) {
        header('HTTP', true, 400);
        exit;
      }

      $comment = new Comment($user_id, $slide_id, $content, time());
      $comment->save();

      $user = User::findBy(array('id', $user_id));
      $username = $user_id == 0 ? 'Anonymous' : $user->username;
      echo json_encode(array(
        'id' => $comment->id,
        'username' => $username,
        'content' => $comment->content,
        'created_at' => $comment->created_at
      ));
      exit;
    };
  }
}
