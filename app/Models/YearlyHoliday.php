<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearlyHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'holiday_date'
    ];

    protected $casts = [
        'holiday_date' => 'datetime'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
