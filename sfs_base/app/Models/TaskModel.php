<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TaskModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tbl_tasks';
    protected $primaryKey = 'task_id';

    protected $fillable = [
        'task_name',
        'dept_id',
        'user_id',
        'is_approved',
        'is_completed',
        'start_date',
        'end_date',
    ];

    public function relatnDept()
    {
        return $this->belongsTo(DeptModel::class, 'dept_id');
    }

    public function relatnUser()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
