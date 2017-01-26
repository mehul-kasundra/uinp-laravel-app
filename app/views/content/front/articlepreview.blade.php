<div class="preview article_preview">
	<div class="row" vocab="http://schema.org/" typeof="Article">
		<div class="col-md-12" style="overflow: hidden;">
			<div style="display:none">{{ $article->id }}</div>
			@if(!empty($article->thumb))
				<img src="/{{ $article->thumb }}" class="img-thumbnail pull-left" property="image">
			@elseif(!empty($article->video))
				<img src="http://img.youtube.com/vi/{{ $article->video }}/0.jpg" class="img-thumbnail pull-left" property="image">
			@endif
			<div style="overflow:hidden">
				<h2 property="name"><a href="/{{ $article->path }}/{{ $article->alias }}">{{ $article->title }}</a></h2>			
				<div class="date">
					<div>Опубликовано: <span property="datePublished">{{ $article->published_at }}</span></div>
					@if(isset($article->username)&&isset($article->google_account))
						<div>
							Автор:
							@if(!empty($article->google_account)) 
								<a href="{{ $article->google_account }}" property="author">{{ $article->username }}</a>
							@else
								<span property="author">{{ $article->username }}</span>
							@endif 
						</div>
					@endif				
				</div>
			</div>								
		</div>
		<div class="col-md-12 text-justify content" property="description">															
			{{ Common_helper::cropStr(strip_tags($article->content),470) }}
			<div><a href="/{{ $article->path }}/{{ $article->alias }}">Подробнее &#187</a></div>
		</div>
	</div>
</div>