var showtractor = {
  init: function() {
    showtractor.loadTemplate('/templates.php', function(templates) {
      if (window.logged_in) {
        showtractor.userSession = {
          "status": "logged_in",
          "user": window.user
        }
        showtractor.user = window.user; 
      }
      showtractor.templates = templates;
      showtractor.displayHome();
      $('#logo').unbind().click(function() {
        showtractor.displayHome();
      });
      $('#search').click(function() {
        var val = $('#search_input').val();
        if (val) {
          $('#search_input').blur();
          showtractor.search(val, function(shows) {
            showtractor.displayResults(shows);
          });
        } else {
          $('#search_input').css('background-color', 'red').bind('focus', function() {
            $(this).css('background-color', '');
          });
        }
      });
      $('#search_input').keydown(function(e) {
        var key = e.keyCode || 0;
        if (key == 13) $('#search').click();
      });
      showtractor.populateNav();
      if (showtractor.userSession) {
        showtractor.getFavorites();
      }
    });
  },
  details: {},
  userSession: false,
  user: false,
  displayedShow: false,
  traktAPIKey: 'e28c3d004287d60b9522e9856b1d5e64',
  templates: {},  
  loadTemplate: function(url, callBack) {
    var maps = showtractor.templates ? showtractor.templates : showtractor.templates = {};
    url = url.replace(/^https?:/i, document.location.protocol);
    if (maps[url]) {
      // use a brief timeout when the map is already loaded so the caller can rely on consistent asynchronicity
      setTimeout(function() { 
        callBack(maps[url]); 
      }, 1); 
    } else {
      $.get(url, function(txt) {
        maps[url] = {};
        var pairs = txt.split(/<!--%\s*/);
        for (var i = 0; i < pairs.length; i++) {
          if (pairs[i].match(/([^\s]+)\s*%-->\s*([\s\S]*)/)) maps[url][RegExp.$1] = RegExp.$2;
        }
        callBack(maps[url]);
      });
    }
  },
  showOverlay: function(html, dontShowClose) {
    $('body').append('<div id="mask" style="display: none;"></div>');
    $('#mask').fadeIn(50);
    $('body').css('overflow', 'hidden');
    $('body').append('<div id="overlay">' + html + '</div>');
    if (!dontShowClose) $('#overlay').append('<span class="close">close</span>');
    $('.close').unbind().click(function() {
      showtractor.closeOverlay();
    });
    var height = $('#overlay').outerHeight();
    console.log(height);
    var windowHeight = $(window).outerHeight();
    var margin = (height / 2) * -1;
    
    if (height+100 > windowHeight) {
      $('#overlay').css('top', '0px').css('margin-top', '0px').css('bottom', '0px').css('overflow', 'auto').css('border-radius', '0px');
    } else {
      $('#overlay').css('top', '50%').css('margin-top', margin-100);
      $('#overlay').animate({marginTop: margin-50}, 200);
    }
    
    $(document).bind('keydown.keyHideOverlay', function(event) {
      if (event && event.keyCode && event.keyCode == 27) {
        showtractor.closeOverlay();
      }
    });
  },
  closeOverlay: function() {
    $('#mask').remove();
    $('#overlay').remove();
    $('body').css('overflow', '');
    $(document).unbind('keydown.keyHideOverlay');
  },
  populateNav: function() {
    if (showtractor.userSession) {
      $('#nav').html(showtractor.templates['user_actions']);
      $('#logout').unbind().click(function() {
        showtractor.logout();
      });
      $('#my_favorites').unbind().click(function() {
        showtractor.displayFavorites();
      });
    } else {
      $('#nav').html(showtractor.templates['no_user_actions'])
      $('.nav.login').unbind().click(function() {
        showtractor.displayLogin();
      });
      $('.nav.signup').unbind().click(function() {
        showtractor.displaySignup();
      });
    }
  },
  displayHome: function() {
    $('#content').html(showtractor.templates['home_splash']);
  },
  displayFavorites: function() {  
    var html = $('<div>' + showtractor.templates['favorites_list'] + '</div>');
    if (showtractor.user.favorites && showtractor.user.favorites.length) {
      for (var i = 0; showtractor.user.favorites.length > i; i++) {
        var fav = showtractor.user.favorites[i];
        var li = $(''
          +'<li id="' + fav.tvrage_id + '_tvrage">'
          +'  <div class="favorite_list_img">'
          +'    <img height="50" src="' + fav.image + '">'
          +'  </div>' 
          +'  <div class="arrow"><svg version="1.1" id="Layer_1" xmlns:ev="http://www.w3.org/2001/xml-events" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="55px" height="100px" viewBox="0 0 55 100" enable-background="new 0 0 55 100" xml:space="preserve"><path fill="none" stroke="#FFFFFF" stroke-width="14" d="M6,5l39,45L6,95"></path></svg></div>'
          +'  <span>' + fav.name + '</span>'
          +'  <div class="clear"></div>'
          +'</li>'
        );
        html.find('ul').append(li);
      }
    } else {
      var li = $(''
        +'<li>'
        +'  <span>You haven\'t added any favorites yet.</span>'
        +'</li>'
      );
      html.find('ul').append(li).after('<span class="note">To add your favorite shows, search for them and click the heart under the show picture.</span>');
    }
    
    showtractor.showOverlay(html.html());
    $('#favorites_list li').unbind().click(function() {
      var tvrage_id = this.id.slice(0, -7);
      spinStart($('#' + tvrage_id + '_tvrage'), 'small', 'white', null, true);
      showtractor.getShow(tvrage_id, function(show) {
        spinStop($('#' + tvrage_id + '_tvrage'));
        showtractor.closeOverlay();
        showtractor.displayDetails(show);
      });
    });
  },
  displayLogin: function() {
    var html = showtractor.templates['login'];    
    showtractor.showOverlay(html);
    $('#login_go').unbind().click(function() {
      var creds = {
        "email": $('#email').val(),
        "password": $('#password').val()
      }
      showtractor.login(creds);
    });
    $('#email, #password').bind('keypress', function(e) {
      if ((e.keyCode || e.which) == 13) $('#login_go').click();
    });
  },
  displaySignup: function() {
    var html = showtractor.templates['signup'];    
    showtractor.showOverlay(html);
    $('#signup_go').unbind().click(function() {
      var creds = {
        "email": $('#email').val(),
        "password": $('#password').val()
      }
      showtractor.signup(creds);
    });
  },
  login: function(creds) {
    spinStart($('#login'), 'large', 'white', null, true);
    showtractor.ajax('POST', '/login.php', creds, function(data) {
      if (data.status == 'logged_in') {
        showtractor.userSession = data;
        showtractor.user = data.user;
        spinStop($('#login'));
        showtractor.closeOverlay();
        showtractor.init();
      } else {
        spinStop($('#login'));
        alert(data.reason);
      }
    });
  },
  getFavorites: function() {
    if (showtractor.userSession.status == 'logged_in') {
      var data = {
        "action": "get_favorites"
      }
      showtractor.ajax('GET', '/api/showactions.php', data, function(data) {
        if (data && data.length) {
          showtractor.user.favorites = [];
          for (var i = 0; data.length > i; i++) {
            showtractor.user.favorites.push(data[i]);
          }
        }
      });
    }
  },
  signup: function(creds) {
    spinStart($('#signup'), 'large', 'white', null, true);
    showtractor.ajax('POST', '/signup.php', creds, function(data) {
      if (data.status == 'logged_in') {
        showtractor.userSession = data;
        spinStop($('#signup'));
        showtractor.closeOverlay();
        showtractor.init();
      } else {
        spinStop($('#signup'));
        alert(data.reason);
      }
    });
  },
  logout: function() {
    spinStart($('#user_actions'), 'small', 'white', null, true);
    showtractor.ajax('GET', '/logout.php', null, function(data) {
      spinStop($('#user_actions'));
      if (data.status == 'success') {
        showtractor.userSession = false;
        showtractor.user = false;
        window.logged_in = false;
        window.user = false;
        showtractor.init();
      } else {
        alert('error logging you out');
      }
    });
  },

  search: function(query, callBack) {
    spinStart($('#search_box'), 'large', 'white', true, true);
    showtractor.ajax('GET', 'api/traktapi.php?query=' + query, null, function(data) {
      $('#results, #show_details').remove();
      var shows = data.results.show;
      if (!$.isArray(data.results.show)) {
        shows = [data.results.show];
      }
      spinStop($('#search_box'))
      if (callBack) callBack(shows);
    });
  },
  displayResults: function(shows) {
    showtractor.displayedShow = false;
    $('#home_splash').remove();
    
    $('#content').append(showtractor.templates['search_results']);
    if (shows && shows.length) {
      var on = [];
      var notOn = [];
      for (var s = 0; shows.length > s; s++) {
        if (shows[s].status == 'Canceled/Ended') {
          notOn.push(shows[s]);
        } else {
          on.push(shows[s]);
        }
      }
      if (on.length) {
        for (var i = 0; on.length > i; i++) {
          var li = $('<li id="' + on[i].showid + '_show"><span>' + on[i].name + '</span></li>');
          li.data("showid", on[i].showid);
          $('#on ul').append(li);
        }
        $('#display_not_on').append('<span id="display_not_on" class="link" style="display: block; text-align: center; margin-top: 30px; margin-bottom: 20px;">Display shows that aren\'t on anymore.</span>')
      }
      if (notOn.length) {
        for (var i = 0; notOn.length > i; i++) {
          var li = $('<li id="' + notOn[i].showid + '_show"><span>' + notOn[i].name + '</span></li>');
          li.data("showid", notOn[i].showid);
          $('#not_on ul').append(li);
        }
      }
      $('#display_not_on').unbind().click(function() {
        if ($('#not_on').css('display') == 'block') {
          $(this).text('Display shows that aren\'t on anymore.')
          $('#not_on').slideUp();
        } else {
          $(this).text('Hide shows that aren\'t on anymore.')
          $('#not_on').slideDown();
        }
      });
      if (on.length || notOn.length) $('#results').show();
      $('li').click(function() {
        
        var showid = $(this).data('showid');
        spinStart($('#' + showid + '_show'), 'small', 'white', null, true);
        showtractor.getShow(showid, function(show) {
          spinStop($('#' + showid + '_show'));
          showtractor.displayDetails(show);
        });
      });
    } else {
      alert('no shows');
    }
  },
  getShow: function(id, callBack) {
    if (showtractor.details[id]) {
      callBack(showtractor.details[id]);
    } else {
      showtractor.ajax('GET', 'api/get.php?view=details&showid=' + id, null, function(data) {
        console.log(data);
        data.results.id = data.show_id;
        if (callBack) callBack(data.results);
      });
    }
  },
  getShowIdbyTvRageId: function(tvrage_id) {
    var data = {
      "action": "get_show_id",
      "tvrage_id": tvrage_id
    }
    showtractor.ajax('POST', '/api/showactions.php', data, function(data) {
      console.log(data);
    });
  },
  buildSeasonMap: function(show) {
    show.seasons = {};
    if (!show.Episodelist.Season.length) return;
    for (var i = 0; show.Episodelist.Season.length > i; i++) {
      show.seasons[show.Episodelist.Season[i]['@attributes'].no] = show.Episodelist.Season[i].episode;
      for (var e = 0; show.Episodelist.Season[i].episode.length > e; e++) { 
        var episode = show.Episodelist.Season[i].episode[e];
        if (moment(episode.airdate).format('YYYYMMDD') >= moment().format('YYYYMMDD')) {
          show.currentSeason = {
            "episodes": show.Episodelist.Season[i].episode,
            "number": show.Episodelist.Season[i]['@attributes'].no
          }
        }
      }
    }
  },
  displayDetails: function(show) {
    showtractor.displayedShow = show;
   
    $('#content').html(showtractor.templates['show_details']);
    showtractor.details[show.showid] = show;
    showtractor.buildSeasonMap(show);
    var off = show.status == 'Canceled/Ended';
    $('.back').click(function() {
      $('#show_details').slideUp();
      $('#results').slideDown();
      showtractor.displayedShow = false;
    });
    var airday = ' on ' + show.airday + 's';
    if (!show.airday) airday = '';
    var network = ' on ' + show.network + '.';
    if (!show.network) network = '';
    var header = show.name + ' airs at ' + showtractor.convertTime(show.airtime) + airday + network
    if (off) header = show.name;
    $('#details_header').html(header);
    $('#show_image img').attr('src', show.image);
    var isFavorited = false;
    if (showtractor.user.favorites && showtractor.user.favorites.length) {
      for (var i = 0; showtractor.user.favorites.length > i; i++) {
        if (show.id == showtractor.user.favorites[i].show_id) isFavorited = true;
      }
    } 
    
    if (isFavorited) {
      $('#show_actions .favorite svg path').attr('fill', 'red');
      $('#show_actions .favorite svg').unbind().click(function() {
        showtractor.unfavoriteShow(show.showid);
      });
    } else {
      $('#show_actions .favorite svg path').attr('fill', 'gray');
      $('#show_actions .favorite svg').unbind().click(function() {
        showtractor.favoriteShow(show.showid, show.name, show.image);
      });
    }
    
    $('#show_details').append('<div id="this_season" class="season_details"></div>');
    $('#show_details').append('<div class="clear"></div>');
    if (off) {
      $('#this_season').append('<h2>This show is not on anymore.</h2>');
    } else {
      $('#this_season').append('<h1 class="season_header">' + show.name + ' is currently showing season ' + show.currentSeason.number + '.</h1>')
      $('#this_season').append('<div id="show_synopsis"></div>');
      if (show.currentSeason) {
        showtractor.displayCurrentSeason(show.currentSeason);
      } else {
        showtractor.displaySeasonList(show)
      }
    }
    
    $('#show_actions .info').unbind().click(function() {
      spinStart($('#show_actions .info'), 'small', 'white', null, true);
      showtractor.getShowSynopsis(show.showid, function(data) {
        console.log(data);
        spinStop($('#show_actions .info'));
        var html = $(data).find('.show_synopsis');
        html.find('div').remove();
        html.find('strong').remove();
        var text = $(html).text();
        var synopsis = '<div class="show_synopsis"><h1>' + show.name + '</h1><span>' + text + '</span></div>';
        showtractor.showOverlay(synopsis);
      });
    });
  },
  favoriteShow: function(tvrage_id, name, image) {
    spinStart($('.favorite'), 'small', 'white', null, true);
    var postData = {
      "action": "favorite",
      "tvrage_id": tvrage_id,
      "name": name,
      "image": image
    }
    showtractor.ajax('POST', '/api/showactions.php', postData, function(data) {
      spinStop($('.favorite'));
      if (data.status && data.status == 'success') {
        if (!showtractor.user.favorites) showtractor.user.favorites = [];
        showtractor.user.favorites.push(postData);
        $('#show_actions .favorite svg path').attr('fill', 'red');
      } else if (data.status && data.status == 'duplicate') {
        showtractor.unfavoriteShow(tvrage_id);
      }
    });
  },
  unfavoriteShow: function(tvrage_id) {
    if (confirm('Are you sure you want remove this show from your favorites?')) {
      var postdata = {
        "action": "unfavorite",
        "tvrage_id": tvrage_id
      }
      spinStart($('.favorite'), 'small', 'white', null, true);
      showtractor.ajax('POST', '/api/showactions.php', postdata, function(data) {
        spinStop($('.favorite'));
        if (data.status && data.status == 'success') {
          $('#show_actions .favorite svg path').attr('fill', 'gray');
          for (var i = 0; showtractor.user.favorites.length > i; i++) {
            if (showtractor.user.favorites[i].tvrage_id == tvrage_id) {
              showtractor.user.favorites.splice(i, 1);
            }
          }
        }
      });
    }
  },
  getEpisodeDetails: function(url, callBack) {
    showtractor.ajax('GET', 'api/get.php?view=episode_details&url=' + url, null, function(data) {
      console.log(data);
      if (callBack) callBack(data.results);
    });
  },
  getShowSynopsis: function(id, callBack) {
    showtractor.ajax('GET', 'api/get.php?view=synopsis&showid=' + id, null, function(data) {
      console.log(data);
      if (callBack) callBack(data.results);
    });
  },
  convertTime: function(time) {
    var times = time.split(':');
    return moment({"hour": times[0], "minute": times[1]}).format('h:mma');
  },
  displayCurrentSeason: function(season) {
    console.log(season);
    var futureEpisodes = [];
    var previousEpisodes = [];
    var nextEpisode = false;
    for (var e = 0; season.episodes.length > e; e++) {
      var episode = season.episodes[e];
      if (moment(episode.airdate).format('YYYYMMDD') >= moment().format('YYYYMMDD')) {
        futureEpisodes.push(episode);
      } else {
        previousEpisodes.push(episode);
      }
    }

    previousEpisodes.sort(function(a, b) {
      var a_airdate = moment(a.airdate).format('YYYYMMDD');
      var b_airdate = moment(b.airdate).format('YYYYMMDD');
      if (a_airdate > b_airdate) return -1;
      if (a_airdate < b_airdate) return 1;
      if (a_airdate == b_airdate) {
        if ((a.seasonnum-0) < (b.seasonnum-0)) {
          return 1;
        } else {
          return -1;
        }
      }
      return 0;
    });
    
    
    if (futureEpisodes.length) {
      $('#this_season').append('<div id="next_episode"><h2>Next Episode</h2><div></div><ul>');
      var li = showtractor.buildEpisodeListItem(futureEpisodes[0], season.number);
      var id = li.find('.episode').attr('id');
      $('#next_episode ul').append(li);
      $('#' + id).data('link', futureEpisodes[0].link);
      if (futureEpisodes.length > 1) {
        $('#this_season').append('<div id="future_episodes"><h2>Future Episodes</h2><ul></ul></div>');
        for (var fe = 1; futureEpisodes.length > fe; fe++) {
          var li = showtractor.buildEpisodeListItem(futureEpisodes[fe], season.number);
          var id = li.find('.episode').attr('id');
          $('#future_episodes ul').append(li);
          $('#' + id).data('link', futureEpisodes[fe].link);
        }
      }
    }
    
    if (previousEpisodes.length) {
      $('#this_season').append('<div id="previous_episodes"><h2>Previous Episodes</h2><ul></ul></div>');
      for (var pe = 0; previousEpisodes.length > pe; pe++) {
        var li = showtractor.buildEpisodeListItem(previousEpisodes[pe], season.number);
        var id = li.find('.episode').attr('id');
        $('#previous_episodes ul').append(li);
        $('#' + id).data('link', previousEpisodes[pe].link);
      }
    }
    
    $('li.episode .episode_info').unbind().click(function() {
      var link = $(this).closest('li').data('link');
      var context = $(this).closest('li');
      var title = $('.episode_title', context).text();
      spinStart($('li.episode .episode_info', context), 'small', 'white', null, true)
      showtractor.getEpisodeDetails(link, function(data) {
        spinStop($('li.episode .episode_info', context));
        var html = $(data).find('.show_synopsis');
        console.log(html.html());
        var text = '';
        if (html.text().trim()) {
          html.find('span').remove();
          html.find('a').remove();
          html.find('br').remove();
          text = html.text().trim();
        } else {
          text = 'No synopsis was found for this episode.';
        }
        var synopsis = '<div class="show_synopsis"><h1>' + title + '</h1><span>' + text + '</span></div>';
        showtractor.showOverlay(synopsis);
      });
    });
  },
  buildEpisodeListItem: function(episode, season) {
    var html = $('<div>' + showtractor.templates['episode_item'] + '</div>');
    
    var today = moment().format('YYYYMMDD');
    var tomorrow = moment().add(1, 'days').format('YYYYMMDD');
    var airs = 'Airs ' + ' ' + moment(episode.airdate).format('MMMM D, YYYY');
    if (moment(episode.airdate).format('YYYYMMDD') < moment().format('YYYYMMDD')) {
      airs = 'Aired ' + moment(episode.airdate).format('MMMM D, YYYY');
    } else if (moment(episode.airdate).format('YYYYMMDD') == today) {
      airs = 'Airs Today';
    } else if (moment(episode.airdate).format('YYYYMMDD') == tomorrow) {
      airs = 'Airs Tomorrow';
    }
    
    html.find('.episode').attr('id', season + 'x' + episode.seasonnum)
    html.find('.episode_num').text(episode.seasonnum);
    html.find('.episode_title').text(episode.title );
    html.find('.airs').text(airs);
    html.data('link', episode.link);
    return html;
  },
  displaySeasonList: function(show) {
    alert('display season list');
  },
  displayPastSeason: function(season) {
    
  },
  ajax: function(type, url, data, callBack) {
    $.ajax({
      type: type,
      url: url,
      data: data ? data : null,
      success: function(data) {
        if (callBack) callBack(data);
      },
      dataType: 'json'
    });
  }
}