<?php
//global.inc.php
define('__ROOT__', dirname(dirname(__FILE__))); 

require_once(__ROOT__.'/classes/User.class.php');
require_once(__ROOT__.'/classes/UserTools.class.php');
require_once(__ROOT__.'/classes/ShowTools.class.php');
require_once(__ROOT__.'/classes/DB.class.php');

//connect to the database
$db = new DB();
$db->connect();

//initialize UserTools object
$userTools = new UserTools();

//start the session
session_start();

//refresh session variables if logged in
if(isset($_SESSION['logged_in'])) {
	$user = unserialize($_SESSION['user']);
	$_SESSION['user'] = serialize($userTools->get($user->id));
}
?>