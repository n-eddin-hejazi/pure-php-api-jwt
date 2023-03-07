<?php
namespace App\Controllers\Api;
use App\Core\Support\QueryBuilder;
class LoginController extends API
{
    private string $email;
    private string $password;

    public function login()
    {
        // self::response([], 'test', TRUE, 200);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['email'], $_POST['password'])){
                $request = $this->request();
                $this->email = $request->email;
                $this->password = $request->password;

                // check the validations
                $this->validation();
                // check credits and login
                $this->checkCredits();
                // assign last login
                $this->registerLastLogin();
                // redirect to home page
                $this->redirectToHome();
            }
        }
        
    }


    private function validation()
    {
        $this->securityValidation();
        self::generalValidation();
        $this->emailValidation();
        $this->passwordValidation();
    }

    private function securityValidation()
    {
        if (!isset(getallheaders()['Secret'])) {
            $message = 'Missing secret key.';
            self::response([], $message, FALSE, 422);
        }
        
        if (empty(getallheaders()['Secret'])) {
            $message = 'Secret key is empty.';
            self::response([], $message, FALSE, 422);
        }

        if (utf8_decode(getallheaders()['Secret']) != utf8_decode($this->mobile_secret)) {
            $message = 'Invalid secret key.';
            self::response([], $message, FALSE, 422);
        }
    }
  

    private function emailValidation()
    {
        // email validation
        if(empty($this->email)){
            $message = "The email field is required.";
            self::response([], $message, FALSE, 403);
        }

        // email validation
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL) || strlen($this->email) < 6 || strlen($this->email) > 40){
            $message = "Invalid email.";
            self::response([], $message, FALSE, 403);
        }
    }

    private function passwordValidation()
    {
        // password validation
        if(empty($this->password)){
            $message = "The password field is required.";
            self::response([], $message, FALSE, 403);
        }

        // password validation
        if(strlen($this->password) < 8){
            $message = "The password field should be grater than or equal to 8 characters.";
            self::response([], $message, FALSE, 403);
        }

        // password validation
        if(strlen($this->password) > 32){
            $message = "The password field should be less than or equal to 32 characters.";
            self::response([], $message, FALSE, 403);
        }
    }

    private function checkCredits()
    {
        $user = QueryBuilder::get('users', 'email', '=', $this->email);
        if(!$user){
            $message = "Email or password incorrect!.";
            self::response([], $message, FALSE, 403);
        }
        
        if($user && !password_verify($this->password, $user->password)){
            $message = "Email or password incorrect!.";
            self::response([], $message, FALSE, 403);
        }

        if($user && password_verify($this->password, $user->password)){
            $data = [
                'ID' => $user->id,
                'Name' => $user->name,
                'Email' => $user->email,
                'Last Login' => $user->last_login,
                'Created At' => $user->created_at,
                'Updated At' => $user->updated_at,
            ];
            self::response($data, "", TRUE, 200, TRUE);
            // $_SESSION['loggedin'] = true;
            // $_SESSION['id'] = $user->id;
            // $_SESSION['name'] = $user->name;
            // $_SESSION['email'] = $user->email;
        }
    }
}
