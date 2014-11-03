<?php 
  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/includes/global.inc.php');

  $api = 'trakt';
  $view = $_REQUEST['view'];
  $tvrageid = false;
  $query = $_REQUEST['query'];
  $useXML = false;
  if ($_REQUEST['useXML'] == 'true') $useXML = true;
  $newquery = str_replace(' ', '%20', $query);
  $url = "http://services.tvrage.com/feeds/search.php?show=$newquery";
  if ($view == 'details') {
    $tvrageid = $_REQUEST['showid'];
    $url = "http://services.tvrage.com/feeds/full_show_info.php?sid=$tvrageid";
  } else if ($view == 'synopsis') {
    $tvrageid = $_REQUEST['showid'];
    $url = "http://www.tvrage.com/shows/id-$tvrageid";
  } else if ($view == 'episode_details') {
    $url = $_REQUEST['url'];
  }
  if ($useXML) {
    $xml = file_get_contents($url);
    $rtn = $xml;
  } else {
    if ($view == 'synopsis' || $view == 'episode_details') {
      $xml = file_get_contents($url);
    } else {
      $xml = simplexml_load_file($url);
      if ($tvrageid) {
        $showTools = new ShowTools();
        $show_id = $showTools->getShowIdByTvRageId($tvrageid);
      }
      
    }
    if ($show_id) {
      $array = array(
        "results" => $xml,
        "show_id" => $show_id,
        "full_url" => $url
      );
    } else {
      $array = array(
        "results" => $xml,
        "full_url" => $url
      );
    }
    
    
    $rtn = json_encode($array);
  }
  
  echo $rtn;
?>