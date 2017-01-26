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
	<div class="row">
		<div class="col-md-8">
			@if (isset($item) && count($item))
				<div class="row">
					<div class="col-md-12 text-justify">
						<h1>{{ $item->title }}</h1>
					</div>
				</div>
			@endif

			@if (count($children))
				@foreach ($children as $article)
					<div class="col-md-12">
						@include('content.front.articlepreview')
					</div>
				@endforeach
				<div class="col-md-12">
					<div>{{ $children->links() }}</div>
				</div>
			@endif

			@if(isset($item->text) && !empty($item->text))
				<div class="row"> 
					<div class="col-md-12 text-justify folder-desc">
						{{ $item->text }}
					</div>
				</div>
			@endif

			@if (count($folders))
				@foreach ($folders as $folder)
					<div class="col-md-12">
						@include('content.front.folderpreview')
					</div>
				@endforeach
				<div class="col-md-12">
					{{ $folders->links() }}
				</div>
			@endif
		</div>
		<div class="col-md-4">
			@include('content.front.rightblock')
		</div>
	</div>
@stop
