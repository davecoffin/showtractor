<?php
  function allowCORS($allowedMethods = 'GET, OPTIONS, POST')
  {
    $allowedHeaders = $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'] or $allowedHeaders = '*';

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: $allowedMethods");
    header("Access-Control-Allow-Headers: $allowedHeaders");
    header('Access-Control-Max-Age: 1000');
  
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') exit;
  }
  allowCORS();  
?>
<!--% home_splash %-->
<div id="home_splash">
  <img src="images/color_logo.png"><br />
  ShowTractor helps you remember when your shows are on.
</div>

<!--% search_results %-->
<div id="results" style="overflow: hidden;">
  <div id="on"><ul></ul></div>
  <div id="not_on" style="display: none;"><ul></ul></div>
</div>

<!--% login %-->
<div id="login" class="center">
  <h1 style="color: #60caff;">Log In</h1><br />
  <input id="email" type="text" placeholder="Enter your email..."/>
  <input id="password" type="password" placeholder="Enter your password..."/><br /><br />
  <span id="login_go">Go</span><br /><br /><br />
</div>

<!--% signup %-->
<div id="signup" class="center">
  <h1 style="color: orange;">Sign Up</h1><br />
  <input id="email" type="text" placeholder="Enter an email..."/>
  <input id="password" type="text" placeholder="Enter a password..."/><br /><br />
  <span id="signup_go">Go</span><br /><br /><br />
</div>

<!--% favorites_list %-->
<div id="favorites_list">
  <h1 style="color: orange;">
    <svg id="list_heart" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve" preserveAspectRatio="xMinYMin meet" width="100%" height="100%">
      <path fill="red" d="M67.607,13.462c-7.009,0-13.433,3.238-17.607,8.674c-4.174-5.437-10.598-8.674-17.61-8.674
      	c-12.266,0-22.283,10.013-22.33,22.32c-0.046,13.245,6.359,21.054,11.507,27.331l1.104,1.349
      	c6.095,7.515,24.992,21.013,25.792,21.584c0.458,0.328,1,0.492,1.538,0.492c0.539,0,1.08-0.165,1.539-0.492
      	c0.8-0.571,19.697-14.069,25.792-21.584l1.103-1.349c5.147-6.277,11.553-14.086,11.507-27.331
      	C89.894,23.475,79.876,13.462,67.607,13.462z"></path>
    </svg>
    My Favorites
  </h1><br />
  <ul>
  
  </ul>
</div>

<!--% no_user_actions %-->
<div id="no_user_actions">
  <span class="nav login">Log In</span>
  <span class="nav signup">Sign Up</span>
</div>

<!--% user_actions %-->
<div id="user_actions">
  <img id="header_profile_photo" src="http://d1wtlopzkpoh9j.cloudfront.net/images/original/2323158.png">
  <span class="logged_in_nav" id="my_favorites">
    <svg id="header_heart" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve" preserveAspectRatio="xMinYMin meet" width="100%" height="100%">
      <path fill="red" d="M67.607,13.462c-7.009,0-13.433,3.238-17.607,8.674c-4.174-5.437-10.598-8.674-17.61-8.674
      	c-12.266,0-22.283,10.013-22.33,22.32c-0.046,13.245,6.359,21.054,11.507,27.331l1.104,1.349
      	c6.095,7.515,24.992,21.013,25.792,21.584c0.458,0.328,1,0.492,1.538,0.492c0.539,0,1.08-0.165,1.539-0.492
      	c0.8-0.571,19.697-14.069,25.792-21.584l1.103-1.349c5.147-6.277,11.553-14.086,11.507-27.331
      	C89.894,23.475,79.876,13.462,67.607,13.462z"></path>
    </svg>
    My Favorites
  </span>
  <span class="logged_in_nav" id="logout">Log Out</span
  <div class="clear"></div>
</div>

