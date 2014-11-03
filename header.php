<?php
//index.php 

require_once 'includes/global.inc.php';
if ($_SESSION['logged_in']) {
  $status = 'logged_in';
  $logged_in = 1;
  $user = json_encode(unserialize($_SESSION["user"]));
} else {
  $status = 0;
  $logged_in = 0;
  $user = 0;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" /> 
    <meta name="apple-mobile-web-app-capable" content="yes" />

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<script src="http://momentjs.com/downloads/moment.js"></script>
		<script src="js/showtractor.js"></script>
		<script src="js/spin.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/showtractor.css" />
		<script>
		  var logged_in = <?=$logged_in?>;
		  var user = <?=$user?>;
		</script>
	</head>
	<body>
	  <div id="wrapper">
    	<div id="header">
  	    <div id="header_content">
  
      	  <div id="logo"><img src="images/eye_logo_small.png"></div>
      	  <div id="nav"></div>
      	  <div id="search_box"><input type="text" id="search_input" placeholder="Search for a show..."><span id="search">Search</span></div>
      	  
  	    </div>  	  
  	  </div>