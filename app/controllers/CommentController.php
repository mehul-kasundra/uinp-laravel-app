<?php

class CommentController extends BaseController {

	/**
	 * Validation rules.
	 *
	 * @var array
	 */
	protected $rules = array(
		'content'	=> 'required|max:1000|not_in:""',
	);

	/**
	 * Table fields and aliases.
	 *
	 * @var array
	 */
	protected $table_fields = array(
			'Author'		=> 'users.username',			
			'Created at'	=> 'comments.created_at',
			'Parent type'	=> 'table',
			'Parent'		=> 'title',
		);	

	/**
	* Display a listing of all comments
	*
	* @return Response
	*/
	public function getIndex()
	{
		if($this->is_admin()){
			$model = new Comment;
			$params = array(
				'sort' 		=> Input::get('sort'),
		    	'order' 	=> Input::get('order'),
		    	'field' 	=> Input::get('field'),
		    	'search' 	=> Input::get('search'),
	    	);
			$table_fields = $this->table_fields;

	        $comments = $model->getComments($table_fields,$params);

			return View::make('content.admin.comments.index', compact('comments','table_fields'));
		} else {
			return Redirect::to('/')->withErrors('Access denied!');
		}
	}

	/**
	 * Show the form for editing the comment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getEdit($id='')
	{		
		if(!empty($id)){
			$model = new Comment;
			$comment = $model->getCommentById($id);
			if(!empty($comment)){
				return View::make('content.admin.comments.form', compact('comment'));
			}
		}
		App::abort(404);
	}


	/**
	 * Store a newly created comment in storage.
	 *
	 * @return Response
	 */
	public function postStore()
	{	
		if(Auth::guest()){
			$userController = new UserController;
			$createUser = $userController->postStore(true);
			if($createUser!='success'){
				return $createUser;
			}
			if (!Auth::attempt(array('email'=>Input::get('email'), 'password'=>Input::get('password')))){
				return Response::json( array('error'=>'Auth user error') );
			}
		}

		$this->rules['itemId'] = 'required|max:99999999999|not_in:""';
		$this->rules['table'] = 'required|max:128|not_in:""';
		$validator = Validator::make(Input::all(), $this->rules);		
		if ($validator->fails()){
			return Response::json( array('error'=>$validator->failed()) );
		} else {			
			$comment = new Comment;	

	        $comment->user_id    	= Auth::User()->id;
	        $comment->table    		= Input::get('table');
	        $comment->item_id    	= Input::get('itemId');
	        $comment->content   	= Input::get('content');

        	$comment->save();
		}

		$comment->username = Auth::user()->username;
		$template = View::make("content.front.comment",array('comment'=>$comment))->render();

		return Response::json(array('template'=>$template,'username'=>Auth::user()->username));
	}

	/**
	 * Update a comment in storage.
	 *
	 * @return Response
	 */
	public function postUpdate(){
		$this->rules['id'] = 'required:not_in:""';
		$validator = Validator::make(Input::all(), $this->rules);

		if ($validator->fails()){
			if (Request::ajax()){
				return Response::json(array('error'=>$validator->failed()));
			} else {
				return Redirect::back()->withErrors($validator)->withInput();
			}	
		} else {
			$id = Input::get('id');		
			$content = Input::get('content');
			$comment = Comment::findOrFail($id);

			if($this->is_owner($comment->user_id)){
				$data['content'] = $content;
				$comment->update($data);
				if (Request::ajax()){
					return Response::json(array('success'=>'true'));
				} else {
					Session::flash('success', 'Comment updated!');
					return Redirect::to('admin/comments');
				}	
			} else {
				return Response::json(array('error'=>'Access denied!'));
			}			
		} 
	}

	/**
	 * Remove the comment from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteDestroy($id)
	{
		$comment = Comment::findOrFail($id);
		if($this->is_owner($comment->user_id)){
			Comment::destroy($id);
			if (!Request::ajax()){
				Session::flash('success', 'Comment deleted!');
				return Redirect::to('admin/comments');
			} else {
				return Response::make('success');
			}
		}
	}
}

