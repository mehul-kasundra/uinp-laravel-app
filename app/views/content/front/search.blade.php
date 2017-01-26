@extends('containers.frontend')
@section('title')Поиск: {{ $search }}@stop
@section('main')
	<div class="row">
		<div class="col-md-8">
			<h1 class="text-center">вы искали: {{ $search }}</h1>	
			@if (!empty($articles))
				@foreach ($articles as $article)
					<div class="col-md-12">
						@include('content.front.articlepreview')
					</div>
				@endforeach
				<div class="col-md-12">
					{{ $articles->links() }}
				</div>
			@else
				<h4 class="text-center">Ничего не найдено</h4>
			@endif
		</div>
		<div class="col-md-4">
			@include('content.front.rightblock')
		</div>
	</div>
@stop