{{ '<?xml version="1.0" encoding="utf-8" ?>'."\n" }}
   <rss version="2.0">
        <channel>
           <title>UINP</title>
           <link>{{ URL::to('/') }}</link>
           <description>Украинский независимый новосной портал</description>
           <image>
				<url>http://uinp.loc/assets/images/uinplogo.png</url>
				<title>UINP</title>
				<link>http://uinp.info</link>
			</image>
			<generator>Simple Laravel CMS</generator>
			<ttl>30</ttl>

			@if(!empty($articles))
				@foreach($articles as $item)
					<item>
						<title>{{ Common_helper::escapeXMLcars($item->title) }}</title>
						<link>{{ URL::to('/').'/'.$item->path.'/'.$item->alias }}</link>
						<category>{{ $item->parent_title }}</category>
						<description>{{ Common_helper::escapeXMLcars($item->content) }}</description>
						<author>{{ $item->username }}</author>
						<pubDate>{{ date('r', strtotime($item->published_at)); }}</pubDate>
						<enclosure url="{{ URL::to('/').'/uploads/articles/'.$item->alias.'.jpg' }}" type="image/jpeg"/>
					</item>
				@endforeach
			@endif
          
       </channel>
  </rss>