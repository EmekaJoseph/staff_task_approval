<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\TaskModel;
use App\Models\UserModel;

use App\Http\Controllers\EmailController;

class TaskController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // function to create new TASK
    public function taskCreate(Request $req)
    {

        // check if user is a staff
        if ($req->user()->role !== 'staff') {
            return response()->json(['error' => 'not a staff'], 203);
        }

        // get the title of task
        $task_name = $req->input('task_name');

        // check if it already exists?
        if (TaskModel::where('task_name', $task_name)->where('user_id', $req->user()->user_id)->exists()) {
            return response()->json(['error' => 'task already exists'], 203);
        }

        $start = Carbon::parse($req->input('start_date'));
        $end = Carbon::parse($req->input('end_date'));

        // create the new task
        $newTask = new TaskModel();
        $newTask->user_id = $req->user()->user_id;
        $newTask->dept_id = $req->user()->dept_id;
        $newTask->task_name = $task_name;
        $newTask->start_date = $start->toDateString();;
        $newTask->end_date = $end->toDateString();;

        $newTask->save();
        return response()->json(['success' => 'created successfully'], 200);
    }


    // function to see all TASKS
    public function taskList(Request $req)
    {
        // check if user is a staff
        if ($req->user()->role !== 'staff') {
            return response()->json(['error' => 'not a staff'], 203);
        }

        // get tasks related to this user
        $taskData = TaskModel::where('user_id', $req->user()->user_id)->get();

        return response()->json($taskData, 200);
    }



    // function to delete un-approved TASKS
    public function taskDelete(Request $req, $task_id)
    {

        // check if user is a staff
        if ($req->user()->role !== 'staff') {
            return response()->json(['error' => 'not a staff'], 203);
        }

        // make sure task is found and not approved
        $relatedTask = TaskModel::find($task_id);
        if ($relatedTask) {
            if ($relatedTask->is_approved == '1') {
                return response()->json(['error' => 'cannot not delete approved task'], 203);
            }
            // delete task
            $relatedTask->delete();
            return response()->json(['success' => 'deleted'], 200);
        }

        return response()->json(['error' => 'not found'], 203);
    }



    // function to mark a TASK as 'complete'
    public function taskMarkComplete(Request $req, $task_id)
    {
        // check if user is a staff
        if ($req->user()->role !== 'staff') {
            return response()->json(['error' => 'not a staff'], 203);
        }

        $relatedTask = TaskModel::find($task_id);
        $relatedDept = $relatedTask->relatnDept;
        $approver = UserModel::where('dept_id', $relatedDept->dept_id)->where('role', 'approver')->first();


        // check if task is found and mark 'completed= 1'
        if ($relatedTask) {
            $relatedTask->is_completed = '1';
            $relatedTask->save();

            // send mail to approver for Approval
            try {
                $mailer = new EmailController();
                $mailer->completedTaskAlert($relatedTask->task_name, $approver->email);
            } catch (\Throwable $th) {
                // throw $th;
            }

            return response()->json(['success' => 'updated successfully'], 200);
        }

        return response()->json(['error' => 'not found'], 203);
    }



    // function to see list of completed TASKS
    public function taskCompletedList(Request $req)
    {
        // check if user is an approver
        if ($req->user()->role !== 'approver') {
            return response()->json(['error' => 'not an approver'], 203);
        }

        // find un-approved tasks related to this approver and are completed
        $relatedDept = $req->user()->relatnDept;
        $taskData = TaskModel::where('dept_id', $relatedDept->dept_id)
            ->where('is_approved', '0')
            ->where('is_completed', '1')->get();

        return response()->json($taskData, 200);
    }

    // function to approve a task
    public function taskApprove(Request $req, $task_id)
    {
        // check if user is an approver
        if ($req->user()->role !== 'approver') {
            return response()->json(['error' => 'not an approver'], 203);
        }

        $relatedTask = TaskModel::find($task_id);

        // check if task is found and mark 'is_approved = 1'
        if ($relatedTask) {
            $relatedTask->is_approved = '1';
            $relatedTask->save();

            // send mail
            try {
                $relatedUser = $relatedTask->relatnUser;
                $mailer = new EmailController();
                $mailer->approvedTaskAlert($relatedTask->task_name, $relatedUser->email);
            } catch (\Throwable $th) {
                throw $th;
            }

            return response()->json(['success' => 'approved successfully'], 200);
        }



        return response()->json(['error' => 'not found'], 203);
    }
}
