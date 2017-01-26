<?php 
class Parser extends \Eloquent {
	/**
     * The database table used by the model.
     *
     * @var string
     */
	protected $table = 'parser';

    /**
     * Нужен для работы Input::all().
     *
     * @var array
     */
	protected $guarded = array('_token');

	public function getParserData($table_fields,$params){
		$sort = $params['sort'];
    	$order = $params['order'];
    	$field = $params['field'];
    	$search = $params['search'];
        
    	$sort_by = isset($table_fields[$sort])?$table_fields[$sort]:'id';  //set default sort
    	isset($table_fields[$field])?$search_field=$table_fields[$field]:$search_field='parser.id';
        if($search_field == 'created_at' || $search_field == 'updated_at'){
            $search_field = 'parser.'.$search_field;
        }

    	$result =  DB::table('parser')
    					->select('parser.*','users.username')
                        ->join('users','users.id','=','parser.author')
    					->where($search_field,'like','%'.$search.'%')
    					->orderBy($sort_by,$order)
    					->paginate(20)
						->appends(array('sort' => $sort, 'order' => $order, 'field' =>$field, 'search' => $search));

        return $result;
	}
}