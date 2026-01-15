<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 *   `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `task_id` int(11) DEFAULT '0',
  `total` int(11) DEFAULT '0' COMMENT '本次操作的数量',
  `before_amount` int(11) DEFAULT '0' COMMENT '操作之前的余额',
  `after_amount` int(11) DEFAULT '0' COMMENT '操作之后的余额',
  `date_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '时间',
  `type` int(11) DEFAULT '2' COMMENT '1 加:系统充值 2 减:geo消耗 3、加:系统赠送',
 */
class UserAmountLog extends Model
{
    use HasFactory;

    protected $table = 'user_amount_logs';

    protected $fillable = [
        'user_id', 
        'task_id', 
        'total', 
        'before_amount', 
        'after_amount', 
        'date_time', 
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * 获取用户消耗的金额
     */
    public static function getAmountConsumed($user_id = false)
    {
        $user_id = Auth::id();
        return self::where('user_id', $user_id)->where('type', 2)->sum('total');
    }

    /**
     * 获取用户充值的金额
     */
    public static function getAmountRecharged($user_id = false)
    {
        $user_id = Auth::id();
        return self::where('user_id', $user_id)->whereIn('type', [1,3])->sum('total');
    }


}
