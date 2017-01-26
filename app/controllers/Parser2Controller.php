<?php 

use Yandex\Translate\Translator;
use Yandex\Translate\Exception;

class Parser2Controller extends BaseController {

	protected $rules = array(
		'url'		=> 'required',
	);
	protected $table_fields = array(
		'id'		=> 'id',
		'Url'		=> 'url',
		'Publish'	=> 'publish',
		'Translate'	=> 'translate',
		'Disabled'	=> 'disabled',
		'Author'	=> 'username',
	);


	/**
	* Display a listing of parser params
	*
	* @return Response
	*/
	public function getIndex()
	{
		$model = new Parser2;
		$params = array(
			'sort' 		=> Input::get('sort'),
	    	'order' 	=> Input::get('order'),
	    	'field' 	=> Input::get('field'),
	    	'search' 	=> Input::get('search'),
    	);
		$table_fields = $this->table_fields;

        $data = $model->getParserData($table_fields,$params);

		return View::make('content.admin.parser.index2', compact('data','table_fields'));
	}

	/**
	 * Show the form for creating a new parser data row
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$folders = Folder::orderBy('title','ASC')->get();
		$parent = 0;
		$tree =  View::make('content.admin.tree',compact('folders','parent'));
		$users = User::lists('username','id');
		return View::make('content.admin.parser.form2',compact('users','tree'));
	}

	/**
	 * Show the form for copy a new parser data row
	 *
	 * @return Response
	 */
	public function putCreate()
	{
		$data = Input::all();
		$folders = Folder::orderBy('title','ASC')->get();
		$parent = 0;
		$tree =  View::make('content.admin.tree',compact('folders','parent'));
		$users = User::lists('username','id');
		return View::make('content.admin.parser.form2',compact('users','tree','data'));
	}

