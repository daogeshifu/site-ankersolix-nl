<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'company',
        'department',
        'first_name',
        'last_name',
        'company_address',
        'city',
        'code',
        'country',
        'info',
    ];

    protected $hidden = [ 'created_at', 'updated_at' ];

}