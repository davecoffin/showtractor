<?php
//login.php

define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/includes/global.inc.php');

$email = "";
$password = "";

//check to see if they've submitted the login form
$email = false;
$password = false;
if ($_POST['email']) $email = $_POST['email'];
if ($_POST['password']) $password = $_POST['password'];

if ($email && $password) {
  $userTools = new UserTools();
  if ($userTools->login($email, $password)) {
    $user = unserialize($_SESSION["user"]);
    $array = array(
      "status" => 'logged_in',
      "user" => $user
    );
    $rtn = json_encode($array);
  } else {
    $array = array(
      "status" => 'failed',
      "reason" => 'No account found.'
    );
    $rtn = json_encode($array);
  }
} else {
  $array = array(
    "status" => 'failed',
    "reason" => 'Missing parameter.'
  );
  $rtn = json_encode($array);
}
echo $rtn;
?>