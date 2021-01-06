<?php
namespace App\Helpers;
use App\Models\Preference;

class MailHelper
{

    static function prepareEmail($userObj,$uuid,$status){
        $statusText = '';
        $statusSentence = '';
        if($status == 0){
            $statusText = 'task_phase_changed';
            $statusSentence = "Task Phase Changed ";
        }elseif($status == 1){
            $statusText = 'task_deadline_changed';
            $statusSentence = "Task Deadline Changed ";
        }elseif($status == 2){
            $statusText = 'task_status_changed';
            $statusSentence = "Task Status Changed ";
        }elseif($status == 3){
            $statusText = 'task_assigned_to_you';
            $statusSentence = "Task Assigend To You ";
        }elseif($status == 4){
            $statusText = 'task_assigned_to_team_member';
            $statusSentence = "Task Assigend To Team Member ";
        }elseif($status == 5){
            $statusText = 'assigned_to_project';
            $statusSentence = "Project Assigend To You ";
        }elseif($status == 6){
            $statusText = 'project_status_changed';
            $statusSentence = "Project Status Changed ";
        }elseif($status == 7){
            $statusText = 'project_timeline_changed';
            $statusSentence = "Project Timeline Changed ";
        }elseif($status == 8){
            $statusText = 'team_member_joined_project';
            $statusSentence = "Team Member Joined Project ";
        }

        if($userObj){
            $preferenceObj = Preference::where('preferencable_id',$userObj->id)->where('key','email_notifications.task_phase_changed')->where('value',1)->first();
            if($preferenceObj){
                $emailData['firstName'] = $userObj->name;
                $emailData['subject'] = $statusSentence.$uuid;
                $emailData['to'] = $userObj->email;
                $emailData['template'] = "emailUsers.taskChanged";
                return self::SendMail($emailData);
            }
        }
    }

    static function SendMail($emailData){

        \Mail::send($emailData['template'], $emailData, function ($message) use ($emailData) {

            $fromEmailAddress = 'noreply@board.servers.com.sa';
            $fromDisplayName = 'Board';

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
