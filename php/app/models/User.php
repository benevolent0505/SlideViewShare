<?php
namespace SlideViewShare\models;

require_once __DIR__ . "/../libs/data_manager.php";
require_once __DIR__ . "/../helpers/users_helper.php";

use SlideViewShare\libs\DataManager as DataManager;

class User {

  public $id;
  public $username;
  public $password;

  public $file;

  public function __construct($username, $password, $crypt_flag = true) {
    $this->username = $username;
    if ($crypt_flag) {
      $this->password = my_password_hash($password);
    } else {
      $this->password = $password;
    }

    $this->file = new DataManager('users', 'csv');
  }

  public function save() {
    $this->id = DataManager::getLastId('users') + 1;
    $write_data = array($this->id, $this->username, $this->password);
    $this->file->save($write_data);
  }

  public static function find($username) {
    $value_arr =  DataManager::find('users', array('username', $username));
    if ($value_arr != null) {
      $user = new User($value_arr['username'], $value_arr['password'], false);
      $user->id = $value_arr['id'];

      return $user;
    }

    return null;
  }

  /**
   * @param Array $value_tuple (ex array('id' => $user_id))
   * @return User | null
   */
  public static function findBy(array $value_tuple) {
    $value_arr =  DataManager::find('users', $value_tuple);
    if ($value_arr != null) {
      $user = new User($value_arr['username'], $value_arr['password'], false);
      $user->id = $value_arr['id'];

      return $user;
    }

    return null;
  }

  public function destroy() {
    return DataManager::destroy('users', array('id', $this->id));
  }
}
