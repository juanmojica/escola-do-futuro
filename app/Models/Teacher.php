<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $appends = ['name', 'email'];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function getNameAttribute()
    {
        return $this->user ? $this->user->name : '';
    }

    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : '';
    }
}
