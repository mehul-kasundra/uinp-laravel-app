<?php

class DashboardController extends BaseController {

	//protected $layout = 'containers.admin';
	protected $rules = array(
		'title'		=> 'required|unique:articles,title',
	);
	protected $table_fields = array(
			'Title'		=> 'title',
			'Author'	=> 'user_id',
			'Created'	=> 'created_at',
			'Updated'	=> 'updated_at',
		);

	/**
	* Display a site file system tree
	*
	* @return Response
	*/
	public function getIndex()
	{		
        $folders = Folder::get();
		return View::make('content.admin.dashboard.index')->nest('file_tree', 'content.admin.tree', compact('folders'));
	}

	 /**
	 * 
	 * Ajax load content of selected tree element
	 * @return Response
	 */
	public function treeList(){
		$model = new Dashboard;
		$parentId = Input::get('id');
		$inMenuEdit = Input::get('inMenuEdit');		
		$entities = $model->getEntities($parentId);		
		return View::make('content.admin.tree_list',compact('entities','inMenuEdit','parentId'));
	}


}