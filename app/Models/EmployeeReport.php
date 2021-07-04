<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'employee_id',
        'total_present',
        'total_absent',
        'total_weekly_holiday',
        'total_govt_holiday',
        'total_casual_leave',
        'total_sick_leave',
        'report_date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employees()
    {
        return $this->belongsTo(Employee::class);
    }
}
