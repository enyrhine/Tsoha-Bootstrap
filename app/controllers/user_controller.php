<?php
class UserController extends BaseController{
    
  public static function login(){
      View::make('user/login.html');
  }
  
  public static function handle_login(){
    $params = $_POST;

    $user = User::authenticate($params['username'], $params['password']);

    if(!$user){
      View::make('suunnitelmat/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
    }else{
      $_SESSION['user'] = $user->user_id;

      Redirect::to('/esittely', array('message' => 'Tervetuloa takaisin ' . $user->name . '!'));
    }
  }
}
