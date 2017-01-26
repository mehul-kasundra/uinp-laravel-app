@extends('containers.frontend')
@section('title')
	@if(isset($seoData->seo_title))
		{{ $seoData->seo_title }}
	@endif
@stop
@section('seoMeta')
	@if(isset($seoData->description))
    	<meta name="description" content="{{ $seoData->description }}">
   	@endif
   	@if(isset($seoData->keywords))
    	<meta name="keywords" content="{{ $seoData->keywords }}">
    @endif
@stop
@section('main')
	<div class="row">
		<div class="col-md-8">		
			@if (isset($importantNews) && !empty($importantNews))
				<div class="row">
					<div class="col-md-12">
						<div class="heading">Важные новости</div>
						<ul class="pgwSlider">
							@foreach ($importantNews as $article)
								<li>
									<a href="/{{ $article->path }}/{{ $article->alias }}">
							            <img src="{{ $article->image }}">
								    <span>{{ $article->title }}</span>
							        </a>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			@endif
			@if (isset($articles) && !empty($articles))
				<div class="row">
					<div class="col-md-12">
						<div class="heading">
							<div class="pull-left"><a href="?rss=last_news" class="fa fa-rss rss-icon" title="RSS"></a></div>
							Последние новости 
							<div class="view-style pull-right">
								<i class="fa fa-bars" title="Список"></i>
								<i class="fa fa-th-large" title="Колонки"></i> 
							</div>
						</div>
					</div>
					<div class="recent-news col-md-6">
					@foreach ($articles as $key => $article)
						@if($key == 10)
							</div>
							<div class="recent-news col-md-6">
						@endif
						@include('content.front.articlepreview')
					@endforeach
					</div>
					<div class="col-md-12">
						
					</div>
				</div>
			@endif
		</div>
		<div class="col-md-4">
			@include('content.front.rightblock')
		</div>
	</div>
	@if (isset($worldNews) && !empty($worldNews))
		<div class="row">
			<div class="col-md-12">
				<div class="heading">Новости в мире</div>
				<div class="row">
					@foreach ($worldNews as $article)
						<div class="col-md-3">
							@include('content.front.articlepreview')
						</div>
					@endforeach
				</div>
			</div>
		</div>
	@endif
	@if (isset($mainArticle) && !empty($mainArticle))
		<h1>{{ $mainArticle->title }}</h1>
		<div class="text-justify">{{ $mainArticle->content }}</div>
	@endif
	
@stop
@section('styles')
	<link rel="stylesheet" type="text/css" media="all" href="/packages/pgwSlider/pgwslider.min.css">
@stop
@section('scripts')
	<script type="text/javascript" src="/packages/pgwSlider/pgwslider.min.js"></script>
	<script>
		$(document).ready(function(){
			$('.pgwSlider').pgwSlider({
				maxHeight:400
			});

			$('.view-style .fa-bars').on('click',function(){
				$('.recent-news').removeClass('col-md-6');
				$('.recent-news').addClass('col-md-12');
			});

			$('.view-style .fa-th-large').on('click',function(){
				$('.recent-news').removeClass('col-md-12');
				$('.recent-news').addClass('col-md-6');
			});
		});
	</script>
@stop