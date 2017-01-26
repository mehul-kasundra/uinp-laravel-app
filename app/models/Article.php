<?php

class Article extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $table = 'articles';

    /**
     * Нужен для работы Input::all().
     *
     * @var array
     */
	protected $guarded = array('_token');
	
    /**
     * get all articles
     * @param  array  $table_fields
     * @param  array  $params
     * @return Response  
     */
    public function getArticles($table_fields,$params){
		$sort = $params['sort'];
    	$order = $params['order'];
    	$field = $params['field'];
    	$search = $params['search'];

    	$sort_by = isset($table_fields[$sort])?$table_fields[$sort]:'id';  //set default sort
    	isset($table_fields[$field])?$search_field=$table_fields[$field]:$search_field='articles.id';
        if($search_field == 'created_at' || $search_field == 'updated_at'){
            $search_field = 'articles.'.$search_field;
        }

    	$articles =  DB::table('articles')
    					->join('users', 'users.id', '=', 'articles.user_id')
    					->select('articles.*', 'users.username as user','users.google_account')
    					->where($search_field,'like','%'.$search.'%')
    					->orderBy($sort_by,$order)
    					->paginate(20)
						->appends(array('sort' => $sort, 'order' => $order, 'field' =>$field, 'search' => $search));

        return $articles;
	}

    /**
     * Get Articles with current tag
     * @param  string  $tags
     * @return Obj  
     */ 
    public function getTagArticles($tags){
        return DB::table('tags')->select('articles.*','folders.path','users.username','users.google_account',DB::raw('count('.DB::getTablePrefix().'comments.id) as commentscount'))
                ->where('tags.name',$tags)
                ->join('tagstoelement','tagstoelement.tag_id','=','tags.id')
                ->leftJoin('articles','articles.id','=','tagstoelement.element_id')
                ->join('folders','folders.id','=','articles.parent_folder_id')
                ->join('users','users.id','=','articles.user_id')
                ->leftjoin('comments','comments.item_id','=',DB::raw(DB::getTablePrefix().'articles.id AND '.DB::getTablePrefix().'comments.table = "articles"'))
                ->where('articles.published_at','!=','0000-00-00 00:00:00')
                ->groupBy('articles.id')                
                ->orderby('published_at','DESC')
                ->paginate(10)
                ->appends(array('tags' => $tags));
    }

    /**
     * Search articles unction
     * @param  string  $search
     * @return Obj  
     */ 
    public function searchArticles($search){
        return DB::table('articles')->select('articles.*','folders.path','users.username','users.google_account',DB::raw('count('.DB::getTablePrefix().'comments.id) as commentscount'))
                    ->where('articles.content', 'LIKE', '%'.$search.'%')
                    ->join('folders','folders.id','=','articles.parent_folder_id')
                    ->join('users','users.id','=','articles.user_id')
                    ->leftjoin('comments','comments.item_id','=',DB::raw(DB::getTablePrefix().'articles.id AND '.DB::getTablePrefix().'comments.table = "articles"'))
                    ->where('articles.published_at','!=','0000-00-00 00:00:00')
                    ->groupBy('articles.id')
                    ->orderby('published_at','DESC')
                    ->paginate(10)
                    ->appends(array('search' => $search));
    }

    /**
     * Get last articles
     *
     * @return Obj  
     */
    public function getLastarticles(){
        $articles = DB::table('articles')->select('articles.id')
        ->where('articles.published_at','!=','0000-00-00 00:00:00')
        ->orderby('published_at','DESC')
        ->limit(20)
        ->get();

        if(!empty($articles)){
            foreach ($articles as $key => $val) {
                $atrticlesId[] = $val->id;
            }
            $result = DB::table('articles')
                ->select('articles.*','folders.path','folders.title as parent_title','users.username','users.google_account')                
                ->leftjoin('folders','folders.id','=','articles.parent_folder_id')
                ->leftjoin('users','users.id','=','articles.user_id')
                ->whereIn('articles.id',$atrticlesId)
                ->orderby('published_at','DESC')
                ->get();
            return $result;
        }
    }

    /**
     * Get all articles in folder
     *
     * @return Obj  
     */
    public function getArticlesByParentAlias($parentAlias,$limit=false,$author=true){
        $parent = Folder::where('alias',$parentAlias)->first();
        if(empty($parent)){
        	return false;
        }
        $select = array('articles.*');
        if($author){
            $select[] = 'users.username';
            $select[] = 'users.google_account';
        }
        $result =  DB::table('articles')
                    ->select($select);                    
        //var_dump($parent->id); exit;
        if($author){
            $result->join('users','users.id','=','articles.user_id');
        }
            $result->where('articles.parent_folder_id',$parent->id)
                    ->where('articles.published_at','!=','0000-00-00 00:00:00')
                    ->orderby('published_at','DESC');
        if($limit){
            $result = $result->take($limit)->get();  
        } else {
            $result = $result->simplePaginate(20);
        }
        foreach ($result as $key => $val) {
             $result[$key]->path = $parent->path;
        } 
        return $result;       
    }

    /**
     * Get article with seo by id
     *
     * @return Obj  
     */
    public function getArticleWhithSeo($id){
        $result =  DB::table('articles')->select('articles.*','seo.keywords','seo.description','seo.img_alt','seo.img_title','seo.seo_title','seo.id as seoid')                    
                    ->leftjoin('seo','seo.item_id','=',DB::raw(DB::getTablePrefix().'articles.id AND '.DB::getTablePrefix().'seo.table = "articles"'))
                    ->where('articles.id',$id)
                    ->first();
        return $result;
    }

    /**
     * Get arhiv articles 
     *
     * @param  string  $date
     * @return Obj  
     */
    public function getArticleByDate($date){
        return DB::table('articles')->select('articles.*','folders.path','users.username','users.google_account',DB::raw('count('.DB::getTablePrefix().'comments.id) as commentscount'))
                    ->where('articles.created_at', 'LIKE', '%'.$date.'%')
                    ->leftjoin('folders','folders.id','=','articles.parent_folder_id')
                    ->leftjoin('users','users.id','=','articles.user_id')
                    ->leftjoin('comments','comments.item_id','=',DB::raw(DB::getTablePrefix().'articles.id AND '.DB::getTablePrefix().'comments.table = "articles"'))
                    ->where('articles.published_at','!=','0000-00-00 00:00:00')
                    ->groupBy('articles.id')
                    ->orderby('published_at','DESC')
                    ->paginate(10)
                    ->appends(array('arhiv' => $date));
    }

}