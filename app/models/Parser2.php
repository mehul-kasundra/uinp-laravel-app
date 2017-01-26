<?php 
class Parser2 extends \Eloquent {
	/**
     * The database table used by the model.
     *
     * @var string
     */
	protected $table = 'parser2';

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
    	$search_field = isset($table_fields[$field])?$table_fields[$field]:'id';
        if($search_field=='username'){
             $search_field = 'users.'. $search_field;
        } else {
             $search_field = 'parser2.'. $search_field;
        }

    	$result =  DB::table('parser2')
    					->select('parser2.*','users.username')
                        ->join('users','users.id','=','parser2.author')
    					->where($search_field,'like','%'.$search.'%')
    					->orderBy($sort_by,$order)
    					->paginate(20)
						->appends(array('sort' => $sort, 'order' => $order, 'field' =>$field, 'search' => $search));

        return $result;
	}
}