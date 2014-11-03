<?php

define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/includes/global.inc.php');


$userTools = new UserTools();
$userTools->logout();

$array = array(
  "status" => 'success'
);
$rtn = json_encode($array);

echo($rtn);
?>