<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width" />
	<title>@yield('title')</title>

	<link rel="icon" href="/assets/images/favicon.ico" />
	<link rel="stylesheet" type="text/css" media="all" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="/assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="/assets/css/style.css">
	@yield('styles')

	<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/js/scrolltopcontrol.js"></script>
	<script type="text/javascript" src="/assets/js/scripts.js"></script>
	@yield('scripts')

	@yield('seoMeta')	
</head>

<body cz-shortcut-listen="true">		
	<div class="container">
		<div class="top row">
			<div class="col-md-9">
				<div class="nav left top-menu">
					<div class="menu-header">
						<div class="pull-left" style="color: #326693;font-family: impact;position: absolute;font-size: 24px;top: 0px;">UINP</div>
						<div class="pull-right fa fa-bars show-menu"></div>
					</div> 
					@if(isset($menu->top))
						{{ $menu->top }}
					@endif
				</div>
				<div class="pull-left phone" style="margin: 4px 0 0 15px; text-decoration:none; display:none">телефон редакции: <a href="tel:380443922975" style="text-decoration:none;">+38 (044) 392-29-75</a></div>	
			</div>
			
			<div class="col-md-3">	
				<div class="search">
					<form action="/">
						<input type="text" name="search" placeholder="Поиск на сайте..." class="searchtext">
						<input type="submit" class="searchsubmit" value="">
					</form>
				</div>
			</div> 	
		</div> 
		<div id="header">
			<div class="logo">		
				<a href="/" title="" rel="home" style="color:#326693">
					<div class="pull-left"><img src="/assets/images/uinplogo.png" style="width: 160px; margin: 20px 0 0 10px;"></div>
					<div class="pull-left header-text" style="font-family:impact; font-size:30px; padding:5px 0 0 20px">
						<div>украинский независимый новостной портал</div>
						<div style="color: rgb(200, 200, 200); text-align:left">ukrainian independent news portal</div>
					</div>
					<div style="clear:both"></div>
				</a>
			</div>			
		</div>
		<div class="cat-menu">
			<div class="nav">
				<div class="menu-header">
					<div class="pull-left">КАТЕГОРИИ</div>
					<div class="pull-right fa fa-bars show-menu"></div>
				</div> 
				@if(isset($menu->categories))
					{{ $menu->categories }}
				@endif
			</div>				
		</div>
		<div class="breadcrumb">
			@if(isset($menu->categories))			
				{{ $breadcrumb }}
			@endif
		</div>
		<div class="main_content">
			@yield('main')		
		</div>
		<div class="footer">
			@if(isset($menu->bottom))
				{{ $menu->bottom }}
			@endif
		</div>			
		<div class="bottom">
			<div style="text-align:center">
			<!-- Yandex.Metrika counter -->
			<script type="text/javascript">
				(function (d, w, c) {
					(w[c] = w[c] || []).push(function() {
						try {
							w.yaCounter29837199 = new Ya.Metrika({
								id:29837199,
								clickmap:true,
								trackLinks:true,
								accurateTrackBounce:true
							});
						} catch(e) { }
					});

					var n = d.getElementsByTagName("script")[0],
						s = d.createElement("script"),
						f = function () { n.parentNode.insertBefore(s, n); };
					s.type = "text/javascript";
					s.async = true;
					s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

					if (w.opera == "[object Opera]") {
						d.addEventListener("DOMContentLoaded", f, false);
					} else { f(); }
				})(document, window, "yandex_metrika_callbacks");
			</script>
			<noscript><div><img src="//mc.yandex.ru/watch/29837199" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
			</div>
			<div style="text-align:center">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-62777753-1', 'auto');
  ga('send', 'pageview');

</script>
<!-- Yandex.Metrika informer -->
<a href="https://metrika.yandex.ru/stat/?id=32077906&amp;from=informer"
target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/32077906/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:32077906,lang:'ru'});return false}catch(e){}" /></a>
<!-- /Yandex.Metrika informer -->

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter32077906 = new Ya.Metrika({
                    id:32077906,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/32077906" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
					<!--LiveInternet counter--><script type="text/javascript"><!--
			document.write("<a href='//www.liveinternet.ru/click' "+
			"target=_blank><img src='//counter.yadro.ru/hit?t44.6;r"+
			escape(document.referrer)+((typeof(screen)=="undefined")?"":
			";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
			screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
			";"+Math.random()+
			"' alt='' title='LiveInternet' "+
			"border='0' width='31' height='31'><\/a>")
			//--></script><!--/LiveInternet-->
			<!-- Rating@Mail.ru counter -->
			<script type="text/javascript">
			var _tmr = _tmr || [];
			_tmr.push({id: "2648013", type: "pageView", start: (new Date()).getTime()});
			(function (d, w) {
			   var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true;
			   ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
			   var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
			   if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
			})(document, window);
			</script><noscript><div style="position:absolute;left:-10000px;">
			<img src="//top-fwz1.mail.ru/counter?id=2648013;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
			</div></noscript>
			<!-- //Rating@Mail.ru counter -->
			
			<!-- begin of Top100 code -->
			<script id="top100Counter" type="text/javascript" src="http://counter.rambler.ru/top100.jcn?3103915"></script>
			<noscript>
			<a href="http://top100.rambler.ru/navi/3103915/">
			<img src="http://counter.rambler.ru/top100.cnt?3103915" alt="Rambler's Top100" border="0" />
			</a>
	                <script async="async" src="https://w.uptolike.com/widgets/v1/zp.js?pid=1393858" type="text/javascript"></script>
			</noscript>
			<!-- end of Top100 code -->
			<script>
			  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			  ga('create', 'UA-62777753-1', 'auto');
			  ga('send', 'pageview');

			</script>
			</div>

			<div class="text-center" style="margin-top:20px">
				Официальный информационный партнёр <a href="http://vk.com/club64920284" target="_blank">майдан | евромайдан | ато | Моя Украина</a>, <a href="http://vidverto.net">Vidverto.net</a>
			</div>
			© Copyriht 2015
		</div>
	</div>	
</body>
</html>