<div class="soc-wigets-cont">
	<div class="heading soc-wigets-heading"><i class="fa fa-plus-circle"></i> UINP в соц. сетях</div>
	<div class="soc-wigets">

		<div class="soc-net-block">
			<script src="https://apis.google.com/js/platform.js" async defer>{lang: 'ru'}</script>
			<div class="g-page" data-width="250" data-href="//plus.google.com/u/0/107413725244831856907" data-rel="publisher"></div>
		</div>

		<div class="soc-net-block">
			<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.0";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
				<div class="fb-like-box" data-href="https://www.facebook.com/pages/UINP/1375927222723977" data-width="250" data-height="250" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="true" data-show-border="true"></div>
		</div>

		<div class="soc-net-block">
			<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/UinpInfo" data-widget-id="567608008881213440">Твиты от @UinpInfo</a>
			<script>
				!function(d,s,id){
					var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
					if(!d.getElementById(id)){
						js=d.createElement(s);
						js.id=id;js.src=p+"://platform.twitter.com/widgets.js";
						fjs.parentNode.insertBefore(js,fjs);
					}
				}(document,"script","twitter-wjs");
			</script>
		</div>

		<div class="soc-net-block">
			<script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>
			<!-- VK Widget -->
			<div id="vk_groups"></div>
			<script type="text/javascript">
			VK.Widgets.Group("vk_groups", {mode: 2, width:"250", height: "250"}, 87100399);
			</script>
		</div>

	</div>
	<script>
		$(document).ready(function(){
			$('.soc-wigets-heading').on('click',function(){
				$(this).parent().find('.soc-wigets').slideToggle();
				$(this).find('i').toggleClass('fa-minus-circle');
			});
		});
	</script>
</div>