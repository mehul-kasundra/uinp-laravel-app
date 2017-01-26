<?php

class ArticleController extends \BaseController {

	protected $rules = array(
		'title'		=> 'max:255|required',
		'alias'		=> 'max:255|required|unique:articles,alias',
		'video'		=> 'max:255',		
	);
	protected $table_fields = array(
			'Title'		=> 'title',
			'Author'	=> 'users.username',
			'Source'	=> 'source',
			'Created'	=> 'created_at',
			'Published'	=> 'published_at',
		);
	protected $dontFlash = array('file');

	/**
	* Display a listing of articles
	*
	* @return Response
	*/

	public function getIndex()
	{
		$model = new Article;
		$params = array(
			'sort' 		=> Input::get('sort'),
	    	'order' 	=> Input::get('order'),
	    	'field' 	=> Input::get('field'),
	    	'search' 	=> Input::get('search'),
    	);
		$table_fields = $this->table_fields;

        $articles = $model->getArticles($table_fields,$params);

		return View::make('content.admin.atricles.index', compact('articles','table_fields'));
	}


	/**
	 * Show the form for creating a new article
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$folders = Folder::orderBy('title','ASC')->get();
		$parent = 0;
		return View::make('content.admin.atricles.form')->nest('tree','content.admin.tree',compact('folders','parent'));
	}

	/**
	 * Store an article in the storage.
	 *
	 * @return Response
	 */
	public function postStore($data='')
	{
		$this->rules['alias']	= 'max:255|required|unique:aliases,alias';

		if(empty($data)){
			$tags 				= Input::get('tags');
			$parentFolderId 	= Input::get('folder_id');
			$title 				= Input::get('title');
			$alias 				= Input::get('alias');
			$userId				= Auth::user()->id;
			$content 			= Input::get('content');
			$publish 			= Input::get('publishnow');
			$video 				= Input::get('video');
			$removeLinks 		= Input::get('removelinks');
			$source				= 'Admin created';

			$socialParams['vk'] = Input::get('vkcheckbox');

			if(Input::hasFile('userfile')) {
				$image = Common_helper::fileUpload(Input::file('userfile'),'articles',$alias);
			}	
			$validator = Validator::make(Input::all(), $this->rules);
		} else {
			$tags 				= $data['keywords'];
			$video				= '';
			$parentFolderId 	= $data['parent_folder_id'];
			$title 				= $data['title'];
			$alias 				= $data['alias'];
			$userId				= $data['user_id'];
			$content 			= $data['content'];
			$publish 			= $data['publish'];
			$removeLinks 		= $data['removelinks'];
			$image 				= $data['image'];
			$source				= $data['source'];

			$socialParams['vk'] = $data['vk'];

			$validator = Validator::make($data, $this->rules);
		}

		if ($validator->fails()){
			if(empty($data)){
				return Redirect::back()->withErrors($validator)->withInput(Input::except('userfile'));
			}  
			return false;
		} else {			
			$model = new Article;
	        $model->title   			= $title;
	        $model->alias   			= $alias;
	        $model->user_id 			= $userId;  
		    $model->video 				= $video;
		    $model->parent_folder_id 	= $parentFolderId;
		    $model->source 				= $source;

		    if($removeLinks == 1){
				$model->content = preg_replace('/<a.*<\/a>/','',$content);
			} else {
				$model->content = $content;
			}
		    if($publish == 1){
		    	$model->published_at = date('Y-m-d H:i:s');
		    }	    

			if(isset($image) && !empty($image)){		
				if(isset($image['errors'])){
					if(empty($data)){
						return Redirect::back()->withErrors($image['errors'])->withInput(Input::except('userfile'));
					}
					return false;
				}			
				$model->image 		= $image['path'];
				if(!empty($image['path'])){
		    		$model->thumb 	= $this->createThumb($image,80,80,true);
		    	}
			}
        	$model->save();

        	$this->saveAlias($alias,$model->id,'articles',$parentFolderId);
  
        	$TagController = new TagController;
        	$TagController->addTags($tags,'articles',$model->id);

        	$this->saveSeo($model->id,'articles',$data);	
  
	        $this->postSocNetworks($model,$tags,$socialParams);
        	  	
		}
		if(empty($data)){
			Session::flash('success', 'Successfully created!');
			return Redirect::to('admin/articles');
		}
		return true;
	}

