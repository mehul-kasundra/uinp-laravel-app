{{ '<?xml version="1.0" encoding="utf-8" ?>'."\n" }}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">	
	@if(!empty($folders))
		@foreach($folders as $folder)
			<url>
				<loc>{{ URL::to('/').'/'.$folder->path }}</loc>
				<lastmod>{{ date('Y-m-d',strtotime($folder->updated_at)) }}</lastmod>
				<changefreq>hourly</changefreq>
			</url>
		@endforeach
	@endif
	@if(!empty($articles))
		@foreach($articles as $article)
			<url>
				<?php $path = !empty($article->path)?'/'.$article->path:''; ?>
				<loc>{{ URL::to('/').$path.'/'.$article->alias }}</loc>
				<lastmod>{{ date('Y-m-d',strtotime($article->updated_at)) }}</lastmod>
				<changefreq>daily</changefreq>
			</url>
		@endforeach
	@endif
</urlset> 