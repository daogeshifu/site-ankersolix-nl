<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    // 指定可填充的字段
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'ip'
    ];
}
