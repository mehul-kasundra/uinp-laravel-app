@if(isset($weeklyNews)&&!empty($weeklyNews))
	<div class="heading">Итоги недели</div>
	@foreach($weeklyNews as $article)
		@include('content.front.articlelistitem')
	@endforeach	
@endif
@if(isset($crimea)&&!empty($crimea))
	<div class="heading">Новости Крыма</div>
	@foreach($crimea as $article)
		@include('content.front.articlelistitem')
	@endforeach	
@endif
@if(Request::is('/'))
	@if(isset($dnr)&&!empty($dnr))
		<div class="heading">Новости ДНР</div>
		@foreach($dnr as $article)
			@include('content.front.articlelistitem')
		@endforeach	
	@endif
	@if(isset($rusvesna)&&!empty($rusvesna))
		<div class="heading">Русская весна</div>
		@foreach($rusvesna as $article)
			@include('content.front.articlelistitem')
		@endforeach	
	@endif
	@if(isset($accidentNews)&&!empty($accidentNews))
		<div class="heading">Происшествия</div>
		@foreach($accidentNews as $article)
			@include('content.front.articlelistitem')
		@endforeach
	@endif
	@if(isset($video)&&!empty($video))
		<div class="heading">Видео</div>
		<div class="video_block">
			@foreach($video as $article)
				@include('content.front.articlelistitem')
			@endforeach
		</div>
	@endif
	@if(isset($vidverto)&&!empty($vidverto))
		<div class="heading">Вiдверто</div>
		<div class="video_block">
			@foreach($vidverto as $article)
				@include('content.front.articlelistitem')
			@endforeach
		</div>
	@endif
@endif
<div class="heading">Наши партнёры</div>
<div id="retraf_1406"></div>
<script>
    document.write('<scr'+'ipt async type="text/javascript" '
    +'src="http://smiradar.ru/retraf.js?b=1406&s=1391&r='+Math.random()
    + '" charset="utf-8"></scr'+'ipt>');
</script>

@include('content.front.socwidgets')