	/**
	 * Post Article to social networks
	 *
	 * @param  Article  $model
	 * @return none
	 */
	private function postSocNetworks($model,$tags,$params){
		$text = $model->content;

		$link = '';
		if($model->parent_folder_id != 0){
			$parentPath = Folder::where('id',$model->parent_folder_id)->first()->path;
			$link = URL::to('/').'/'.$parentPath.'/'.$model->alias;		
		}
		
		if($params['vk']==1){
			if(!empty($link)){
				$text = $link." \n".$text;
				$text = $model->title." \n".$text;
			}
			$this->vkwallpost($text, $model->image, explode(',',$tags));			
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getEdit($id='')
	{
		$model = new Article;
		$article = $model->getArticleWhithSeo($id);
		if(!empty($article)){
			$folders = Folder::all();
			$TagController = new TagController;
        	$tags = $TagController->getTags($id,'articles');
			$parent = $article->parent_folder_id;	
		
			return View::make('content.admin.atricles.form', compact('article','tags'))->nest('tree','content.admin.tree',compact('folders','parent'));
		} else {
			App::abort(404);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function putUpdate($id)
	{		
		$model = Article::findOrFail($id);
		$tags = Input::get('tags');
		$parent_folder_id 	= Input::get('folder_id');

		$content = Input::get('content');
		if(Input::get('removelinks')){
			$content = preg_replace('/<a.*<\/a>/','',$content);
		}

		$alias = Alias::where('item_id',$id)->where('table','articles')->first();					
		if(isset($alias->id) && !empty($alias->id)){		
			$this->rules['alias']	= 'max:255|required|unique:aliases,alias,'.$alias->id;
		}

		$validator = Validator::make($data = Input::all(), $this->rules);
		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput(Input::except('userfile'));
		} else {

			$data = array(
		        'title'   			=> Input::get('title'),
		        'alias'   			=> Input::get('alias'),
		        'content'			=> $content,
		        'video'				=> Input::get('video'),
		        'parent_folder_id' 	=> $parent_folder_id,
	        );

	        if(Input::get('publishnow')){
		    	$data['published_at'] = date('Y-m-d H:i:s');
		    }
			
			if (Input::hasFile('userfile')) {
				$image = Common_helper::fileUpload(Input::file('userfile'),'articles/'.$data['alias'],Input::get('alias'));
				if(isset($image['errors'])){
					return Redirect::back()->withErrors($image['errors'])->withInput(Input::except('userfile'));
				}
		        $data['image'] = $image['path'];
		        $data['thumb'] = $this->createThumb($image,80,80,true);
		        $image = $image['path'];
			} else {
				$image = Input::get('image');
					if(empty($image)) {
						$data['image'] = '';
						$data['thumb'] = '';
					}
			}

        	$model->update($data);

        	$TagController = new TagController;
        	$TagController->addTags($tags,'articles',$model->id);

        	if(!empty($alias)){
        		$this->updateAlias($alias->id,Input::get('alias'),$parent_folder_id);
        	} else {
        		$this->saveAlias(Input::get('alias'),$model->id,'articles',$parent_folder_id);
        	}

        	$this->saveSeo($id,'articles');
		}
		Session::flash('success', 'Successfully updated!');
		return Redirect::to('admin/articles');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteDestroy($id)
	{
		Article::destroy($id);
		Alias::where('item_id',$id)->where('table','articles')->delete();
		Seo::where('item_id',$id)->where('table','articles')->delete();
		Session::flash('success', 'Successfully deleted!');
		return Redirect::back();
	}

	/**
	 * Post Article to VK
	 * @param  string  $text
	 * @param  string  $image
	 * @param  array   $tags
	 * @return string
	*/	
	public function vkwallpost($text, $image='', $tags='',$postId=false){
		//если задан postId - обновит существующую запись		
		$accessToken = Config::get('site_keys.vk.accessToken');
		$model = new Vkontakte(array('access_token' => $accessToken));
		$publicID = Config::get('site_keys.vk.publicID');
		$text = strip_tags($text);
		$postId = $model->postToPublic($publicID, $text, $image, $tags, $postId);
		return $postId;
	}
}