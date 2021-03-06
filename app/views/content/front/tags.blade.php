@extends('containers.frontend')
@section('title')Теги: {{ $tags }}@stop
@section('main')
	<div class="row">
		<div class="col-md-8">
			<h1 class="text-center">ности по теме: {{ $tags }}</h1>	
			@if (count($articles))
				@foreach ($articles as $article)
					<div class="col-md-12">
						@include('content.front.articlepreview')
					</div>
				@endforeach
				<div class="col-md-12">
					{{ $articles->links() }}
				</div>
			@else
				<h4 class="text-center">Новости отсутствуют</h4>
			@endif
		</div>
		<div class="col-md-4">
			@include('content.front.rightblock')
		</div>
	</div>
@stop