<?php
namespace SlideViewShare\libs;
use \SplFileObject;

class DataManager {

  public $file;
  public $table;

  public function __construct($table_name, $type) {
    $this->table_name = $table_name;
    $this->file = self::getFile($table_name, "r+");
    $this->table = self::convertToTable($this->file);
  }

  public function save(array $data) {
    $path = $this->file->getRealPath();

    $fp = fopen($path, 'r+');
    fseek($fp, 0, SEEK_END);

    fputcsv($fp, $data);
    fclose($fp);
  }

  /**
   * @param String $table_name
   * @param Array $value_tuple
   */
  public static function destroy($table_name, array $value_tuple) {
    $file = self::getFile($table_name, "r");
    $table = self::convertToTable($file);
    $key = array_shift($value_tuple);
    $value = array_shift($value_tuple);

    $delete_i = null;
    foreach ($table as $index => $row) {
      if ($row[$key] == $value) {
        $delete_i = $index;
      }
    }

    if (isset($delete_i)) {
      $path = $file->getRealPath();
      $row_file = file($path);
      unset($row_file[$delete_i]);
      file_put_contents($path, $row_file, LOCK_EX);
    }
  }

  public static function find($table_name, array $value_tuple) {
    $file = self::getFile($table_name, "r");
    $table = self::convertToTable($file);
    $key = array_shift($value_tuple);
    $value = array_shift($value_tuple);

    foreach ($table as $row) {
      if ($row[$key] == $value) {
        return $row;
      }
    }

    return null;
  }

  /**
   * @param String $table_name
   * @return Array | null
   */
  public static function all($table_name) {
    $file = self::getFile($table_name, "r");
    return $table = self::convertToTable($file);
  }

  public static function getFile($table_name, $open_mode) {
    // TODO: 例外処理
    $file = new SplFileObject(__DIR__ . "/../../db/$table_name.csv", $open_mode);
    $file->setFlags(SplFileObject::READ_CSV);

    return $file;
  }

  public static function convertToTable($file) {
    $table = array();
    $keys = array();

    foreach ($file as $index => $row) {
      if ($index == 0) {
        $keys = $row;
      } else {
        if ($row[0] != null) {
          $table[] = array_combine($keys, $row);
        }
      }
    }

    return $table;
  }

  public static function getLastId($table_name) {
    $file = file(__DIR__ . "/../../db/$table_name.csv");
    return count($file) - 1;
  }
}
