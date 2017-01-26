<div class="article_list_row">
	@if(!empty($article->thumb))
		<img src="/{{ $article->thumb }}" class="img-thumbnail-small pull-left">
	@elseif(!empty($article->video))
		<img src="http://img.youtube.com/vi/{{ $article->video }}/0.jpg" class="img-thumbnail-med pull-left">
	@endif
	<div style="overflow:hidden">
		<a href="/{{ $article->path }}/{{ $article->alias }}">{{ $article->title }}</a>	
		<div class="date">
			<div>Опубликовано: {{ $article->published_at }}</div>
		</div>
	</div>
</div>

