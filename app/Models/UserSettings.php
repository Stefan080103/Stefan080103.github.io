<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'push_notifications',
        'sms_notifications',
        'show_activity_status',
        'allow_message_requests',
        'account_privacy'
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'show_activity_status' => 'boolean',
        'allow_message_requests' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
