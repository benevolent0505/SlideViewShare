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

  /**
   * @param Array $value_tuple (ex array('id' => $slide_id))
   * @return Slide | null
   */
  public static function findBy(array $value_tuple) {
    $value_arr =  DataManager::find('slides', $value_tuple);
    if ($value_arr != null) {
      $slide = new Slide($value_arr['user_id'], $value_arr['title'], $value_arr['description'], $value_arr['path'], $value_arr['thumb_path']);
      $slide->id = $value_arr['id'];

      return $slide;
    }

    return null;
  }

  /**
   * @param String $user_id
   * @return Array
   */
  public static function findAllBy($user_id) {
    $tmp_arr = DataManager::findAll('slides', array('user_id', $user_id));

    $slides = array();
    foreach ($tmp_arr as $row) {
      $slide = new Slide($row['user_id'], $row['title'], $row['description'], $row['path'], $row['thumb_path']);
      $slide->id = $row['id'];

      $slides[] = $slide;
    }

    return $slides;
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
