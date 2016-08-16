<?php
namespace SlideViewShare\models;

require_once __DIR__ . "/../libs/data_manager.php";
require_once dirname(__FILE__) . "/../models/User.php";
use SlideViewShare\libs\DataManager as DataManager;
use SlideViewShare\models\User as User;

class Comment {

  public $id;
  public $user_id;
  public $slide_id;
  public $content;
  public $created_at;

  public $file;

  /**
   * @param string  $user_id
   * @param string  $slide_id
   * @param string  $content
   * @param integer $created_at
   */
  public function __construct($user_id, $slide_id, $content, $created_at) {
    $this->user_id = $user_id;
    $this->slide_id = $slide_id;
    $this->content = $content;
    $this->created_at = $created_at;

    $this->file = new DataManager('comments', 'csv');
  }

  public function save() {
    $this->id = DataManager::getLastId('comments') + 1;
    $write_data = array($this->id, $this->user_id, $this->slide_id, $this->content, $this->created_at);
    $this->file->save($write_data);
  }

  public function getUsername() {
    if ($this->user_id == 0) {
      return 'Anonymous';
    } else {
      return User::findBy(array('id', $this->user_id))->username;
    }
  }

  /**
   * @param array $value_tuple (Ex: array('slide_id', $slide_id))
   * @return array
   */
  public static function findAllBy(array $value_tuple) {
    $tmp_arr = DataManager::findAll('comments', $value_tuple);

    $comments = array();
    foreach ($tmp_arr as $row) {
      $comment = new Comment($row['user_id'], $row['slide_id'], $row['content'], $row['created_at']);
      $comment->id = $row['id'];

      $comments[] = $comment;
    }

    return $comments;
  }
}
