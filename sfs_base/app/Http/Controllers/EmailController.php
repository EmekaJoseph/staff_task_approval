<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Mail;

class EmailController extends BaseController
{
    function completedTaskAlert($task_name, $email)
    {
        $data = [
            'task_name' => $task_name,
        ];

        $template = 'emails/completedTaskAlert';
        $topic = 'New Task Completed';
        $to = $email;
        $this->sendEmail($topic, $template, $data, $to);
    }

    function approvedTaskAlert($task_name, $email)
    {
        $data = [
            'task_name' => $task_name,
        ];

        $template = 'emails/approvedTaskAlert';
        $topic = 'Task Approved';
        $to = $email;
        $this->sendEmail($topic, $template, $data, $to);
    }


    private function sendEmail($topic, $template, $data, $email)
    {
        Mail::send($template, $data, function ($message) use ($email, $topic) {
            $message->to($email)->subject($topic);
            $message->from('josephiwuji.93@gmail.com', 'TASK');
        });
    }
}
