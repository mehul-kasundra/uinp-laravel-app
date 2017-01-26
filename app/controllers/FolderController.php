<?php

class FolderController extends BaseController {

	protected $rules = array(
		'title'		=> 'max:255|required',
	);
	protected $table_fields = array(
		'Title'		=> 'title',
		'Alias'		=> 'alias',
		'Author'	=> 'users.username',
		'Created'	=> 'created_at',
		'Updated'	=> 'updated_at',
	);


/**
* Display a listing of folders
*
* @return Response
*/
	public function getIndex()
	{
		$model = new Folder;
		$params = array(
			'sort' 		=> Input::get('sort'),
	    	'order' 	=> Input::get('order'),
	    	'field' 	=> Input::get('field'),
	    	'search' 	=> Input::get('search'),
    	);
		$table_fields = $this->table_fields;

        $folders = $model->getFolders($table_fields,$params);

		return View::make('content.admin.folders.index', compact('folders','table_fields'));
	}

/**
 * Show the form for creating a new folder
 *
 * @return Response
 */
	public function getCreate()
	{
		$folders = Folder::orderBy('title','ASC')->get();
		$parent = 0;	
		return View::make('content.admin.folders.form')->nest('tree','content.admin.tree',compact('folders','parent'));
	}

/**
 * Store a newly created folder in storage.
 *
 * @return Response
 */
	public function postStore()
	{	
		$parent_folder_id 	= Input::get('folder_id');
		$alias = Input::get('alias');
		$this->rules['alias']	= 'max:255|required|unique:aliases,alias';	
		$validator = Validator::make(Input::all(), $this->rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput(Input::all());
		} else {
			$parents = $this->getParents($parent_folder_id);
			$path = implode('/', $parents).'/'.$alias;

			$model = new Folder;
	        $model->title   			= Input::get('title');
	        $model->alias   			= $alias;
	        $model->text   				= Input::get('text');
	        $model->user_id 			= Auth::user()->id;
	        $model->parent_folder_id 	= $parent_folder_id; 
	        $model->path 				= $this->getPath($parent_folder_id, $alias);
        	$model->save();
        	$this->saveAlias(Input::get('alias'),$model->id,'folders',$parent_folder_id);
        	$this->saveSeo($model->id,'folders');
		}
		//folder::create($data);
		Session::flash('success', 'Folder successfully created!');
		return Redirect::to(URL::to('admin/folders'));
	}

/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return Response
 */
	public function getEdit($id)
	{
		$model = new Folder;
		$folder = $model->getFolderWhithSeo($id);
		if(!empty($folder)){
			$folders = Folder::all();			
			$current_folder_id = $id;
			$parent = $folder->parent_folder_id;	

			return View::make('content.admin.folders.form', compact('folder'))->nest('tree','content.admin.tree',compact('folders','parent','current_folder_id'));
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
		$model = Folder::find($id);
		$parent_folder_id 	= Input::get('folder_id');
		$alias = Alias::where('item_id',$id)->where('table','folders')->first();						

		if(!empty($alias)){		
			$this->rules['alias']	= 'max:255|required|unique:aliases,alias,'.$alias->id;
		}
		$validator = Validator::make($data = Input::all(), $this->rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		} else {
			$path = $this->getPath($parent_folder_id, $model->alias);

			if($path!=$model->path){				
				$this->updateChildrenPath($model->id,$path);
			}

			$data = array(
		        'title'   			=> Input::get('title'),
		        'alias'   			=> Input::get('alias'),
		        'text'   			=> Input::get('text'),
		        'user_id'   		=> Auth::user()->id,
		        'parent_folder_id' 	=> $parent_folder_id,
		        'path'				=> $this->getPath($parent_folder_id, Input::get('alias')),
	        );	        

        	$model->update($data);
        	if(!empty($alias)){
        		$this->updateAlias($alias->id,Input::get('alias'),$parent_folder_id);
        	} else {
        		$this->saveAlias(Input::get('alias'),$model->id,'folders',$parent_folder_id);
        	}
        	$this->saveSeo($id,'folders');
		}
		Session::flash('success', 'Folder successfully updated!');
		return Redirect::to(URL::to('admin/folders'));
	}

/**
 * Remove the specified resource from storage.
 *
 * @param  int  $id
 * @return Response
 */
	public function deleteDestroy($id)
	{
		$children = Alias::where('parent_folder_id',$id)->first();
		if(empty($children)){
			Folder::destroy($id);
			Alias::where('item_id',$id)->where('table','folders')->delete();
			Seo::where('item_id',$id)->where('table','folders')->delete();
			Session::flash('success', 'Folder successfully deleted!');
		} else {
			Session::flash('error', 'Unable to delete non-empty folder');
		}
		return Redirect::back();
	}

	private function getPath($parentId,$alias){
		$parents = $this->getParents($parentId);		
		$path = implode('/', $parents);
		$path = !empty($path)?$path.'/'.$alias:$alias;	
		return $path;
	}

	private function updateChildrenPath($parentId,$parentPath){
		if($parentId!=0){
			$children = Folder::where('parent_folder_id',$parentId)->get();
			if(!empty($children)){
				foreach ($children as $child) {
					$path = $parentPath.'/'.$child->alias;
					$model = new Folder;
					Folder::where('id',$child->id)->update(array('path'=>$path));
					$this->updateChildrenPath($child->id,$path);
				}
			}
		}
	}

}