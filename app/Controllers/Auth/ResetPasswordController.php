<?php
namespace App\Controllers\Auth;
use App\Core\Support\QueryBuilder;
use Carbon\Carbon;
class ResetPasswordController
{
    private string $email;
    private string $password;
    private string $password_confirmation;

    public function index()
    {
        ifAuth();
        return view('auth.reset-password');
    }

    public static function getURLValidation()
    {
        if (!isset($_GET['token'])) {
            return view('404');
        }

        if (!isset($_GET['email'])) {
            return view('404');
        }

        $user = QueryBuilder::get('users', 'email', '=', $_GET['email']);
        $token = QueryBuilder::get('password_resets', 'email', '=', $_GET['email']);

        if(!$user && !is_object($user)){
            return view('404');
        }

        if(!$token && !is_object($token)){
            return view('404');
        }
        
        if (isset($_GET['email']) && ($token->email != $user->email)) {
            return view('404');
        }

        if (isset($_GET['token']) && $_GET['token'] != $token->token) {
            return view('404');
        }

        $diffInMinutes = Carbon::now()->diffInMinutes(Carbon::parse($token->created_at));
        if (isset($_GET['token']) && $_GET['token'] != $token->token && ($diffInMinutes < env('TOKEN_EXPIRATION_TIME'))) {
            return view('404');
        }

        if ($token->reset_status == 1) {
            return view('404');
        }

    }

    private function postURLValidation()
    {
        if (!isset($_POST['email'])) {
            return view('404');
        }

        if (!isset($_POST['email'])) {
            return view('404');
        }

        $user = QueryBuilder::get('users', 'email', '=', $_POST['email']);
        $token = QueryBuilder::get('password_resets', 'email', '=', $_POST['email']);

        if(!$user && !is_object($user)){
            return view('404');
        }

        if(!$token && !is_object($token)){
            return view('404');
        }
        
        if (isset($_POST['email']) && ($token->email != $user->email)) {
            return view('404');
        }

        if (isset($_POST['token']) && $_POST['token'] != $token->token) {
            return view('404');
        }

        $diffInMinutes = Carbon::now()->diffInMinutes(Carbon::parse($token->created_at));
        if (isset($_POST['token']) && $_POST['token'] != $token->token && ($diffInMinutes < env('TOKEN_EXPIRATION_TIME'))) {
            return view('404');
        }
    }

    public function resetPassword()
    {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['password'], $_POST['password_confirmation'])){
                // check url validation
                self::postURLValidation();

                // assign preperties
                $this->email = $_POST['email'];
                $this->password = $_POST['password'];
                $this->password_confirm = $_POST['password_confirmation'];

                // check the validations
                $this->validation();

                // change password
                $this->changePassword();
                    
            }
        }
    }

    private function validation()
    {
        $password_errors = $this->passwordValidation();
        if(!empty($password_errors)){
            session()->setFlash('password_errors', $password_errors);
            return back();
        }

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

        // password validation
        if($this->password_confirm !== $this->password){
            $password_errors[] = "Password confirmation doesn't match.";
        }

        return $password_errors;
    }

    private function changePassword()
    {
        $data = ['password' => password_hash($this->password, PASSWORD_DEFAULT)];
        
        try{
            QueryBuilder::update('users', $data, 'email', '=', $this->email);
            QueryBuilder::update('password_resets', ['reset_status' => 1], 'email', '=', $this->email);
            session()->setFlash('success', 'Password reseted sucessfully.');
            return to('login');
        } catch (Exception $e) {
            session()->setFlash('fail', $e->getMessage());
            return back();
        }
    }

}