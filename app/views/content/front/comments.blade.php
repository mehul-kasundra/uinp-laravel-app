@section('styles')
<link rel="stylesheet" type="text/css" media="all" href="/packages/fancyBox/jquery.fancybox.css">
@stop
@section('scripts')
<script src="/packages/fancyBox/jquery.fancybox.pack.js"></script>
	<script>
		$(document).ready(function() {
			$('.login').fancybox({
				type: 'ajax'
			});
		});

		function addComment(){
			$('#username, #email, #password').css('outline','none');
			$('.register-error').remove();
			var url = '/comments/store';
			var content = $('.comment-textarea').val();			
			@if(Auth::guest())
				var username = $('#username').val();
				var email = $('#email').val();
				var password = $('#password').val();
				var ajaxSave = saveComment(url,content,'',username,email,password);				
			@else
				var ajaxSave = saveComment(url,content);
			@endif

			if(ajaxSave!==undefined && ajaxSave.success!==undefined){				
				ajaxSave.success(function(result){
					if(result.error){
						$.each(result.error, function(key, val){
							$('#'+key).css('outline','solid 1px red');
							$('#'+key).after('<div class="register-error"> '+val+'</div>');
						});						
					} else if(result.template!==undefined) {
						$('.comments-cont').prepend(result.template);
						$('.comment-textarea').val('');
						$('.none-comments').remove();
						$('.comment-user-form').after('Вы вошли как: '+result.username+' <a href="/auth/logout">Выход</a>');
						$('.comment-user-form').remove();

					}
				});
			}					
		}

		function checkFields(username,email,password){
			if(username===undefined || username==''){			
				$('#username').css('outline','solid 1px red');
				var error=true;
			}
			if(email===undefined || email==''){			
				$('#email').css('outline','solid 1px red');
				var error=true;
			}
			if(password===undefined || password==''){			
				$('#password').css('outline','solid 1px red');
				var error=true;
			}
			if(error===true){
				return false;
			}
			return true;
		}

		function editComment(elem,action){
			if(action=='save') {
				var url = '/comments/update';				
				var content = $(elem).parent().parent().parent().find('textarea').val();
				var id = $(elem).data('id');				
				var ajaxSave = saveComment(url,content,id);

				if(ajaxSave!==undefined && ajaxSave.success!==undefined){
					ajaxSave.success(function(result){
						if(result.success){
							$(elem).parent().parent().find('.content').html(content);
							$(elem).text('');
							$(elem).attr('onClick',"editComment(this)");
							$(elem).addClass('fa fa-pencil');
						}
					});
				}
			} else {		
				var content = $(elem).parent().parent().find('.content');			
				content.html('<div><textarea class="form-control">'+content.text().trim()+'</textarea></div>');
				$(elem).text('Сохранить');
				$(elem).attr('onClick',"editComment(this,'save')");
				$(elem).attr('class','pointer');
			} 
		}

		function saveComment(url,content,id,username,email,password){			
			var table = '{{ $item->table }}';
			var itemId = '{{ $item->id }}';			
			if(content!=''){
				return $.ajax({
					type: 'post',
					dataType: 'json',
					url: url,
					data: {
						'id': id,
						'table': table,
						'itemId': itemId,
						'content': content,
						'username': username,
						'email': email,
						'password': password
					}
				});
			} else {
				alert('Введите текст комментария')
			}
		}

		function deleteComment(elem){
			if (confirm("Удалить?")){
				$.ajax({
					type: 'delete',
					url: '/comments/destroy/'+$(elem).data('id'),
					success: function(){
						$(elem).parent().parent().parent().remove();
					} 
				});
			}
		}
	</script>
@stop
<hr>
@if(isset($comments) && count($comments))
	<h4>Комментрарии:</h4>
	<div class="comments-cont">
		@foreach($comments as $comment)
			@include('content.front.comment')
		@endforeach
	</div>
	{{ $comments->links() }}
@else
	<div class="comments-cont">
		<div class="none-comments">
			Комментарии отсутствуют
		</div>
	</div>
	<hr>
@endif	

<div class="comment-form">
	@if(Auth::guest())
		<div>
			<div class="comment-user-form">
				<div class='form-group'>					
					{{ Form::label('username', 'Имя*', array('class' => 'pull-left')) }}
					<div>
				    	{{ Form::text('username', null) }}
				    </div>
				</div>
				<div class='form-group'>
				    {{ Form::label('email', 'Email*', array('class' => 'pull-left')) }}
				    <div>
				    	{{ Form::text('email', null) }}
				    </div>
				</div>
				<div class='form-group'>
				    {{ Form::label('password', 'Пароль*', array('class' => 'pull-left')) }}
				    <div>
				    	{{ Form::password('password', null) }}
					</div>
				</div>
				<div style="margin-top:20px">
					<div>Зарегистрированы?</div>
					<a href="/auth" class="button login">Войти</a>
				</div>
			</div> 
		</div>
	@else
		Вы вошли как: {{ Auth::user()->username }} 
		<a href="/auth/logout">Выход</a>
	@endif	
	<div class='form-group'>
		{{ Form::label('content', 'Оставить комментарий (до 1000 символов)') }}
	    {{ Form::textarea('content', null, array('class' => 'comment-textarea')) }}
	    {{ Form::hidden('item_id',  $item->id) }}
	</div>
	<div class='form-group'>
	    {{ Form::button('Отправить', array('class' => 'comment-submit button', 'onClick' => 'addComment()')) }}
	</div>
</div>
