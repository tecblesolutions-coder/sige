<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'document_type',
        'document_number',
        'middle_name',
        'address',
        'email',
        'phone',
        'job_title',
        'status',
        'department_id',
        'position_id',
        'country_id',
        'state_id',
        'city_id',
        'zip_code',
        'birthdate',
        'date_hired',
        'user_id',
    ];

    protected $casts = [
        'birthdate' => 'datetime',
        'date_hired' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
