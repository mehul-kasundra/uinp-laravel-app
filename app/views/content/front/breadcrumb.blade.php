<div class="menu-header">
	<div class="pull-left">НАВИГАЦИЯ</div>
	<div class="pull-right fa fa-bars show-menu"></div>
</div> 
<ul class="clear">
	<li><a href="/">Главная</a></li>	
	@if (isset($items) && !empty($items))
		@if(is_array($items))
			@foreach($items as $key => $item)
				@if(isset($items[$key+1]))
					<li><a href="/{{ $item->path }}">{{ $item->title }}</a></li>
				@else
					<li><span>{{ $item->title }}</span></li>
				@endif
			@endforeach
		@endif
		@if(is_string($items))
			{{ $items }}
		@endif
	@endif
</ul>
