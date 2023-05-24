# staff_task_approval

Laravel Eloquent-ORM API with integrated frontend Framework (Vue js).

APP FLOW:

- Authentication is handled using Laravel Passport or Sanctum. <br>
- A logged in user can either be a staff or an approver <br>
- A logged-in user should belong to a department <br>
- A logged-in staff should be able to add tasks. <br>
- A logged-in staff should be able to see all the tasks they’ve added. <br>
- Once a task is marked completed a mail should be sent to the approver associated to the department of the staff <br>
- A logged-in approver should be able to see the tasks sent to him to be approved <br>
- Once a task is approved by the approver, a mail should be sent to the staff that created the task. <br>
- A staff should only be able to delete tasks that have not been approved. <br>

HOW TO RUN:

- Fork repo <br>
- Run 'composer install' in the sfs_base directory <br>
- Run 'npm install' in the sfs_vue directory <br>
- import the sfs_db into your local server (e.g: xampp) <br>
- connect to the database via Laravel 'Database.php' file or the provided .env file <br>
- run the sfs_base and sfs_vue with the commands: 'php artisan serve' and 'npm run dev' respectively. <br>
- The app is up and running!