<!--% show_details %-->
<div id="show_details">
  <div id="show_title"></div>
  <div id="show_info"></div>
  <div id="show_image">
    <img src="">
    <div id="show_actions">
      <span class="favorite">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve" preserveAspectRatio="xMinYMin meet" width="100%" height="100%">
          <path d="M67.607,13.462c-7.009,0-13.433,3.238-17.607,8.674c-4.174-5.437-10.598-8.674-17.61-8.674
          	c-12.266,0-22.283,10.013-22.33,22.32c-0.046,13.245,6.359,21.054,11.507,27.331l1.104,1.349
          	c6.095,7.515,24.992,21.013,25.792,21.584c0.458,0.328,1,0.492,1.538,0.492c0.539,0,1.08-0.165,1.539-0.492
          	c0.8-0.571,19.697-14.069,25.792-21.584l1.103-1.349c5.147-6.277,11.553-14.086,11.507-27.331
          	C89.894,23.475,79.876,13.462,67.607,13.462z"></path>
        </svg>
      </span>
      <span class="info">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
          	 viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
          <g display="none">
          	<path display="inline" fill="#231F20" d="M50,22.1c8.7,0.1,17.1,2.1,25,5.8c6.6,3.4,12.3,7.5,16.9,12.4c5,5.6,5.8,8.3,5.9,9.4
          		c-0.4,2.8-2.3,6-5.8,9.6c-5,5.1-10.7,9.3-16.9,12.4c-8.2,3.9-16.7,5.8-25,5.8c-8.7-0.1-17.1-2.1-25-5.8
          		c-6.6-3.4-12.3-7.5-16.9-12.4c-5-5.6-5.8-8.3-5.9-9.4c0.4-2.8,2.3-6,5.8-9.6C13,35.2,18.7,31,25,27.9C33.2,24,41.6,22.1,50,22.1
          		 M50,66.2L50,66.2c4.7,0,8.7-1.6,12-4.8c3.3-3.2,5-7.1,5-11.6c0-4.5-1.7-8.4-5-11.6c-3.3-3.1-7.3-4.7-12-4.7s-8.7,1.6-12,4.7
          		c-3.3,3.2-5,7.1-5,11.6c0,4.5,1.7,8.4,5,11.6C41.3,64.6,45.3,66.2,50,66.2 M50,20.1c-8.7,0-17.3,2-25.9,6
          		c-6.5,3.2-12.3,7.5-17.5,12.8c-3.9,4-6,7.6-6.4,10.9c0,2.5,2.1,6.1,6.4,10.9c4.8,5.1,10.6,9.3,17.5,12.8c8.2,3.9,16.8,5.9,25.9,6
          		c8.7,0,17.3-2,25.9-6c6.5-3.2,12.3-7.5,17.5-12.8c3.9-4,6-7.6,6.4-10.9c0-2.5-2.1-6.1-6.4-10.9c-4.8-5.1-10.6-9.3-17.5-12.8
          		C67.7,22.2,59,20.2,50,20.1L50,20.1z M50,64.2c-4.1,0-7.7-1.4-10.6-4.3S35,53.7,35,49.7c0-4,1.5-7.3,4.4-10.1
          		c2.9-2.8,6.4-4.2,10.6-4.2c4.1,0,7.7,1.4,10.6,4.2c2.9,2.8,4.4,6.2,4.4,10.1c0,4-1.5,7.4-4.4,10.2S54.1,64.2,50,64.2L50,64.2z"/>
          </g>
          <g>
          	<path fill="#6DC6F0" d="M50,12c21,0,38,17,38,38S71,88,50,88S12,71,12,50S29,12,50,12 M50,10c-22.1,0-40,17.9-40,40s17.9,40,40,40
          		s40-17.9,40-40S72.1,10,50,10L50,10z"/>
          </g>
          <g>
          	<path fill="#6DC6F0" d="M54.1,27.7c0.1,2.3-1.6,4.1-4.2,4.1c-2.3,0-4-1.8-4-4.1c0-2.3,1.7-4.2,4.2-4.2
          		C52.5,23.6,54.1,25.4,54.1,27.7z M46.7,74.6V38h6.6v36.6H46.7z"/>
          </g>
        </svg>

      </span>
    </div>
  </div>
  <div id="this_season" class="season_details"></div>
</div>

<!--% episode_item %-->
<li class="episode">
  <span class="episode_num"></span>
  <span class="episode_title"></span>
  <div class="episode_actions">
    <div class="watch"><img src="/images/watch_off.svg"></div>
    <div class="episode_info"><img src="/images/info.svg"></div>
  </div>
  <span class="airs"></span>
  <div class="clear"></div>
</li>