<?php

class MumbleController extends BaseController {

/**
 * Validation rules.
 *
 * @var array
 */
	protected $rules = array(
		'content'	=> 'required',
		'type'	=> 'required',
	);

/**
 * Table fields and aliases.
 *
 * @var array
 */
	protected $table_fields = array(
			'Text'			=> 'content',
			'Created at'	=> 'created_at',
			'Updated at'	=> 'updated_at',
		);	

/**
* Display a listing of all items
*
* @return Response
*/
	public function getIndex()
	{
		if($this->is_admin()){
			$model = new Mumble;
			$params = array(
				'sort' 		=> Input::get('sort'),
		    	'order' 	=> Input::get('order'),
		    	'field' 	=> Input::get('field'),
		    	'search' 	=> Input::get('search'),
	    	);
			$table_fields = $this->table_fields;

	        $mumbleRows = $model->getMumble($table_fields,$params);

			return View::make('content.admin.mumble.index', compact('mumbleRows','table_fields'));
		} else {
			return Redirect::to('/')->withErrors('Access denied!');
		}
	}

/**
 * Show the form for editing the item.
 *
 * @param  int  $id
 * @return Response
 */
	public function getCreate()
	{		
		return View::make('content.admin.mumble.form');
	}

/**
 * Show the form for editing the item.
 *
 * @param  int  $id
 * @return Response
 */
	public function getEdit($id='')
	{		
		// if(!empty($id)){
		// 	App::abort(404);
		// }
		$model = new Mumble;
		$mumbleItem = $model->find($id);
		if(!empty($mumbleItem)){
			return View::make('content.admin.mumble.form', compact('mumbleItem'));
		}
	}


/**
 * Store a newly created item in storage.
 *
 * @return Response
 */
	public function postStore()
	{	
		$validator = Validator::make(Input::all(), $this->rules);		
		if ($validator->fails()){
			return Response::json( array('error'=>$validator->failed()) );
		} else {			
			$mumble = new Mumble;	
	        $mumble->content = Input::get('content');
	        $mumble->type 	 = Input::get('type');
        	$mumble->save();
		}
		return Redirect::to('/admin/mumble');
	}

/**
 * Update a item in storage.
 *
 * @return Response
 */
	public function putUpdate($id){
		$mumble = Mumble::find($id);
		if(empty($mumble)){
			App::abort(404);
		}
		$validator = Validator::make(Input::all(), $this->rules);
		if ($validator->fails()){
			return Redirect::back()->withErrors($validator)->withInput();
		} else {
			$data['content'] = Input::get('content');
			$data['type'] 	 = Input::get('type');
			$mumble->update($data);

			Session::flash('success', 'Мumble updated!');
			return Redirect::to('admin/mumble');			 		
		} 
	}

/**
 * Remove the item from storage.
 *
 * @param  int  $id
 * @return Response
 */
	public function getDelete($id)
	{
		Mumble::destroy($id);
		Session::flash('success', 'Мumble item deleted!');
		return Redirect::to('admin/mumble');
	}
}

