<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tbl_users';
    protected $primaryKey = 'user_id';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = false;

    public function relatnDept()
    {
        return $this->belongsTo(DeptModel::class, 'dept_id');
    }

    public function relatnTasks()
    {
        return $this->hasMany(TaskModel::class, 'user_id');
    }
}
