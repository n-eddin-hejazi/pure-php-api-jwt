<?php
namespace App\Core\Support;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class Mail
{
    public static function sendMail($to, $subject, $HTML_message)
    {
        // new instance 
        $mail = new PHPMailer();
        $mail->IsSMTP();
        // $mail->SMTPDebug = 3;
        $mail->SMTPAuth = true;
        $mail->Host = env('MAIL_HOST');
        $mail->SMTPSecure = env('MAIL_ENCRYPTION');
        $mail->Port = env('MAIL_PORT');
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->CharSet = 'utf-8';
        $mail->SetFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        $mail->Subject = $subject;
        // body of mail    
        $mail->MsgHTML($HTML_message);
        $mail->ClearReplyTos();
        $mail->AddReplyTo(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        $mail->AddAddress($to);
        $send_status = $mail->Send();

        // return TRUE of FALSE
        return $send_status;

        // if ($mail->Send()) {
        //     session()->setFlash('success', "You will receive an email if your email is registered.");
        //     return back();
        // } else {
        //     session()->setFlash('fail', "fail.");
        //     return back();
        // }
    }
}