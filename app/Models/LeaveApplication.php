<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'is_emergency',
        'leave_type',
        'subject',
        'message',
        'attached_files',
        'reply_message',
        'status'
    ];

    public function employees()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leave_application_dates()
    {
        return $this->hasMany(LeaveApplicationDate::class);
    }
}
