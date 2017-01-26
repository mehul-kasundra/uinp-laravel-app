<?php

class Mumble extends \Eloquent {
	protected $table = 'mumble';
    protected $guarded = array('_token');

	public function getMumble($table_fields,$params){
		$sort = $params['sort'];
    	$order = $params['order'];
    	$field = $params['field'];
    	$search = $params['search'];
        
    	$sort_by = isset($table_fields[$sort])?$table_fields[$sort]:'id';  //set default sort
    	isset($table_fields[$field])?$search_field=$table_fields[$field]:$search_field='id';
        if($search_field == 'created_at' || $search_field == 'updated_at'){
            $search_field = $search_field;
        }
    	$mumble =  DB::table('mumble')
    					->select('*')
    					->where($search_field,'like','%'.$search.'%')
    					->orderBy($sort_by,$order)
    					->paginate(20)
						->appends(array('sort' => $sort, 'order' => $order, 'field' =>$field, 'search' => $search));
        return $mumble;
	}
}