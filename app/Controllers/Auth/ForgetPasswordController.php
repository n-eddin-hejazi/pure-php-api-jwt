<?php
namespace App\Controllers\Auth;
use Carbon\Carbon;
use App\Core\Support\Mail;
use App\Core\Support\QueryBuilder;
class ForgetPasswordController
{
    
    private string $email;

    public function index()
    {
        ifAuth();
        return view('auth.forget-password');
    }

    public function forgetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['email'])) {
                // assign preperties
                $this->email = htmlspecialchars(strip_tags($_POST['email']));

                // check the validations
                $this->validation();

                // check status, and send mail
                $this->forgetPasswordScenarios();

            }
        }
    }

    private function validation()
    {
        $email_errors = $this->emailValidation();
        if (!empty($email_errors)) {
            session()->setFlash('email_errors', $email_errors);
        }

        if (!empty($email_errors)) {
            session()->setFlash('email', $this->email);
            return back();
        }

    }

    private function emailValidation()
    {
        $email_errors = [];
        // email validation
        if (empty($this->email)) {
            $email_errors[] = "The email field is required.";
        }

        // email validation
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL) || strlen($this->email) < 6 || strlen($this->email) > 40) {
            $email_errors[] = "Invalid email.";
        }

        return $email_errors;
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
        session()->setFlash('success', "You will receive an email if your email is registered.");
        return to('login');
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
        session()->setFlash('fail', "You cannot send more than one email within one hour");
        return back();
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
            session()->setFlash('success', "You will receive an email if your email is registered.");
            return to('login');  
        }else{
            session()->setFlash('fail', "There is an error, please try again later!.");
            return back();
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
