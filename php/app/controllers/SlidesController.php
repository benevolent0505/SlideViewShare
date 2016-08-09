<?php
namespace SlideViewShare\controllers;

require_once __DIR__ . "/../helpers/functions.php";
require_once dirname(__FILE__) . "/../models/User.php";
require_once dirname(__FILE__) . "/../models/Slide.php";
use SlideViewShare\models\User as User;
use SlideViewShare\models\Slide as Slide;

class SlidesController {

  public function show() {
    return function ($slide_id) {
      $slide = Slide::find($slide_id);
      $presenter = User::findBy(array('id', $slide->user_id));

      // セッション処理
      session_name('SLIDEVIEWSHARESESSID');
      session_start();
      $session_user = null;
      if (isset($_SESSION['username'])) {
        $session_user = User::find($_SESSION['username']);
      }

      return renderResponse('show_slide.tpl.html', array('session_user' => $session_user,
       'slide' => $slide, 'presenter' => $presenter));
    };
  }

  public function create() {
    return function ($params) {
      if (isset($_FILES['slide']['error']) && is_int($_FILES['slide']['error'])) {
        try {
          switch ($_FILES['slide']['error']) {
            case UPLOAD_ERR_OK: // OK
              break;
            case UPLOAD_ERR_NO_FILE:   // ファイル未選択
              throw new RuntimeException('ファイルが選択されていません');
            case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
            case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過
              throw new RuntimeException('ファイルサイズが大きすぎます');
            default:
              throw new RuntimeException('その他のエラーが発生しました');
          }

          // TODO: $_FILES['upfile']['type']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする必要がある
          if ($_FILES['slide']['type'] != 'application/pdf') {
            throw new RuntimeException('画像形式が未対応です');
          }

          $upload_dir = __DIR__ . "/../../db/slides/";
          $path = sprintf($upload_dir . "%s.%s", sha1_file($_FILES['slide']['tmp_name']), 'pdf');

          $tmp = explode('/', $_SERVER['SCRIPT_NAME']);
          array_pop($tmp);
          $p = implode('/', $tmp);
          $abs_path = 'http://' . $_SERVER['HTTP_HOST'] . $p . '/db/slides/' .
           sha1_file($_FILES['slide']['tmp_name']) . '.pdf';
          $result = move_uploaded_file($_FILES['slide']['tmp_name'], $path);

          if (!$result) {
            throw new RuntimeException('ファイル保存時にエラーが発生しました');
          }

          chmod($path, 0644);

          // スライドのメタデータ登録処理
          $title = array_shift($params);
          $description = array_shift($params);
          $user_id = array_shift($params);

          $slide = new Slide($user_id, $title, $description, $abs_path);
          $slide->save();

          header('Location: ./' . $slide->id);
          exit;
        } catch (Exception $e) {
          // TODO: 例外処理を書く
        }
      }
    };
  }

  public function upload() {
    return function () {
      // セッション処理
      session_name('SLIDEVIEWSHARESESSID');
      session_start();
      if (isset($_SESSION['username'])) {
        $session_user = User::find($_SESSION['username']);
      } else {
        header('Location: ./signin');
        exit;
      }

      return renderResponse('upload.tpl.html', array('session_user' => $session_user));
    };
  }
}
