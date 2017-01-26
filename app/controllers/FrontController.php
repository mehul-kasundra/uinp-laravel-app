<?php

class FrontController extends BaseController {

	public function __construct(){
		$this->getUrlseo();		
		$this->getMenus();
		$this->startTime();
		if(Request::is('/')){
			View::share('video', $this->getChildren('video',4,false));
			View::share('accidentNews', $this->getChildren('accident',4,false));
			View::share('rusvesna', $this->getChildren('russkaya_vesna_proekt',4,false));
			View::share('dnr', $this->getChildren('dnr',4,false));
			View::share('vidverto', $this->getChildren('vidverto',4,false));
		}
		View::share('crimea', $this->getChildren('crimea',4,false));    
		View::share('weeklyNews', $this->getChildren('weekly_news',4,false));
		$this->endTime('правая панель');
	}

	private function getUrlseo(){
		$model = new Seo;
		$this->seo = $model->where('url',Request::url())->first();
	}

	private function getMenus(){
		$result = Menu::all();
		$menus = new stdClass();
		
		foreach ($result as $value) {
			$key = $value->name;
			$menus->$key = $value->content;
		}
		View::share('menu', $menus);
	}


	public function getIndex()
	{
		$model = new Article;	
		$tags = Input::get('tags');
		if(!empty($tags)){					
			$articles = $model->getTagArticles($tags);
			$breadcrumb = View::make('content.front.breadcrumb',array('items'=>'<li><span>теги: '.$tags.'</span></li>'));
			return View::make('content.front.tags',compact('articles','tags','breadcrumb'));
		}

		$search = Input::get('search');
		if(!empty($search)){			
			$articles = $model->searchArticles($search);
			$breadcrumb = View::make('content.front.breadcrumb',array('items'=>'<li><span>поиск: '.$search.'</span></li>'));
			return View::make('content.front.search',compact('articles','search','breadcrumb'));
		}

		$arhiv = Input::get('arhiv');
		if(!empty($arhiv)){			
			$articles = $model->getArticleByDate($arhiv);
			$breadcrumb = View::make('content.front.breadcrumb',array('items'=>'<li><span>Архив: '.$arhiv.'</span></li>'));
			return View::make('content.front.arhiv',compact('articles','arhiv','breadcrumb'));
		}

		$rss = Input::get('rss');
		if(!empty($rss)){			
			$articles = $model->getLastarticles($rss);
			$content = View::make('content.front.rss',compact('articles'));
			return Response::make($content, '200')->header('Content-Type', 'text/xml');
		}
		$this->startTime();
		$worldNews 		= $this->getChildren('world_news',4,false);
		$importantNews 	= $this->getChildren('important_news',4,false);	
		$this->endTime('мировые и важные');
		$this->startTime();
		$articles = $model->getLastarticles();
		$this->endTime('последние новости');
		$seoData = $this->seo;

		$mainArticle = Article::where('alias','na_glavnoi')->first();	
		$breadcrumb = View::make('content.front.breadcrumb');
		return View::make('content.front.index',compact('articles','mainArticle','importantNews','worldNews','breadcrumb','seoData'));
	}

	public function missingMethod($items = array())
	{
		if(!empty($items)){
			$items = $this->getUrlElements($items);	
			$breadcrumb = View::make('content.front.breadcrumb',compact('items'));

			$item = array_pop($items);			

			if(!empty($this->seo->seo_title)){
				$item->title = $this->seo->seo_title;
			}								
			if(!empty($this->seo->description)){
				$item->description = $this->seo->description;
			}
			if(!empty($this->seo->keywords)){
				$item->keywords = $this->seo->keywords;
			}
			if(!empty($this->seo->img_alt)){
				$item->img_alt = $this->seo->img_alt;
			}
			if(!empty($this->seo->img_title)){
				$item->img_title = $this->seo->img_title;
			}										
			$this->startTime();
			if($item->table=='folders'){
				$model = new Folder;
				$folders = 	$model->getFoldersByParentId($item->id);			
				$children = $this->getChildren($item->alias);
				$this->endTime('левый блок');
				return View::make('content.front.folder',compact('item','folders','children','breadcrumb'));
			} else {
				$model = new Comment;
				$comments = $model->getCommentsByElement($item->table,$item->id);
				$closeArticles = $this->nextArticle($item->id);
				$this->endTime('новость');
				return View::make('content.front.article',compact('item','breadcrumb','comments','closeArticles'));
			}
		}			
		App::abort(404);
	}

