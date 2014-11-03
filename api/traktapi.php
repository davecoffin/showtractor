<?php 
  define('__ROOT__', dirname(dirname(__FILE__))); 
  require_once(__ROOT__.'/includes/global.inc.php');
  $traktapikey = 'e28c3d004287d60b9522e9856b1d5e64';
  $api = 'trakt';
  $view = $_REQUEST['view'];
  $tvrageid = false;
  $query = $_REQUEST['query'];
  if ($_REQUEST['useXML'] == 'true') $useXML = true;
  $newquery = str_replace(' ', '+', $query);
  $baseURL = 'http://api.trakt.tv/';
  
  $url = "$baseURL/search/shows.json/$traktapikey?query=$newquery";
  $url2 = '';
  if ($view == 'details') {
    $tvdb_id = $_REQUEST['tvdb_id'];
    $url = "$baseURL/show/summary.json/$traktapikey/$tvdb_id/extended";
    $url2 = "$baseURL/show/seasons.json/$traktapikey/$tvdb_id";
  } else if ($view == 'synopsis') {
    $tvrageid = $_REQUEST['showid'];
    $url = "http://www.tvrage.com/shows/id-$tvrageid";
  } else if ($view == 'episode_details') {
    $url = $_REQUEST['url'];
  }
  
  if ($view == 'synopsis' || $view == 'episode_details') {
    $json = file_get_contents($url);
  } else {
    $json = file_get_contents($url);
    
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
  } else if ($view == 'details') {
    /*
    $seasons = file_get_contents($url2);
    $array = array(
      "results" => array(
        "show" => json_decode($json),
        "seasons" => json_decode($seasons)
      ),
      "full_url" => $url
    );
    */
    $array = array(
      "results" => array(
        "show" => json_decode($json)
      ),
      "full_url" => $url
    );
  } else {
    $array = array(
      "results" => json_decode($json),
      "full_url" => $url
    );
  }
  
  
  $rtn = json_encode($array);
  
  echo $rtn;
?>