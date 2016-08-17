<?php
namespace SlideViewShare\models;

require_once __DIR__ . "/../libs/data_manager.php";

use SlideViewShare\libs\DataManager as DataManager;

class Answer {

  public $id;
  public $slide_id;
  public $is_design;
  public $is_content;
  public $is_user;
  public $opinion;

  public $file;

  public function __construct($slide_id, $is_design, $is_content, $is_user, $opinion) {
    $this->slide_id = $slide_id;
    $this->is_design = $is_design;
    $this->is_content = $is_content;
    $this->is_user = $is_user;
    $this->opinion = $opinion;

    $this->file = new DataManager('answers', 'csv');
  }

  public function save() {
    $this->id = DataManager::getLastId('answers') + 1;
    $write_data = array($this->slide_id, $this->is_design, $this->is_content, $this->is_user, $this->opinion);
    $this->file->save($write_data);
  }

    /**
   * @return User[] | null
   */
  public static function all() {
    $value_arr = DataManager::all('answers');

    $answers = array();
    if (isset($value_arr)) {
      foreach ($value_arr as $row) {
        $answer = new Answer($row['slide_id'], $row['is_design'], $row['is_content'], $row['is_user'], $row['opinion']);
        $answer->id = $row['id'];

        $answers[] = $answer;
      }

      return $answers;
    }

    return null;
  }
}