	/*
	* Собирает элементы из URL
	* $items - array
	* Return Array
	*/
	private function getUrlElements($items){		
		$result = array();
		foreach ($items as $key => $val) {			
			if(isset($items[$key+1])) {									//все элементы кроме последнего						
				$item = $this->getFolderByAlias($val);			
			} else {													//последний элемент
				$item = $this->getElementByAlias($val);
			}
			$parent = $result;			
			$parent = array_pop($parent);
			if(!$this->checkElement($item,$parent)){
				App::abort(404);
			}
			$result[] = $item;
		}
		return $result;
	}


	/*
	* Проверяет правильность пути (родитель-потомок)
	*/
	private function checkElement($item,$parent){
		if($item->parent_folder_id!=0 && empty($parent)){				//если id родителя первого элемента не 0
			return false;
		}
		if(!empty($parent) && $item->parent_folder_id!=$parent->id){	//если это не первый элемент и Id его родителя неверно
			return false;
		}
		return true;
	}

	private function getElementByAlias($alias){
		$element = Alias::where('alias',$alias)->first();
		if(empty($element)){
			App::abort(404);			
		}
		$tablePrefix = DB::getTablePrefix();
		$result = DB::table($element->table)
			->select($element->table.'.*','users.username','users.google_account','users.thumb as userthumb','seo.seo_title','seo.keywords','seo.description','seo.img_alt','seo.img_title', DB::raw('GROUP_CONCAT('.$tablePrefix.'tags.name) as tags'))
			->join('users','users.id','=',$element->table.'.user_id')
			->leftjoin('seo','seo.item_id','=',DB::raw($tablePrefix.$element->table.'.id AND '.$tablePrefix.'seo.table = "'.$element->table.'"'))
			->leftjoin('tagstoelement','tagstoelement.element_id','=',DB::raw($tablePrefix.$element->table.'.id AND '.$tablePrefix.'tagstoelement.table = "'.$element->table.'"'))
			->leftjoin('tags','tags.id','=','tagstoelement.tag_id')
			->where($element->table.'.id',$element->item_id)
			->first();
		if(empty($result)){
			App::abort(404);			
		}
		$result->table = $element->table;				
		return $result;
	}

	private function getFolderByAlias($alias){
		$item = Folder::where('alias',$alias)->first();
		if(empty($item)){
			App::abort(404);			
		}
		$item->table = 'folders';
		return $item;
	}

	private function getChildren($parentAlias,$limit=0,$author=true){
		$model = new Article;
		$result = $model->getArticlesByParentAlias($parentAlias,$limit,$author);		
		return $result;
	}

	public function Sitemap(){
		$folders = Folder::all();
		$articles = DB::table('articles')->select('articles.*','folders.path')->leftjoin('folders','folders.id','=','articles.parent_folder_id')->get();
		$content = View::make('content.front.sitemap',compact('folders','articles'));
		return Response::make($content, '200')->header('Content-Type', 'text/xml');
	}

	private function nextArticle($id){
		return array(
			'next'	=> $this->getArticlePath($id+1),
			'prev'	=> $this->getArticlePath($id-1),
		);
	}

	private function getArticlePath($id){
		$article = Article::where('id',$id)->where('published_at','!=','0000-00-00 00:00:00')->first();
		if(empty($article)){
			return false;
		}
		$folder = Folder::find($article->parent_folder_id);
		if(empty($folder)){
			return false;
		}
		return '/'.$folder->path.'/'.$article->alias;
	}
}