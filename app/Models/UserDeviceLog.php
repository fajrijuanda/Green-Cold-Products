<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeviceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'browser',
        'platform',
        'device',
        'ip_address',
        'location',
        'last_activity',
    ];

    // Cast last_activity to datetime
    protected $casts = [
        'last_activity' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
