<?php

class SeoController extends BaseController {

	protected $rules = array(
		'keywords'		=> 'max:256',
		'description'	=> 'max:256',
		'title'			=> 'max:256',
		'img_alt'		=> 'max:256',
		'img_title'		=> 'max:256',
		'table'			=> 'max:128',
	);

	protected $table_fields = array(
		'Url'		=> 'url',
		'Created'	=> 'created_at',
		'Updated'	=> 'updated_at',
	);

/**
* Display a listing of resource
*
* @return Response
*/
	public function getIndex()
	{
		$model = new Seo;
		$params = array(
			'sort' 		=> Input::get('sort'),
	    	'order' 	=> Input::get('order'),
	    	'field' 	=> Input::get('field'),
	    	'search' 	=> Input::get('search'),
    	);
		$table_fields = $this->table_fields;

        $items = $model->getUrltable($table_fields,$params);

		return View::make('content.admin.seo.index', compact('items','table_fields'));
	}

/**
 * Show the form for creating a new seo for specified url
 *
 * @return Response
 */
	public function getCreate()
	{
		return View::make('content.admin.seo.form');
	}

/**
 * Show the form for editing seo
 *
 * @param  int  $id
 * @return Response
 */
	public function getEdit($id)
	{
		$seo = Seo::find($id);
		if(!empty($seo)){	
			return View::make('content.admin.seo.form', compact('seo'));
		} else {
			App::abort(404);
		}
	}


/**
 * Show the form for creating a seo for specified element
 *
 * @return Response
 */
	public function getItem($table,$elementId)
	{
		$item = DB::table($table)->find($elementId);
		$seo = Seo::where('table',$table)->where('item_id',$elementId)->first();	
		return View::make('content.admin.seo.form',compact('elementId','table','item','seo'));
	}

/**
 * Store a newly created specified resource in storage.
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
			$model 	= new Seo;
			$url 		= Input::get('url');
			$table 		= Input::get('table');
			$title 		= Input::get('seo_title');
			$item_id 	= Input::get('item_id');
 
  			$model->seo_title 	= isset($title)?$title:'';
	        $model->keywords   	= Input::get('keywords');
	        $model->description = Input::get('description');
	        $model->img_alt 	= Input::get('img_alt');
	        $model->img_title 	= Input::get('img_title');
	        $model->url   		= !empty($url)?$url:'';
	        $model->table 		= !empty($table)?$table:'';
	        $model->item_id 	= !empty($item_id)?$item_id:'';;

        	$model->save();
		}

		Session::flash('success', 'SEO data successfully created!');
		return Redirect::to('admin');
	}


/**
 * Update the specified resource in storage.
 *
 * @param  int  $id
 * @return Response
 */
	public function putUpdate($id)
	{
		$model = Seo::find($id);
		if(empty($model)){
			App::abort(404);
		}
		
		$validator = Validator::make($data = Input::all(), $this->rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		} else {
			$title = Input::get('seo_title');
			$data = array(
				'url'   		=> Input::get('url'),
				'seo_title'   	=> isset($title)?$title:'',
		        'keywords'   	=> Input::get('keywords'),
		        'description'   => Input::get('description'),
		        'img_alt'   	=> Input::get('img_alt'),
		        'img_title'   	=> Input::get('img_title'),
	        );	        

        	$model->update($data);
		}
		Session::flash('success', 'Seo successfully updated!');
		return Redirect::back();
	}

/**
 * Remove the specified resource from storage.
 *
 * @param  int  $id
 * @return Response
 */
	public function deleteDestroy($id)
	{
		Seo::destroy($id);
		Session::flash('success', 'Successfully deleted!');
		return Redirect::back();
	}

}