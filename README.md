# staff_task_approval

APP FLOW:

-Authentication should be handled using Laravel Passport or Sanctum. <br>
A logged in user can either be a staff or an approver
A logged-in user should belong to a department
A logged-in staff should be able to add tasks.
A logged-in staff should be able to see all the tasks they’ve added.
Once a task is marked completed a mail should be sent to the approver associated to the department of the staff
A logged-in approver should be able to see the tasks sent to him to be approved
Once a task is approved by the approver, a mail should be sent to the staff that created the task.
A staff should only be able to delete tasks that have not been approved.
