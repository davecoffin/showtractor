<?php
require_once 'User.class.php';
require_once 'DB.class.php';

class ShowTools {

	public function addShow($tvrage_id, $tvdb_id, $name, $image) {
  	$db = new DB();
	  $data = array(
			"tvrage_id" => $tvrage_id,
			"tvdb_id" => $tvdb_id,
			"name" => "'$name'",
			"image" => "'$image'"
		);
		$this->id = $db->insert($data, 'shows');
		return $this->id;
	}
	
	public function favorite($tvrage_id, $tvdb_id, $name, $image) {
	  if ($_SESSION["logged_in"]) {
  	  $result = mysql_query("SELECT id FROM shows WHERE tvdb_id = '$tvdb_id'");
		
  		// if the show is in our db, pass back the id and if not, add it.
  		if (mysql_num_rows($result) == 1) {
  			$row = mysql_fetch_assoc($result);
  			$showid = $row['id'];
  		} else {
  		  $showid = $this->addShow($tvrage_id, $tvdb_id, $name, $image);
  		}
  
      // check if this user has already favorited this show
  		$favoriteResult = mysql_query("SELECT id FROM favorites WHERE user_id = " . $_SESSION['user_id'] . " AND show_id = '$showid'");
  		if (mysql_num_rows($favoriteResult) == 1) {
  		  $rtn = array(
          "status" => 'duplicate',
          "show_id" => $showid
        );
  		} else {
        $db = new DB();
    	  $data = array(
    			"user_id" => $_SESSION['user_id'],
    			"show_id" => $showid
    		);
  
    		$this->id = $db->insert($data, 'favorites');
    		$rtn = array(
    		  "status" => 'success',
    		  "show_id" => $showid
    		);
  
  
  		}
	  } else {
  	  $rtn = array(
  		  "status" => 'failed',
  		  "reason" => 'not_logged_in'
  		);
	  }
		return $rtn;
	}	
	
	public function unfavorite($tvdb_id) {
	  $error = '';
	  if ($_SESSION["logged_in"]) {
  	  $result = mysql_query("SELECT id FROM shows WHERE tvdb_id = '$tvdb_id'");
      if (mysql_num_rows($result) == 1) {
  			$row = mysql_fetch_assoc($result);
  			$showid = $row['id'];
  			// check if this user has favorited the show

    		$favoriteResult = mysql_query("SELECT id FROM favorites WHERE user_id = " . $_SESSION['user_id'] . " AND show_id = '$showid'");
    		
    		if (mysql_num_rows($favoriteResult) == 1) {
    		  $db = new DB();
          $db->delete('favorites', "user_id = " . $_SESSION["user_id"] . " AND show_id = " . $showid);

          $rtn = array(
            "status" => 'success',
            "show_id" => $showid
          );
    		} else {
          $error = 'no_favorite_found';
    		}
  		} else {
    		$error = 'cant_find_showid';
  		}
	  } else {
	    $error = 'not_logged_in';
	  }
	  
	  if ($error) {
  	  $rtn = array(
  		  "status" => 'failed',
  		  "reason" => $error
  		);
	  }
		return $rtn;
	}
	
  public function getFavorites() {
    if ($_SESSION["logged_in"]) {
      $db = new DB();
      $favorites = array();
  		$result = $db->select('favorites', "user_id = " . $_SESSION["user_id"], true);
  		foreach($result as $key => $val) {
        foreach($result[$key] as $key2 => $value2) {
          if ($key2 == 'show_id') {
            $showdetails = $db->select('shows', "id = " . $value2, false);
            $result[$key]['name'] = $showdetails['name'];
            $result[$key]['image'] = urldecode($showdetails['image']);
            $result[$key]['tvrage_id'] = urldecode($showdetails['tvrage_id']);
            $result[$key]['tvdb_id'] = urldecode($showdetails['tvdb_id']);
          }
        }
      }
  		return $result;
    }
  }
  
  public function getShowIdByTvRageId($tvrage_id) {
    $result = mysql_query("SELECT id FROM shows WHERE tvrage_id = '$tvrage_id'");
    if (mysql_num_rows($result) == 1) {
			$row = mysql_fetch_assoc($result);
			$showid = $row['id'];
			return $showid;
    }
  }
}

?>