<?php

class TagController extends BaseController {

	protected $rules = array(
		'name'		=> 'required',
	);

	protected $table_fields = array(
		'Name'				=> 'name',
		'Frequency'			=> 'frequency',
		'Frequency regions'	=> 'freq_regions',
		'Yandex main pages'	=> 'mainpages_yandex',
		'Yandex titles'		=> 'titles_yandex',
		'Google main pages'	=> 'mainpages_google',
		'Google titles'		=> 'titles_google',
		'Created'			=> 'created_at',
	);

/**
* Display a listing of resource
*
* @return Response
*/
	public function getIndex(){
        $model = new Tag;
		$params = array(
			'sort' 		=> Input::get('sort'),
	    	'order' 	=> Input::get('order'),
	    	'field' 	=> Input::get('field'),
	    	'search' 	=> Input::get('search'),
    	);
		$table_fields = $this->table_fields;
        $tags = $model->getTags($table_fields,$params);
		return View::make('content.admin.tags.index', compact('tags','table_fields'));
	}

/**
 * Show the form for import a new tags
 *
 * @return Response
 */
	public function getImport(){	
		return View::make('content.admin.tags.import');
	}

/**
 * Proccess import a new tags
 *
 * @return Response
 */
	public function postImport(){
		if (Input::hasFile('file')){
			header('Content-Type: text/html; charset=utf-8');

			$file = Input::file('file');
			$extension = $file->getClientOriginalExtension();
			$file->move('uploads', 'tagsTable.'.$extension);
			$filePath = 'uploads/tagsTable.'.$extension;

			include_once(app_path().'/lib/spreadsheet-reader/php-excel-reader/excel_reader2.php');
			include_once(app_path().'/lib/spreadsheet-reader/SpreadsheetReader.php');
			$reader = new SpreadsheetReader($filePath); 
		    foreach ($reader as $row){	
				$validator = Validator::make(array('name'=>$row[0]), $this->rules);
				if ($validator->fails()) continue;
				$tag = Tag::where('name',$row[0])->first();
				if(!empty($tag)){
					$data = array(
				        'frequency'   		=> isset($row[1])?$row[1]:0,
				        'freq_regions' 		=> isset($row[2])?$row[2]:0,
				        'mainpages_yandex' 	=> isset($row[3])?$row[3]:0,
				        'titles_yandex' 	=> isset($row[4])?$row[4]:0,
				        'mainpages_google'  => isset($row[5])?$row[5]:0,
				        'titles_google' 	=> isset($row[6])?$row[6]:0,
			        );
			        $data['summ_google']	= $data['mainpages_google']+$data['titles_google'];  
			        $data['summ_yandex']	= $data['mainpages_yandex']+$data['titles_yandex'];
			        $tag->update($data);   
				} else {
					$model = new Tag;
			        $model->name 				= $row[0];
			        $model->frequency   		= isset($row[1])?$row[1]:0;
			        $model->freq_regions 		= isset($row[2])?$row[2]:0;
			        $model->mainpages_yandex 	= isset($row[3])?$row[3]:0;
			        $model->titles_yandex 		= isset($row[4])?$row[4]:0;
			        $model->mainpages_google   	= isset($row[5])?$row[5]:0;
			        $model->titles_google 		= isset($row[6])?$row[6]:0;
			        $model->summ_google			= $model->mainpages_google + $model->titles_google;
					$model->summ_yandex			= $model->mainpages_yandex + $model->titles_yandex;
					$model->save();
				}
			}
		    Session::flash('success', 'Tags imported!'); 
		} else {
			Session::flash('success', 'No file uploaded!');
		}
		return Redirect::to('admin/tags');
	}

/**
 * Show the form for creating a new tag
 *
 * @return Response
 */
	public function getCreate(){	
		return View::make('content.admin.tags.form');
	}

/**
 * Store a newly created specified resource in storage.
 *
 * @return Response
 */
	public function postStore()
	{
		$this->rules['name']	= 'max:128|required|unique:tags,name';
		$validator = Validator::make(Input::all(), $this->rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$model 	= new Tag;
 
  			$model->name 				= Input::get('name');
	        $model->frequency   		= Input::get('frequency');
	        $model->freq_regions 		= Input::get('freq_regions');
	        $model->mainpages_yandex 	= Input::get('mainpages_yandex');
	        $model->titles_yandex 		= Input::get('titles_yandex');
	        $model->mainpages_google   	= Input::get('mainpages_google');
	        $model->titles_google 		= Input::get('titles_google');
	        $model->summ_google			= $model->mainpages_google + $model->titles_google;
			$model->summ_yandex			= $model->mainpages_yandex + $model->titles_yandex;

        	$model->save();
		}
		Session::flash('success', 'Tag successfully created!');
		return Redirect::to('admin/tags');
	}

/**
 * Show the form for editing tag
 *
 * @param  int  $id
 * @return Response
 */
	public function getEdit($id)
	{
		$tag = Tag::find($id);
		if(!empty($tag)){	
			return View::make('content.admin.tags.form', compact('tag'));
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
	public function putUpdate($id){
		$model = Tag::find($id);
		if(empty($model)){
			App::abort(404);
		}
		$this->rules['name']	= 'max:128|required|unique:tags,name,'.$model->id;
		$validator = Validator::make($data = Input::all(), $this->rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		} else {
			$data = array(
				'name' 				=> Input::get('name'),
		        'frequency'   		=> Input::get('frequency'),
		        'freq_regions' 		=> Input::get('freq_regions'),
		        'mainpages_yandex' 	=> Input::get('mainpages_yandex'),
		        'titles_yandex' 	=> Input::get('titles_yandex'),
		        'mainpages_google'  => Input::get('mainpages_google'),
		        'titles_google' 	=> Input::get('titles_google'),
	        );
	        $data['summ_google']	= $data['mainpages_google']+$data['titles_google'];  
	        $data['summ_yandex']	= $data['mainpages_yandex']+$data['titles_yandex'];       
        	$model->update($data);
		}
		Session::flash('success', 'Tag successfully updated!');
		return Redirect::to('/admin/tags');
	}


	public function addTags($tags,$table,$elementId){
		$model = new Tagstoelement;
		$model->where('table',$table)->where('element_id',$elementId)->delete();

		if(!empty($tags)){		
			$tag = new Tag;
			$tag->saveTags($tags);

			$tags = explode(',',$tags);
			$tagsIds = $tag->select('id')->whereIn('name', $tags)->get();			
			if(!empty($tagsIds)){
				$data = array();
				foreach ($tagsIds as $val) {
					$data[] = array('tag_id'=>$val->id,'table'=>$table,'element_id'=>$elementId);
				}
				$model->insert($data);
			}
		}	
	}

	public function getTags($elementId,$table){
		$model = new Tagstoelement;
		$tags = $model->select('tags.name')
					->from('tagstoelement')
					->join('tags','tags.id','=','tagstoelement.tag_id')
					->where('tagstoelement.element_id',$elementId)
					->where('tagstoelement.table',$table)
					->get();
		if(!empty($tags)){
			$result = '';
			foreach ($tags as $val) {
				$result.=$val->name.',';
			}
			return $result;
		}
		return '';
	}

/**
 * Remove the specified resource from storage.
 *
 * @return none
 */
	public function getDelete($id=''){
		if(empty($id)){
			App::abort(404);
		}
		Tag::destroy($id);
		return Redirect::to('/admin/tags');
	}

	public function postDestroy()
	{
		$name = Input::get('name');
		$tag = Tag::where('name',$name)->first();
		if(!empty($tag)){
			Tag::destroy($tag->id);
			Tagstoelement::where('tag_id',$tag->id)->delete();
		}
	}

}