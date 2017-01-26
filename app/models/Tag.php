<?php

class Tag extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $table = 'tags';
    protected $guarded = array('_token');

	public function getTags($table_fields,$params){
		$sort = $params['sort'];
    	$order = $params['order'];
    	$field = $params['field'];
    	$search = $params['search'];
        
    	$sort_by = isset($table_fields[$sort])?$table_fields[$sort]:'id';  //set default sort
    	isset($table_fields[$field])?$search_field=$table_fields[$field]:$search_field='id';

    	$tags =  DB::table($this->table)
    					->select('*')
    					->where($search_field,'like','%'.$search.'%')
    					->orderBy($sort_by,$order)
    					->paginate(20)
						->appends(array('sort' => $sort, 'order' => $order, 'field' =>$field, 'search' => $search));
        return $tags;
	}

	 /**
     * Save tags
     * @param  string  $tags
     * @return None  
     */
	public function saveTags($tags){
		$tags = strtolower($tags);
		$tags = trim($tags);
		$tags = preg_replace('/,/', "'),('", "('".$tags."')");
		DB::statement("INSERT INTO slc_tags (name) VALUES $tags ON DUPLICATE KEY UPDATE name = VALUES(name)");
	}

	/**
     * Get tags by id
     * @param  string  $tags
     * @return Obj  
     */	
	public function getTagsIds($tags){
		$tags = explode(',',$tags);
		$result = DB::table($this->table)->select()->whereIn('name', $tags)->get();
		return $result;
	}
}