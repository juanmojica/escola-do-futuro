<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\User;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'birth_date',
    ];

    protected $dates = [
        'birth_date',
        'deleted_at',
    ];

    protected $appends = ['name', 'email'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('enrollment_date', 'status')
            ->withTimestamps();
    }

    public function getNameAttribute()
    {
        return $this->user ? $this->user->name : '';
    }

    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : '';
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->age : null;
    }
}
