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
    return function (array $params) {
      try {
        // $_FILES内の変数の異常時
        if (!isset($_FILES['slide']['error']) || !is_int($_FILES['slide']['error'])) {
          throw new RuntimeException("ファイルアップロードに失敗しました");
        }

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

        $filename = time() . sha1_file($_FILES['slide']['tmp_name']); // ファイル名の衝突を防ぐ為、エポックタイムを前に足す
        $root_path;
        // 本当はホスト名の自動判定は危険なのでやめた方がいい
        // note: http://blog.a-way-out.net/blog/2015/11/06/host-header-injection/
        if (isset($_SERVER['SERVER_ADDR'])) {
          $pattern = "/^\/~[a-z_][a-z0-9_]{0,30}\//";  // /~username/とマッチするか
          if (preg_match($pattern, $_SERVER['REQUEST_URI'], $matches)) {
            $root_path = 'http://' . $_SERVER['SERVER_ADDR'] . $matches[0];
          } else {
            // マッチしない場合はuser_dir環境化ではないとして、hostnameをルートにする
            $root_path = 'http://' . $_SERVER['SERVER_ADDR'] . '/';
          }
        } else {
          // 処理が思いつかないので取り敢えず例外を出しておく
          throw new RuntimeException('その他のエラーが発生しました');
        }

        // スライドファイルの保存
        $upload_dst = __DIR__ . "/../../db/slides/";
        $path = sprintf($upload_dst . "%s.%s", $filename, 'pdf');
        $result = move_uploaded_file($_FILES['slide']['tmp_name'], $path);
        if (!$result) {
          throw new RuntimeException('ファイル保存時にエラーが発生しました');
        }
        chmod($path, 0644);
        $file_path = $root_path . 'db/slides/' . $filename . '.pdf';

        // サムネイルの保存
        $create_dst = __DIR__ . "/../../db/thumbs";
        $path = sprintf($create_dst . "%s.%s", $filename, 'png');
        $thumb_path = '';

        // スライドデータの保存処理
        $title = array_shift($params);
        $description = array_shift($params);
        $user_id = array_shift($params);

        $slide = new Slide($user_id, $title, $description, $file_path, $thumb_path);
        $slide->save();

        header('Location: ./' . $slide->id);
      } catch (Exception $e) {
        // TODO: flash messageに例外のメッセージを表示させる
        // TODO: フォームの値入力時のものに戻す
        header('Location: ../upload');
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
