<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/includes/global.inc.php');

$action = false;
if ($_POST['action']) $action = $_POST['action'];
if ($_REQUEST['action']) $action = $_REQUEST['action'];
$rtn = 'false';

if ($action == 'favorite') {
  $tvrage_id = $_POST['tvrage_id'];
  $tvdb_id = $_POST['tvdb_id'];
  $name = $_POST['name'];
  $image = $_POST['image'];
  $db = new DB();
  $imgurl = mysqli_real_escape_string($db, $image);
  $imgurl = '';  
  $imgurl = urlencode($image);
  $showTools = new ShowTools();
  $rtn = json_encode($showTools->favorite($tvrage_id, $tvdb_id, $name, $imgurl));
} else if ($action == 'unfavorite') {
  $tvdb_id = $_POST['tvdb_id'];
  $showTools = new ShowTools();
  $rtn = json_encode($showTools->unfavorite($tvdb_id));
} else if ($action == 'get_favorites') {
  $showTools = new ShowTools();
  $rtn = json_encode($showTools->getFavorites());
} else if ($action == 'get_show_id') {
  $tvrage_id = $_POST['tvrage_id'];
  $showTools = new ShowTools();
  $rtn = json_encode($showTools->getTvRageIdByShowId($tvrage_id));
} 

echo $rtn;
?>