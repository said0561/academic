<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'gender', 'dob', 'class_id','middle_name','phone',
    ];

    public function class()
    {
        // table yetu inaitwa 'classes', model tutaiita SchoolClass
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function parents()
    {
        // parent_student pivot: parent_user_id <-> student_id
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_user_id');
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
