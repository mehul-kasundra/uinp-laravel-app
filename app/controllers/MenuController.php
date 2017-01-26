<?php

class MenuController extends BaseController {

	protected $rules = array(
		'name'		=> 'required|unique:menus,name',
		'content'	=> 'required',
	);
	protected $table_fields = array(
			'Name'		=> 'name',
			'Created'	=> 'created_at',
			'Updated'	=> 'updated_at',
		);

	/**
	* Display a listing of menus
	*
	* @return Response
	*/
	public function getIndex()
	{
		$model = new Menu;
		$params = array(
			'sort' 		=> Input::get('sort'),
	    	'order' 	=> Input::get('order'),
	    	'field' 	=> Input::get('field'),
	    	'search' 	=> Input::get('search'),
    	);
		$table_fields = $this->table_fields;

        $menus = $model->getMenus($table_fields,$params);

		return View::make('content.admin.menus.index', compact('menus','table_fields'));
	}


	/**
	 * Show the form for creating a new menu
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$folders = Folder::get();
		$inMenuEdit = 1;
		return View::make('content.admin.menus.form', compact('menu'))->nest('file_tree', 'content.admin.tree', compact('folders','inMenuEdit'));
	}

	/**
	 * Store a newly created menu in storage.
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
			$menu = new Menu;
 
	        $menu->name   = Input::get('name');
	        $menu->content = Input::get('content');

        	$menu->save();
		}

		//menu::create($data);
		Session::flash('success', 'menu successfully created!');
		return Redirect::to('admin/menus');
	}

	/**
	 * Display the specified menu.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getShow($id)
	{
			App::abort(404);	
	}

	/**
	 * Show the form for editing the specified menu.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getEdit($id)
	{
		$menu = Menu::find($id);
		if(!empty($menu)){
			$folders = Folder::get();
			$inMenuEdit = 1;
			return View::make('content.admin.menus.form', compact('menu'))->nest('file_tree', 'content.admin.tree', compact('folders','inMenuEdit'));
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
		$menu = Menu::findOrFail($id);
		
		$this->rules['name'] = 'required|unique:menus,name,'.$id;
		$validator = Validator::make($data = Input::all(), $this->rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		} else {
			$data = array(
		        'name'   	=> Input::get('name'),
		        'content'	=> Input::get('content'),
	        );	        

        	$menu->update($data);
		}
		Session::flash('success', 'Menu successfully updated!');
		return Redirect::to('admin/menus');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteDestroy($id)
	{
		Menu::destroy($id);
		Session::flash('success', 'Menu successfully deleted!');
		return Redirect::to('admin/menus');
	}

	/**
	 * 
	 * Collect all parents for current item
	 * @return Response
	 */
	public function parentsLinks(){
		$model = ucfirst(Input::get('type'));
		$id = Input::get('id');		
		$element = $model::where('id',$id)->first();

		while (!empty($element)) {
			$parents[] = $element->alias;
			$element = Folder::where('id',$element->parent_folder_id)->first();
		}

		if(!empty($parents)){
			krsort($parents);
			echo '/'.implode('/', $parents);
		}
	}

}