<?php
namespace App\Controllers\Api;
use App\Core\Support\QueryBuilder;
class RegisterController extends API
{
    private string $name;
    private string $email;
    private string $password;
    private string $password_confirmation;

    public function register()
    {   
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['password_confirmation'])){
                $request = $this->request();
                $this->name = $request->name;
                $this->email = $request->email;
                $this->password = $request->password;
                $this->password_confirm = $request->password_confirmation;

                // check the validations
                $this->validation();
                // create new account
                $this->craeteNewAccount();
            }
        }
    }

    private function validation()
    {
        self::generalValidation();
        $this->securityValidation();
        $this->nameValidation();
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
  

    private function nameValidation()
    {
        // name validation
        if(empty($this->name)){
            $message = "The name field is required.";
            self::response([], $message, FALSE, 403);
        }

        // name validation
        if(strlen($this->name) < 3){
            $message = "The length of name field shloud be grater than or equal to 3 characters.";
            self::response([], $message, FALSE, 403);
        }

        // name validation
        if(strlen($this->name) > 32){
            $message = "The length of name field shloud be less than or equal to 32 characters.";
            self::response([], $message, FALSE, 403);
        }

    }

    private function emailValidation()
    {
        // email validation - check the email is empty
        if(empty($this->email)){
            $message = "The email field is required.";
            self::response([], $message, FALSE, 403);
        }

        // email validation - check the email is invalid
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL) || strlen($this->email) < 6 || strlen($this->email) > 40){
            $message = "Invalid email.";
            self::response([], $message, FALSE, 403);
        }

        // email validation - check the email if exist
        if(QueryBuilder::get('users', 'email', '=', $this->email)){
            $message = "Email is alerady taken, please pick up another one.";
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

        // password validation
        if($this->password_confirm !== $this->password){
            $message = "Password confirmation doesn't match.";
            self::response([], $message, FALSE, 403);
        }        
    }

    private function craeteNewAccount()
    {
        $data = [
            'name' => $this->name,
            'password' => password_hash($this->password, PASSWORD_DEFAULT),
            'email' => $this->email,
        ];

        try{
            QueryBuilder::insert('users', $data);
            self::response([], '', TRUE, 200);
        } catch (Exception $e) {
            self::response([], $e->getMessage(), FALSE, $e->getCode());
        }

    }

}
