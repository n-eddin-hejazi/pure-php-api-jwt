<?php

namespace App\Controllers\Auth;
use App\Core\Support\QueryBuilder;
use Carbon\Carbon;
class LoginController
{
     private string $email;
     private string $password;

     public function index()
     {
          ifAuth();
          $this->autoLoginWithRememberMe();
          return view('auth.login');
     }
     
      public function store()
     {
          if($_SERVER['REQUEST_METHOD'] === 'POST'){
               if(isset($_POST['email'], $_POST['password'])){
                    // assign preperties
                    $this->email = htmlspecialchars(strip_tags($_POST['email']));
                    $this->password = $_POST['password'];

                    // check the validations
                    $this->validation();

                    // check credits and login
                    $this->checkCredits();

                    // assign last login
                    $this->registerLastLogin();

                    $this->rememberMe();

                    // redirect to home page
                    $this->redirectToHome();
               }
          }
     }

     private function validation()
     {
          $email_errors = $this->emailValidation();
          if(!empty($email_errors)){
               session()->setFlash('email_errors', $email_errors);
          }

          $password_errors = $this->passwordValidation();
          if(!empty($password_errors)){
               session()->setFlash('password_errors', $password_errors);
          }

          if(!empty($email_errors)){
               session()->setFlash('email', $this->email);
               return back();
          }

     }

     private function emailValidation()
     {
          $email_errors = [];
          // email validation
          if(empty($this->email)){
               $email_errors[] = "The email field is required.";
          }

          // email validation
          if(!filter_var($this->email, FILTER_VALIDATE_EMAIL) || strlen($this->email) < 6 || strlen($this->email) > 40){
               $email_errors[] = "Invalid email.";
          }

          return $email_errors;
     }

     private function passwordValidation()
     {
          $password_errors = [];
          // password validation
          if(empty($this->password)){
               $password_errors[] = "The password field is required.";
          }

          // password validation
          if(strlen($this->password) < 8){
               $password_errors[] = "The password field should be grater than or equal to 8 characters.";
          }

          // password validation
          if(strlen($this->password) > 32){
               $password_errors[] = "The password field should be less than or equal to 32 characters.";
          }

          return $password_errors;
     }

     private function checkCredits()
     {
          $user = QueryBuilder::get('users', 'email', '=', $this->email);
          if(!$user){
               session()->setFlash('fail', 'Email or password incorrect!.');
               return back();
          }
         

          if($user && !password_verify($this->password, $user->password)){
               session()->setFlash('fail', 'Email or password incorrect!.');
               return back();
          }

          if($user && password_verify($this->password, $user->password)){
               $_SESSION['loggedin'] = true;
               $_SESSION['id'] = $user->id;
               $_SESSION['name'] = $user->name;
               $_SESSION['email'] = $user->email;
          }
     }

     private function registerLastLogin()
     {  
          $data = ['last_login'=> Carbon::now()];
          QueryBuilder::update('users', $data, 'id', '=', $_SESSION['id']);
     }

     private function autoLoginWithRememberMe()
     {
          if((isset($_COOKIE['remember_me'])) && (is_string($_COOKIE['remember_me'])) && (strlen($_COOKIE['remember_me']) == 100)){         
               $user = QueryBuilder::get('users', 'remember_me', '=', $_COOKIE['remember_me']);
               if($user->remember_me === $_COOKIE['remember_me']){
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $user->id;
                    $_SESSION['name'] = $user->name;
                    $_SESSION['email'] = $user->email;
                    return to('admin');
               }
          }    
     }

     private function rememberMe()
     {
          if(isset($_POST['remember_me']) && ($_POST['remember_me'] == 'on')){
               $data = ['remember_me' => $this->generateUniqueToken()];
               QueryBuilder::update('users', $data, 'id', '=', $_SESSION['id']);
               setcookie('remember_me', $data['remember_me'], time() + (60 * 60 * 24 * 30), '/');
          }
     }

     private function generateUniqueToken()
     {
          $token = bin2hex(random_bytes(50));
          $old_token = QueryBuilder::get('users', 'remember_me', '=', $token);
          if($old_token){
               // Token already exists in the database
               return $this->generateUniqueToken();
          }else{
               // Token does not exist in the database
               return $token;
          }
        
     }

     private function redirectToHome()
     {
          session()->setFlash('success', "Welcome {$_SESSION['name']}, you are logged in");
          return to('admin');
     }

}   