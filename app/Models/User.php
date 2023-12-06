<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'nickname',
        'password',
        'tel',
        'header_img',
        'email',
        'role_id',
        'status',
        'user_id',
        'last_user_id',
        'login_times',
    ];

    public array $statusMap = [
        1 => '正常',
        2 => '限制登陆',
    ];

    public array $roleMap = [
        1 => '管理员',
        2 => '普通用户',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public function getStatusStrAttribute(): string
    {
        $status = $this->attributes['status'];

        return $this->statusMap[$status];
    }

    public function getRoleStrAttribute(): string
    {
        $role = $this->attributes['role_id'];

        return $this->roleMap[$role];
    }
}