	/**
	 * Store a newly created parser data in storage.
	 *
	 * @return Response
	 */
	public function postStore()
	{	
		$validator = Validator::make(Input::all(), $this->rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$model = new Parser2;
	        $model->url   			= Input::get('url');
	        $model->parse_url   	= Input::get('parse_url');
	        $model->author   		= Input::get('author');
	        $model->publish   		= Input::get('publish')?1:0;
	        $model->translate   	= Input::get('translate')?1:0;
	        $model->only_with_images= Input::get('only_with_images')?1:0;
	        $model->disabled   		= Input::get('disabled')?1:0;
	        $model->remove_links   	= Input::get('remove_links')?1:0;
	        $model->vk   			= Input::get('vk')?1:0;
	        $model->folder_id 		= Input::get('folder_id');
	        $model->min_chars 		= Input::get('min_chars');
	        $model->links_rule 		= Input::get('links_rule');
	        $model->title_rule 		= Input::get('title_rule');
	        $model->image_rule 		= Input::get('image_rule');
	        $model->text_rule 		= Input::get('text_rule');
	        $model->keywords_rule 	= Input::get('keywords_rule');
	        $model->description_rule= Input::get('description_rule');

        	$model->save();
		}

		Session::flash('success', 'Parser data successfully added!');
		return Redirect::to(URL::to('admin/parser2'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getEdit($id)
	{
		$parser = Parser2::find($id);
		if(empty($parser)){
			App::abort(404);
		}
		$folders = Folder::all();
		$parent = $parser->folder_id;
		$tree =  View::make('content.admin.tree',compact('folders','parent'));
		$users = User::lists('username','id');
		return View::make('content.admin.parser.form2', compact('parser','users','tree'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function putUpdate($id)
	{
		$model = Parser2::findOrFail($id);

		$validator = Validator::make($data = Input::all(), $this->rules);
		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		} else {

			$data = array(
		        'url'   			=> Input::get('url'),
		        'parse_url'			=> Input::get('parse_url'),
		        'author'   			=> Input::get('author'),
		        'publish'   		=> Input::get('publish'),
		        'translate'   		=> Input::get('translate'),
		        'only_with_images'	=> Input::get('only_with_images'),
		        'disabled'   		=> Input::get('disabled'),
		       	'remove_links'   	=> Input::get('remove_links'),
	        	'vk'   				=> Input::get('vk'),
	        	'folder_id' 		=> Input::get('folder_id'),
	        	'min_chars'			=> Input::get('min_chars'),
	        	'links_rule' 		=> Input::get('links_rule'),
	        	'title_rule' 		=> Input::get('title_rule'),
		        'image_rule' 		=> Input::get('image_rule'),
		        'text_rule' 		=> Input::get('text_rule'),
		        'keywords_rule' 	=> Input::get('keywords_rule'),
		        'description_rule'	=> Input::get('description_rule'),

	        );	        

        	$model->update($data);
		}
		Session::flash('success', 'Parser data successfully updated!');
		return Redirect::to(URL::to('admin/parser2'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteDestroy($id)
	{
		Parser2::destroy($id);
		Session::flash('success', 'Successfully deleted!');
		return Redirect::back();
	}


	private function curl_get_contents($url) {
		if (!function_exists('curl_init')){
		    die('CURL is not installed!');
		}
		$headers[]  = "User-Agent:Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13";
	    $headers[]  = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
	    $headers[]  = "Accept-Language:en-us,en;q=0.5";
	    $headers[]  = "Accept-Encoding:gzip,deflate";
	    $headers[]  = "Accept-Charset:ISO-8859-1,windows-1251,utf-8;q=0.7,*;q=0.7";
	    $headers[]  = "Keep-Alive:115";
	    $headers[]  = "Connection:keep-alive";
	    $headers[]  = "Cache-Control:max-age=0";

	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_ENCODING, "gzip");
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	    $data = curl_exec($curl);
	    curl_close($curl);
	    return $data;
	}

	private function curl($url){
		$curl = curl_init();
		curl_setopt_array($curl, Array(
		    CURLOPT_URL            	=> $url,
		    CURLOPT_RETURNTRANSFER 	=> TRUE,
		    CURLOPT_TIMEOUT			=> 400,
		    CURLOPT_ENCODING       	=> 'UTF-8'
		));
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}

	public function getParse($parserId='',$linksTest=''){
		ini_set('max_execution_time', 6000);
		header('Content-Type: text/html; charset=utf-8');
		include_once(app_path().'/helpers/simple_html_dom.php');
		$htmlDom = new simple_html_dom();

		if(empty($parserId)){
			$parserData = Parser2::all();
		} else {
			$parserData = array(Parser2::find($parserId));
		}
		if(!empty($parserData)){
			foreach ($parserData as $parserRow) {
				if($parserRow->disabled==0 || !empty($parserId)){
					$html = $this->curl_get_contents($parserRow->parse_url);
					$htmlDom->load($html);
					$links = $htmlDom->find($parserRow->links_rule);

					if(count($links) && empty($linksTest)){
						$itemsCount = $this->storeParsed($links,$parserRow,$parserId);
					} elseif(!empty($linksTest)) {
						foreach ($links as $link) {
							$link->href = $this->combineLinks($link->href,$parserRow->url);
							echo '<div><a href="'.$link->href.'">'.$link->href.'</a></div>';
						}
					} else {
						echo 'Не удалось получить список ссылок';
					}
				}
			}	
		}
		if(empty($parserId)){	
			return Redirect::to('/admin/parser2');
		}
	}

	private function combineLinks($link,$url){
		preg_match('/http/', $link, $matches);
    	if(!isset($matches[0])){
    		$link = $url.$link;
    	}
    	return $link;
	}

	private function removeTags($html){
		$res = preg_replace('/\<img.*\>/', '', $html);
		$res = preg_replace('/\<a.*\>(.*)\<\/a>/', '$1', $res);
		//$res = preg_replace('/\<.*\>.*\<\/.*\>/', '', $html);
		//$res = preg_replace('/\<.*\>/', '', $res);
		return $res;
	}

	private function findRatedTags($text){
		$ratedTags = Tag::where('frequency','>','0')->orderBy('summ_google','ASC')->get();
		$result = array();
		if(count($ratedTags)){
			foreach ($ratedTags as $key => $val) {
				if(preg_match('/'.$val->name.'/', (string)$text)){
					$result[] = $val->name;
				}
			}
		}
		// if(empty($result)){
		// 	$model = new CountKeywords;
		// 	$result = $model->get_keywords(mb_strtolower($text),3);
		// }
		return $result;
	}

	private function storeParsed($links,$parserRow,$parserId){
		//header('Content-Type: text/html; charset=utf-8');
		$htmlDom = new simple_html_dom();
		$i=0;

	    foreach($links as $link) {
	    	$link->href = $this->combineLinks($link->href,$parserRow->url);

	    	$articleHtml = $this->curl_get_contents($link->href);
	    	$htmlDom->load($articleHtml);

	    	$article['title'] 		= '';
			$article['content'] 	= '';
			$article['description'] = '';
			$article['image']		= '';
	    	$article['keywords']	= '';
	    	$article['source']		=  $parserRow->parse_url;

	    	//get content
	    	if(!empty($parserRow->text_rule)){
				$text = $htmlDom->find($parserRow->text_rule);
				if(count($text)){
					foreach ($text as $value) {
						$article['content'].= $value->plaintext;
					}
					$article['content'] = $this->createAbzac($article['content']);
					$ratedTags = $this->findRatedTags($article['content']);
				}
			}
			//get keywords
			if(!empty($parserRow->keywords_rule)){
		    	$keywords = $htmlDom->find($parserRow->keywords_rule);
		    	if(!empty($keywords)){
					foreach ($keywords as $keyword) {
						if(!empty($keyword->content)){
							$article['keywords'] = $keyword->content;
						}
						if(!empty($keyword->plaintext)){
							$article['keywords'].= $keyword->plaintext.',';
						}
					}
					trim($article['keywords'],',');
				}
			}
			if(isset($ratedTags) && !empty($ratedTags)){	//добавляем наиденные рейтинговые теги из базы
				$article['keywords'].=', '.implode(', ',$ratedTags); 
			}
			//get title
			if(!empty($parserRow->title_rule)){
	    		$article['title'] 	  = $htmlDom->find($parserRow->title_rule,0);
	    		if(!empty($article['title'])){
	    			$article['title'] = $article['title']->plaintext;
if(!empty($parserId)){	    			
	    			$mumble = Mumble::orderBy(DB::raw('RAND()'))->first()->content;
	    			$tags = explode(', ',$article['keywords']);
	    			$article['title'] = trim($article['title'],' ').'. '.$mumble.' '.array_pop($tags);
}
	    		}
	    	}
	    	//get description
			if(!empty($parserRow->description_rule)){
				$article['description'] 	= $htmlDom->find($parserRow->description_rule,0);
				if(!empty($article['description'])){
					$article['description'] = $article['description']->content;
				}
			}
			//get image
			if(!empty($parserRow->image_rule)){			// куча проверок из-за russvesna
				$article['image'] 	= $htmlDom->find($parserRow->image_rule,0);
				if(!empty($article['image'])){
					$article['image'] 	= $article['image']->src;
				}
				if(empty($article['image'])){
		    		$article['image'] = $htmlDom->find($parserRow->image_rule,1);
			    	if(!empty($article['image'])){
						$article['image'] = $htmlDom->find($parserRow->image_rule,1)->src;
					}
		    	}
		    	preg_match('/http/', $article['image'], $matches);
		    	if(!empty($article['image']) && !isset($matches[0])){
		    		$article['image'] = $parserRow->url.$article['image'];
		    	}
			}

	    	/*****Test*****/
			if(!empty($parserId)){
				echo '<h1>Парсим запись по линке</h1> <a href="'.$link->href.'">'.$link->href.'</a>';
				echo '<br><br><strong>Title: </strong>'.$article['title'];
				echo '<br><br><strong>Description: </strong>'.$article['description'];
				echo '<br><br><strong>Image: </strong><img src="'.$article['image'].'">';
				echo '<br><br><strong>Text: </strong>'.$article['content'];
				echo '<br><br><strong>Tags (Keywords): </strong> '.$article['keywords'];
				if(isset($ratedTags)){
					echo '<br><br><strong>Tags (Из базы): </strong> '.implode(', ',$ratedTags);	
				}
				
			} else {
				echo '<a href="'.$link->href.'">'.$link->href.'</a><br>';
				@ob_flush(); flush();
			}
			/**************/

	    	$article['user_id'] 			= $parserRow->author;
		    $article['created_at']			= date('Y-m-d H:i:s');
		    $article['updated_at']			= date('Y-m-d H:i:s');
		    $article['publish']				= $parserRow->publish;
		    $article['removelinks']			= $parserRow->remove_links;
		    $article['vk']					= $parserRow->vk;		    
		    $article['parent_folder_id'] 	= $parserRow->folder_id;

			$article['alias'] = $this->generateAlias($article['title']);			//Делаем алиас

		    if(!$this->aliasUnique($article['alias'])){
				/*****Test*****/
				//if(!empty($parserId)){
					echo '<div style="color:red">Уже сохранена</div><br>';
					@ob_flush(); flush();
				//}
				/*************/
		    	continue;
		    }
		    if($parserRow->min_chars > 0 && strlen($article['content']) < $parserRow->min_chars){
				/*****Test*****/
				//if(!empty($parserId)){
					echo '<div style="color:red">Недостаточно символов</div><br>';
					@ob_flush(); flush();
				//}
				/*************/	
				continue;
			}

		    if(!empty($article['image'])){
		    	$imageName = $article['alias'];
		    	if(strlen($imageName)>128){
		    		$imageName = substr($imageName, 0, 128);
		    	}
			    $imagePath = 'uploads/articles/'.$imageName.'.jpg';
			    if(empty($parserId)){
			    	if($this->storeImage($article['image'],$imagePath)){
			    		$article['image'] = array(
					    	'name'	=> $imageName.'.jpg',
					    	'path'	=> $imagePath,
					    );
			    	} else {
			    		/****Test******/
						//if(!empty($parserId)){
							echo '<div style="color:red">Ошибка картинки</div><br>';
							@ob_flush(); flush();
						//}
						/*************/	
						continue;
			    	}
				}

			} else {
				if($parserRow->only_with_images == 1){
					/****Test******/
					//if(!empty($parserId)){
						echo '<div style="color:red">Нет картинки</div><br>';
						@ob_flush(); flush();
					//}
					/*************/	
					continue;
				}
			}

	    	if($parserRow->translate == 1){
	    		$article['title'] 		= (string)$this->yandexTranslate((string)$article['title']);
	    		$article['keywords'] 	= (string)$this->yandexTranslate((string)$article['keywords']);
	    		$article['description'] = (string)$this->yandexTranslate((string)$article['description']);
	    		$article['content'] 	= (string)$this->yandexTranslate((string)$article['content']);
	    	}

			/****Test******/
			//if(!empty($parserId)){
				echo '<br><div style="color:green">Будет сохранена</div><br>';
				@ob_flush(); flush();
			//}
			/*************/
			
			if(empty($parserId)){
		    	$articleController = new ArticleController;
		    	$articleController->postStore($article);
			}
    		$i++;    		   
	    }
	    return $i;
	}

	private function createAbzac($str){
		$substrings = explode(". ",$str);
		$subres = '';
		$result = array();
		foreach($substrings as $key => $val){ 
		    $subres.= $val.'. ';
		    if(($key+1)%4==0){
		    	$result[] = '<p>'.$subres.'</p>';
		    	$subres = '';
		    }
		}
		$result = implode('',$result);
		if(empty($result)){
			return '<p>'.$str.'</p>';
		}
		return $result;
	}

	private function storeImage($url,$path){
		if(!empty($url) && !empty($path)){
			@file_get_contents($url);
			if(preg_match('/Not Found/', $http_response_header[0])){
				return false;
			}
			$image = file_get_contents($url);
			if(empty($image)){
				return false;
			}
			file_put_contents(public_path().'/'.$path,$image);
			return true;
		}
		return false;
	}

	private function aliasUnique($alias){
		$alias = Alias::where('alias',$alias)->first();
		if(!empty($alias)){
			return false;
		}
		return true;
	}

	private function generateAlias($text){
		if(!empty($text)){
			$text = mb_strtolower($text);
			$transl = array(
                'а'=> 'a', 'б'=> 'b', 'в'=> 'v', 'г'=> 'g', 'д'=> 'd', 'е'=> 'e', 'ё'=> 'e', 'ж'=> 'zh', 
                'з'=> 'z', 'и'=> 'i', 'й'=> 'j', 'к'=> 'k', 'л'=> 'l', 'м'=> 'm', 'н'=> 'n', ' '=>'_',
                'о'=> 'o', 'п'=> 'p', 'р'=> 'r', 'с'=> 's', 'т'=> 't', 'у'=> 'u', 'ф'=> 'f', 'х'=> 'h',
                'ц'=> 'c', 'ч'=> 'ch', 'ш'=> 'sh', 'щ'=> 'sh','ъ'=> '', 'ы'=> 'y', 'ь'=> '', 'э'=> 'e', 'ю'=> 'yu', 'я'=> 'ya',
                'і'=> 'i', 'є'=> 'e',
            );
            $text = strtr($text,$transl);
            $text = preg_replace('~[^-a-z0-9_]+~u', '', $text);
            return $text;
		}
	}

	private function yandexTranslate($text){
		try {
			$key = Config::get('site_keys.yandexTranslate');
			$translator = new Translator($key);
			$translation = $translator->translate($text, 'ru');
			return $translation;

			//echo $translation->getSource(); // Hello world;
			//echo $translation->getSourceLanguage(); // en
			//echo $translation->getResultLanguage(); // ru

		} catch (Exception $e) {
		  	//echo $e->getMessage(); exit;
			return $text;
		}
	}
}