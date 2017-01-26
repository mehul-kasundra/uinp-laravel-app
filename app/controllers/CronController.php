<?php 

class CronController extends BaseController {

	public function getPublisharticle($key=''){
		if($key !=  Config::get('site_keys.cron.publish')){
			App::abort(404);
		}
		$article = Article::where('published_at','0000-00-00 00:00:00')->orderby('id','ASC')->first();
		if(!empty($article)){
			$article->update(array('published_at'=>date('Y-m-d H:i:s')));
		}
	}

	public function getRunparser($key=''){
		if($key !=  Config::get('site_keys.cron.parser')){
			App::abort(404);
		}
		$parserController = new ParserController;
		$parserController->getParse();
	}

	public function getRunparser2($key=''){
		if($key !=  Config::get('site_keys.cron.parser')){
			App::abort(404);
		}
		$parser2Controller = new Parser2Controller;
		$parser2Controller->getParse();
	}
}