<?php

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::controller('/auth', 'AuthController');

/*
|--------------------------------------------------------------------------
| For all
|--------------------------------------------------------------------------
*/
Route::post('comments/store','CommentController@postStore');
Route::controller('cron', 'CronController');
Route::get('/sitemap.xml', 'FrontController@sitemap');
/*
|--------------------------------------------------------------------------
| For registered users
|--------------------------------------------------------------------------
*/
Route::group(array('before' => 'auth'), function() {
     Route::controller('comments','CommentController');     
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::group(array('before' => 'is_admin'), function() {	
     Route::get('admin', 'DashboardController@getIndex');
     Route::post('admin/dashboard/parents_links', 'MenuController@parentsLinks');
     Route::post('admin/dashboard/tree_list', 'DashboardController@treeList');

     Route::controller('admin/seo', 'SeoController');
     Route::controller('admin/folders', 'FolderController');
     Route::controller('admin/articles', 'ArticleController');
     Route::controller('admin/comments','CommentController');
     Route::controller('admin/tags','TagController');
     Route::controller('admin/menus', 'MenuController');
     Route::controller('admin/users', 'UserController');
     Route::get('admin/parser/parse/{parserId?}', 'ParserController@getParse'); 
     Route::controller('admin/parser', 'ParserController');
     Route::controller('admin/parser2', 'Parser2Controller');
     Route::controller('admin/mumble', 'MumbleController');    
});


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/
            
Route::controller('/', 'FrontController');

