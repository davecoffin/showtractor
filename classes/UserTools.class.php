<?php
require_once 'User.class.php';
require_once 'DB.class.php';

class UserTools {

	//Log the user in. First checks to see if the 
	//username and password match a row in the database.
	//If it is successful, set the session variables
	//and store the user object within.
	public function login($email, $password)
	{

		$hashedPassword = md5($password);
		$result = mysql_query("SELECT * FROM users WHERE email = '$email' AND password = '$hashedPassword'");

		if (mysql_num_rows($result) == 1) {
		  $row = mysql_fetch_assoc($result);
			$_SESSION["user"] = serialize(new User($row));
			$_SESSION["user_id"] = $row['id'];
			$_SESSION["login_time"] = time();
			$_SESSION["logged_in"] = 1;
			return true;
		} else {
			return false;
		}
	}
	
	//Log the user out. Destroy the session variables.
	public function logout() {
		unset($_SESSION["user"]);
		unset($_SESSION["login_time"]);
		unset($_SESSION["logged_in"]);
		session_destroy();
	}

	//Check to see if a username exists.
	//This is called during registration to make sure all user names are unique.
	public function checkAccountExists($email) {
		$result = mysql_query("select id from users where email='$email'");
  	if(mysql_num_rows($result) == 0) {
			return false;
   	} else {
   		return true;
		}
	}
	
	//get a user
	//returns a User object. Takes the users id as an input
	public function get($id)
	{
		$db = new DB();
		$result = $db->select('users', "id = $id");
		
		return new User($result);
	}
	
}

?>