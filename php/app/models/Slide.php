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

  public function __construct($user_id, $title, $description = null, $path) {
    $this->user_id = $user_id;
    $this->title = $title;
    $this->description = $description;
    $this->path = $path;

    $this->file = new DataManager('slides', 'csv');
  }

  public function save() {
    $this->id = DataManager::getLastId('slides') + 1;
    $write_data = array($this->id, $this->user_id, $this->title, $this->description, $this->path);
    $this->file->save($write_data);
  }

  public static function find($id) {
    $tmp_s = DataManager::find('slides', array('id', $id));
    if ($tmp_s != null) {
      $slide = new Slide($tmp_s['user_id'], $tmp_s['title'], $tmp_s['description'], $tmp_s['path']);
      $slide->id = $tmp_s['id'];

      return $slide;
    }

    return null;
  }

  public function destroy() {
    return DataManager::destroy('slides', array('id', $this->id));
  }

  /**
   * @return Array|null
   */
  public static function all() {
    $table = DataManager::all('slides');
    $slides = array();

    if ($table !=null) {
      foreach ($table as $s) {
        $slide = new Slide($s['user_id'], $s['title'], $s['description'], $s['path']);
        $slide->id = $s['id'];

        $slides[] = $slide;
      }

      return $slides;
    }

    return null;
  }
}
