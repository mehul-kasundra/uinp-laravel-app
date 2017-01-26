@extends('containers.frontend')
@section('title')
	@if(!empty($item->seo_title))
		{{ $item->seo_title }}
	@else
		{{ $item->title }}
	@endif
@stop
@section('seoMeta')
    <meta name="description" content="{{ $item->description }}">
    <meta name="keywords" content="{{ $item->keywords }}">
@stop

@section('main')
	<div class="row" vocab="http://schema.org/" typeof="Article">
		<div class="col-md-8">
			@if (isset($item) && !empty($item))
				<div class="row">			
					<div class="col-md-12">
						<h1 property="name">{{ $item->title }}</h1>
						<div class="entry-meta">
							<div class="pull-left">
								@if(!empty($item->userthumb))
									<img src="/{{ $item->userthumb }}" style="width: 30px;  margin-right: 5px;">
								@endif
							</div>
							<div>
								Автор
								@if(!empty($item->google_account)) 
									<a href="{{ $item->google_account }}" property="author">{{ $item->username }}</a>
								@else
									<span property="author">{{ $item->username }}</span>
								@endif
							</div>
							<div>Опубликовано <span property="datePublished">{{ $item->published_at }}</span>.</div>
						</div>
						@if(!empty($item->image))
							<img src="/{{ $item->image }}" style="width:100%; max-width:300px; margin: 0 10px 0 0" class="pull-left" title="{{ $item->img_title }}" alt="{{ $item->img_alt }}" property="image">
						@endif	
						<div class="text-justify" property="description">
							{{ Common_helper::close_tags($item->content) }}
						</div>
						@if(!empty($item->video))
							<iframe src="https://www.youtube.com/embed/{{ $item->video }}" class="videoframe" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
						@endif
						<div class="pull-left" style="margin:5px">					
							@if(!empty($item->tags))
								<?php $tags = explode(',',$item->tags) ?>
								@foreach($tags as $tag)
									<a href="/?tags={{ $tag }}" class="tag fa fa-tag" property="keywords">{{ $tag }}</a>
								@endforeach
							@endif
						</div>
						<div class="pull-right">
							<script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
							<div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="vkontakte,gplus,facebook,twitter"></div>
						</div>
						<div style="clear:both; "></div>
						<div class="col-md-12" style="margin-top:10px">	
							@if(!empty($closeArticles['prev']))		
								<a href="{{ $closeArticles['prev'] }}" class="pull-left"><i class="fa fa-arrow-left"></i> Предыдущая статья</a>
							@endif		
							@if(!empty($closeArticles['next']))						
								<a href="{{ $closeArticles['next'] }}" class="pull-right">Следующая статья <i class="fa fa-arrow-right"> </i></a>	
							@endif
						</div>
					</div>
				</div>
				@if($item->parent_folder_id!=0)
					@include('content.front.comments')
				@endif
			@endif
		</div>
		<div class="col-md-4">
			@include('content.front.rightblock')
		</div>
	</div>
@stop