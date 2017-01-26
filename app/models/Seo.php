<?php

class Seo extends \Eloquent {
	protected $table = 'seo';
	protected $guarded = array('_token');

	public function getUrltable($table_fields,$params){
		$sort = $params['sort'];
    	$order = $params['order'];
    	$field = $params['field'];
    	$search = $params['search'];
    	$sort_by = isset($table_fields[$sort])?$table_fields[$sort]:'id';  //set default sort
    	isset($table_fields[$field])?$search_field=$table_fields[$field]:$search_field='id';

    	$items =  DB::table($this->table)
    					->select('*')
    					->where('url','!=','')
    					->where($search_field,'like','%'.$search.'%')
    					->orderBy($sort_by,$order)
    					->paginate(20)
						->appends(array('sort' => $sort, 'order' => $order, 'field' =>$field, 'search' => $search));
        return $items;
	}
}