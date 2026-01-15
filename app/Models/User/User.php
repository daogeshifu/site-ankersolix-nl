<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\User\UserInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'google_id',
        'avatar',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ipAddresses()
    {
        return $this->hasMany(IpAddress::class);
    }

    public function getLevel()
    {
        $arr = ['Regular Member','Bronze Member','Gold Member','Diamond Member','Platinum Member'];
        return $arr[$this->level] ?? 'Unknown';
    }

    public function info()
    {
        return $this->hasOne(UserInfo::class);
    }

    //user_avatar attribute ,b存在 则使用默认头像
    public function getUserAvatarAttribute()
    {
        //判断文件是否存在
        if (Storage::disk('public')->exists($this->avatar)) {
            return Storage::disk('public')->url($this->avatar);
        }
        return $this->avatar ?? '/aigc/static/picture/default-avatar.svg';
    }
}
