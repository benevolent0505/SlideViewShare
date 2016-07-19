<?php

function my_password_hash($password) {
  $algorithm = '2a'; // Blowfish
  $cost = '10';
  $seed = array_merge(range('0', '9'), range('a', 'z'), range('A', 'Z'), array('.', '/'));

  // make salt
  $salt = '$' . $algorithm . '$' . $cost . '$';
  for ($i = 0; $i < 22; $i++) {
    $salt = $salt . $seed[mt_rand(0, count($seed)-1)];
  }
  $salt = $salt . '$';

  return crypt($password, $salt);
}

function my_password_verify($password, $hash) {
  $salt = substr($hash, 0, 29);
  $h = crypt($password, $salt);

  return strcmp($hash, $h) == 0;
}
