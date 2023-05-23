<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class DeptModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tbl_dept';
    protected $primaryKey = 'dept_id';
    protected $guarded = [];
    public $timestamps = false;

    public function relatnUsers()
    {
        return $this->hasMany(UserModel::class, 'user_id');
    }

    public function relatnTasks()
    {
        return $this->hasMany(TaskModel::class, 'dept_id');
    }
}
