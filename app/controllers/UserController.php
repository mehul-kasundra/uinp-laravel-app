<?php

class UserController extends BaseController {

	protected $rules = array(
		'username'	=> 'required|max:255',
		'email'		=> 'required|max:255|email|unique:users,email',
		'password'	=> 'required|max:64|same:password_confirmation',	
	);
	protected $table_fields = array(
			'User'		=> 'username',
			'Email'		=> 'email',
			'Created'	=> 'created_at',
			'Updated'	=> 'updated_at',
			'Role'		=> 'roles.name',
		);

	/**
	* Display a listing of users
	*
	* @return Response
	*/
	public function getIndex()
	{
		$model = new User;
		$params = array(
			'sort' 		=> Input::get('sort'),
	    	'order' 	=> Input::get('order'),
	    	'field' 	=> Input::get('field'),
	    	'search' 	=> Input::get('search'),
    	);
		$table_fields = $this->table_fields;

        $users = $model->getUsers($table_fields,$params);

		return View::make('content.admin.users.index', compact('users','table_fields'));
	}


	/**
	 * Show the form for creating a new user
	 *
	 * @return Response
	 */
	public function getCreate()
	{
		$roles_dd = Role::lists('name','id');
		return View::make('content.admin.users.form',compact('roles_dd'));
	}


	/**
	 * Store a newly created user in storage.
	 *
	 * @return Response
	 */
	public function postStore($innerRequest=false)
	{
		$validator = Validator::make(Input::all(), $this->rules);

		if ($validator->fails())
		{
			if($innerRequest) return Response::json( array('error'=>$validator->messages()->toArray()) );
			return Redirect::back()->withErrors($validator)->withInput(Input::except('password'));
		} else {
			if($innerRequest){
				$role = 3;
			} else {
				$role = Input::get('role');
				$role = empty($role)?3:$role;
			}
			$user = new User;
 
	        $user->username   		= Input::get('username');
	        $user->email      		= Input::get('email');
	        $user->google_account   = Input::get('google_account');	        
	        $user->password   		= Hash::make(Input::get('password'));
	        $user->role_id    		= $role;

	        if(Input::hasFile('userfile')) {
				$image = Common_helper::fileUpload(Input::file('userfile'),'users',$user->username);				
				if(isset($image) && isset($image['errors'])){		
					return Redirect::back()->withErrors($image['errors'])->withInput(Input::except('userfile'));	
				}		
				$user->image = $image['path'];
		    	$user->thumb = $this->createThumb($image,80,80,true);
	    	}
			
        	$user->save();
			if($innerRequest) return 'success';

			Session::flash('success', 'User successfully created!');
			return Redirect::to('admin/users');
		}
	}

	/**
	 * Display the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getShow($id)
	{
		$user = User::find($id);

		if(!empty($user)){	
			return View::make('users::admin.form', compact('user'));
		} else {
			App::abort(404);
		}
	}

	/**
	 * Show the form for editing the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getEdit($id)
	{
		$user = User::find($id);
		if(!empty($user)){
			$roles_dd = Role::lists('name','id');
			return View::make('content.admin.users.form', compact('user','roles_dd'));
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
		$user = User::findOrFail($id);
		
		$this->rules['email'] = 'required|email|unique:users,email,'.$id;
		$this->rules['password'] = 'same:password_confirmation';
		$validator = Validator::make($data = Input::all(), $this->rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		} else {
			$data = array(
		        'username'   		=> Input::get('username'),
		        'email'      		=> Input::get('email'),
		        'google_account'    => Input::get('google_account'),		        
		        'role_id'    		=> Input::get('role'),
	        );	
	        if(Input::hasFile('userfile')) {	       	
				$image = Common_helper::fileUpload(Input::file('userfile'),'users',$data['username']);

				$data['image'] = $image['path'];
				$data['thumb'] = $this->createThumb($image,40,40,true);
			} else {
				$image = Input::get('image');
					if(!empty($user->image)) {
						$data['image'] = '';
						$data['thumb'] = '';
						unlink(public_path().'/'.$user->image);
						unlink(public_path().'/'.$user->thumb);
					}
			}        
	        if(Input::get('password')){
	        	$data['password'] = Hash::make(Input::get('password'));
	        }
        	$user->update($data);
		}
		Session::flash('success', 'User successfully updated!');
		return Redirect::to('admin/users');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteDestroy($id)
	{
		$user = User::find($id);
		if(empty($user)){
			App::abort(404);
		}
		if(!empty($user->image)){
			unlink(public_path().'/'.$user->image);
		}
		if(!empty($user->thumb)){
			unlink(public_path().'/'.$user->thumb);
		}
		User::destroy($id);
		Session::flash('success', 'User successfully deleted!');
		return Redirect::to('admin/users');
	}

}