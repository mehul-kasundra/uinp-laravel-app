<?php
ini_set('memory_limit', '-1');
ini_set('display_errors',1);
class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	/**
	 * Chek user is admin
	 * @return bool
	 */
	protected function is_admin(){
		if(Auth::check() && Auth::user()->role->id==1){
			return true;
		} else {
			return false;
		}
	}

	protected function startTime(){
		if($this->is_admin()){
			$this->start_time = microtime(TRUE);
		}
	}

	protected function endTime($text='Время выполнения'){
		if($this->is_admin()){
			$this->end_time = microtime(TRUE);
			if(isset($this->start_time)){
				echo '<div style="text-align:center; font-size:10px">'.$text.' '.round($this->end_time - $this->start_time,2).' сек</div>';
			}
		}
	}


	/**
	 * Chek is user resource owner
	 * @return bool
	 */
	protected function is_owner($elementOwnerId){
		if($this->is_admin() || Auth::check() && $elementOwnerId==Auth::user()->id){
			return true;
		} else {
			return false;
		}
	}

	protected function saveAlias($alias,$item_id,$table,$parent_folder_id){
	    $model = new Alias;
		$model->alias = $alias;
		$model->item_id = $item_id;
		$model->table = $table;
		$model->parent_folder_id = $parent_folder_id;
		$model->save();
	}

	protected function updateAlias($id,$alias,$parent_folder_id){
		$model = new Alias;
		$element = $model->find($id);

		$data = array(
			'alias'				=>	$alias,
			'parent_folder_id'	=>	$parent_folder_id,
		);		
		$model->where('id',$id)->update($data);
	}

	/*
	* Используется при сохранении элемента
	*/
	protected function getParents($parentId){
		while ( $parentId != 0) {
			$parent = Folder::where('id',$parentId)->first();
			$result[]=$parent->alias;
			$parentId=$parent->parent_folder_id;
		}
		if (!empty($result)){
			krsort($result);
			return $result;
		}
		return array();
	}

	/**
	 * Create image thumb
	 * @param  array  $image
	 * @param  int  $width
	 * @param  int  $height
	 * @param  bool  $crop
	 * @return string
	*/
	public function createThumb($image,$width,$height,$crop){
		$thumbPath = 'uploads/thumbs/thumb_'.$image['name'];
		Image::make($image['path'], 
			array(
				'width' 	=> $width,
				'height' 	=> $height,
				'crop'		=> $crop
			))
		->save($thumbPath);
		return $thumbPath;
	}

	/**
	 * Save seo data
	 *
	 * @return Bool
	 */
	public function saveSeo($itemId='',$table,$data=''){
		$model = new Seo;
		$model->table = $table;
		$model->item_id = $itemId;
		if(empty($data)){
			$model->seo_title 	= Input::get('seo_title');
			$model->keywords 	= Input::get('keywords');
			$model->description = Input::get('description');
			$model->img_alt 	= Input::get('img_alt');
			$model->img_title 	= Input::get('img_title');
		} else {
			$model->keywords 	= $data['keywords'];
			$model->description = $data['description'];
		}
		if(!empty($model->seo_title) || !empty($model->keywords) || !empty($model->description) || !empty($model->img_alt) || !empty($model->img_title)){			
	    	$seoid = Input::get('seoid');
    		if(!empty($seoid)){
    			$seoController = new SeoController;
    			$seoController->putUpdate($seoid);
    		} else if(!empty($itemId)) {
    			$model->save();        			
    		}
    		return true;
    	}
    	return false;
	}
}