<?php
namespace App\Helpers;

class MailHelper
{

    static function prepareEmail($userObj){
        $emailData['firstName'] = $userObj->name;
        $emailData['subject'] = 'الرد علي رسالتك : '.$userObj->address;
        $emailData['content'] = $userObj->reply;
        $emailData['to'] = $userObj->email;
        $emailData['template'] = "emailUsers.emailReplied";
        return self::SendMail($emailData);
    }

    static function SendMail($emailData){

        \Mail::send($emailData['template'], $emailData, function ($message) use ($emailData) {

            $fromEmailAddress = 'noreply@board.servers.com.sa';
            $fromDisplayName = 'تكافل';

            if(isset($emailData['fromEmailAddress'])){
                $fromEmailAddress = $emailData['fromEmailAddress'];
            }

            if(isset($emailData['fromDisplayName'])) {
                $fromDisplayName = $emailData['fromDisplayName'];
            }

            $message->from($fromEmailAddress, $fromDisplayName);

            $message->to($emailData['to'])->subject($emailData['subject']);

        });
    }
}
