<div class='comment' property="comment">
	<div class='body'>
		<b><i class='fa fa-user'></i> {{ $comment->username }}</b>, 
		<span style='font-size:10px'>{{ $comment->created_at }}</span>
		@if (!Auth::guest() && ($comment->user_id == Auth::User()->id || Auth::User()->role->id == 1))
			<div class='comment_actions pull-right'>
				<span onClick='editComment(this)' class='pointer comment_edit_button fa fa-pencil right5' data-id='{{ $comment->id }}' title='Редактировать'></span>
				<span onClick='deleteComment(this)' class='pointer comment_delete_button fa fa-times right5' data-id='{{ $comment->id }}' title='Удалить'></span>							
			</div>
		@endif
		<div class='content'>
			{{ $comment->content }}
		</div>
	</div>
</div>