<?php
 
class AuthController extends BaseController {
 
    public function getIndex()
    {
        return View::make('content.auth.login');
    }
 
    public function postIndex()
    {
        $username = Input::get('username');
        $password = Input::get('password');
 
        if (Auth::attempt(array('email' => $username, 'password' => $password)))
        {
            if(Auth::user()->role->id==1) return Redirect::to('/admin');
            return Redirect::back();
        }
 
        return Redirect::back()
            ->withInput()
            ->withErrors('That username/password combo does not exist.');
    }
 
    public function getLogin()
    {
        return Redirect::to('/auth');
    }
 
    public function getLogout()
    {
        Auth::logout();
        return Redirect::back();
    }
 
}