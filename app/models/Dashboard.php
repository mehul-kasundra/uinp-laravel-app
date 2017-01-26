<?php

class Dashboard {

	public $limit = 20;
	
	public function getEntities($parentId=0){

		$countQuery = $this->getCount($parentId);
		$query = $this->buildQuery($parentId);

		$count = DB::select(DB::raw($countQuery));
		$count = $count[0]->cnt;	

		$results = DB::select(DB::raw($query));
		$results = Paginator::make($results, $count, $this->limit);

		return $results;
	}

	private function getCount($parentId){
		$entities = Config::get('site_config.entities');

		$query = 'select count(*) as cnt from ((select id from '.DB::getTablePrefix().'folders where parent_folder_id = '.$parentId.')';
		foreach ($entities as $key => $val) {
			$query.= 'union  (select id from '.DB::getTablePrefix().$key.' where parent_folder_id = '.$parentId.') ';
		}
		$query.= ')tmp';
		return $query;
	}

	private function buildQuery($parentId){
		$entities = Config::get('site_config.entities');

		$query = '(select "Folder" as type, "folders" as tname, title, created_at, alias, undeletable, id from '.DB::getTablePrefix().'folders f where parent_folder_id = '.$parentId.') ';
		foreach ($entities as $key => $val) {
			$query.= 'union  (select "'.$val.'" as type, "'.$key.'" as tname, title, created_at, alias, 0 as undeletable, id from '.DB::getTablePrefix().$key.' a where parent_folder_id = '.$parentId.') ';
		}
		$page = Input::get('page');
		$page = !empty($page)?$page:1;
				
		$offset = $this->limit*($page-1);		
		$query.='limit '.$this->limit.' offset '.$offset;
		return $query;
	}

}