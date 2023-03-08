<?php
namespace App\Controllers\Api;
use App\Core\Support\QueryBuilder;
use Carbon\Carbon;
use App\Core\Support\Mail;
class ForgetPasswordController extends API
{
    private string $email;

    public function forgetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['email'])) {
                $request = $this->request();
                // assign preperties
                $this->email = htmlspecialchars(strip_tags($request->email));

                // check the validations
                $this->validation();

                // check status, and send mail
                $this->forgetPasswordScenarios();

            }
        }
    }

    private function validation()
    {
        self::generalValidation();
        self::securityValidation();
        $this->emailValidation();
    }

    private function emailValidation()
    {
        // email validation
        if (empty($this->email)) {
            $message = "The email field is required.";
            self::response([], $message, FALSE, 422);
        }

        // email validation
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL) || strlen($this->email) < 6 || strlen($this->email) > 40) {
            $message = "Invalid email.";
            self::response([], $message, FALSE, 422);
        }
    }

    private function forgetPasswordScenarios()
    {
        // get user
        $user = QueryBuilder::get('users', 'email', '=', $this->email);
        $old_token = QueryBuilder::get('password_resets', 'email', '=', $this->email);

        if(!$user){
            $this->checkIfEmailNotExist();
        }

        if($user && !$old_token){
            $this->firstSending();
        }
        
        $diffInMinutes = Carbon::now()->diffInMinutes(Carbon::parse($old_token->created_at));
        
        // dont send                            
        if(($user) && ($old_token) && ($diffInMinutes < env('TOKEN_EXPIRATION_TIME'))){
            $this->secondSendingWhenTheTokenIsActive();
        }

        // update and send                         
        if(($user) && ($old_token) && ($diffInMinutes >= env('TOKEN_EXPIRATION_TIME'))){
            $this->thirdSendingAfterUnblock();
        }
    }

    private function checkIfEmailNotExist()
    {    
        sleep(3);
        self::response([], "", TRUE, 200);
    }

    private function firstSending()
    {
        $token = $this->generateUniqueToken();
        // Insert the email and token into the password_resets table
        $data = ['email' => $this->email, 'token' => $token];
        QueryBuilder::insert('password_resets', $data);
        $this->sendEmail($token);
    }

    private function secondSendingWhenTheTokenIsActive()
    {
        $message = "You cannot send more than one email within one hour.";
        self::response([], $message, FALSE, 422);
    }

    private function thirdSendingAfterUnblock()
    {       
        $token = $this->generateUniqueToken();
        $data = ['reset_status' => 0, 'token' => $token, 'created_at' => Carbon::now()];
        QueryBuilder::update('password_resets', $data, 'email', '=', $this->email);
        $this->sendEmail($token);
    }

    private function sendEmail($token)
    {
        // start - prepare the data of email
        $subject = env('APP_NAME') . " Account recovery information";
        $url = main_url() . "/reset-password?email={$this->email}&token={$token}";
        $HTML_message = file_get_contents(view_path() . 'emails/forget-passowrd-email.html');
        $HTML_message = str_replace('{url}', $url, $HTML_message);
        // end - prepare the data of email 

        // send mail        
        if(Mail::sendMail($this->email, $subject, $HTML_message)){
            self::response([], '', TRUE, 250);
        }else{
            self::response([], 'Internal Server Error', FALSE, 500);
        }
    }

    private function generateUniqueToken()
    {
        $token = bin2hex(random_bytes(25));
        $old_token = QueryBuilder::get('password_resets', 'token', '=', $token);
        if($old_token){
            // Token already exists in the database
            return $this->generateUniqueToken();
        }else{
            // Token does not exist in the database
            return $token;
        }
        
    }
}