<?php 
//register.php

define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/includes/global.inc.php');


//initialize php variables used in the form
$password = "";
$password_confirm = "";
$email = "";
$error = "";

//retrieve the $_POST variables
$email = $_POST['email'];
$password = $_POST['password'];

if ($email && $password) {
  //initialize variables for form validation
  $success = true;
  $userTools = new UserTools();
  
  //validate that the form was filled out correctly
  //check to see if user name already exists
  if ($userTools->checkAccountExists($email)) {
    $error .= "That email is already taken.<br/> \n\r";
    $success = false;
  }
  
  if ($success) {
    //prep the data for saving in a new user object
    $data['email'] = $email;
    $data['password'] = md5($password); //encrypt the password for storage
  
    //create the new user object
    $newUser = new User($data);
  
    //save the new user to the database
    $newUser->save(true);
  
    //log them in
    $userTools->login($email, $password);
    $array = array(
      "status" => 'logged_in',
      "user" => $user
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