<?php

class Comment extends Eloquent {

	/**
	 * Нужен для работы Input::all().
	 *
	 * @var array
	 */
	protected $guarded = array('_token');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'comments';

	/**
	 * get all comments
	 * @param  int  $id
	 * @return Obj	 
	 */
	public function getCommentById($id){
		$comment = DB::table($this->table)
			->select('comments.*','users.username')
			->join('users','users.id','=','comments.user_id')
			->where('comments.id',$id)
			->first();
			return $comment;
	}

	/**
	 * get comments by table and element id
	 * @param  string  $table
	 * @param  int  $id
	 * @return Obj	 
	 */
	public function getCommentsByElement($table,$id){
		return DB::table($this->table)->select('comments.*','users.username')
				->join('users','users.id','=','comments.user_id')
				->where('comments.table', $table)
				->where('comments.item_id',$id)
				->orderby('id','DESC')
				->paginate(10);
	}


	/**
	 * get all comments
	 * @param  array  $table_fields
	 * @param  array  $params
	 * @return Response	 
	 */
	public function getComments($table_fields,$params){

		$items = Config::get('site_config.entities');
    	if(!empty($items)){
			$sort = $params['sort'];
	    	$order = $params['order'];
	    	$field = $params['field'];
	    	$search = $params['search'];
	    	$sort_by = isset($table_fields[$sort])?$table_fields[$sort]:'comments.id';  //set default sort
	    	isset($table_fields[$field])?$search_field=$table_fields[$field]:$search_field='comments.id';

	    	$comments =  DB::table($this->table);

	    	$select = array(
	    			'comments.*',
	    			'users.username',
	    			'folders.path as path'
	    		);

	    	foreach ($items as $key => $val) {
		    	$comments = $comments
		    		->leftJoin($key,'comments.item_id','=',$key.'.id')
					->leftJoin('folders',$key.'.parent_folder_id','=','folders.id');
				$select[]= $key.'.title';
				$select[]= $key.'.alias';
	    	}

	    	$comments = $comments->select($select)
	    					->join('users','comments.user_id','=','users.id')    					
	    					->where($search_field,'like','%'.$search.'%')
	    					->orderBy($sort_by,$order)
	    					->paginate(20)
							->appends(array('sort' => $sort, 'order' => $order, 'field' =>$field, 'search' => $search));
        	return $comments;
    	}
    	return false;
	}	

}