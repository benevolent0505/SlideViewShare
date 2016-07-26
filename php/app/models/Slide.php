<?php
namespace SlideViewShare\models;

require_once __DIR__ . "/../libs/data_manager.php";
use SlideViewShare\libs\DataManager as DataManager;

class Slide {

  public $id;
  public $user_id;
  public $title;
  public $description;
  public $path;
  public $thumb_path;

  public function __construct($user_id, $title, $description = null, $path, $thumb_path) {
    $this->user_id = $user_id;
    $this->title = $title;
    $this->description = $description;
    $this->path = $path;
    $this->thumb_path = $thumb_path;

    $this->file = new DataManager('slides', 'csv');
  }

  public function save() {
    $this->id = DataManager::getLastId('slides') + 1;
    $write_data = array($this->id, $this->user_id, $this->title, $this->description,
      $this->path, $this->thumb_path);
    $this->file->save($write_data);
  }

  public static function find($id) {
    $tmp_s = DataManager::find('slides', array('id', $id));
    if ($tmp_s != null) {
      $slide = new Slide($tmp_s['user_id'], $tmp_s['title'], $tmp_s['description'], $tmp_s['path'], $tmp_s['thumb_path']);
      $slide->id = $tmp_s['id'];

      return $slide;
    }

    return null;
  }

  public function destroy() {
  }

  public static function all() {
  }
}
