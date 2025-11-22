<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'department_id',
        'is_active',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function classes()
    {
        return $this->belongsToMany(
                SchoolClass::class,
                'class_subject',   // pivot table
                'subject_id',      // foreign key ya subject kwenye pivot
                'class_id'         // foreign key ya class kwenye pivot
            )
            ->withPivot('teacher_user_id')
            ->withTimestamps();
    }



    public function results()
    {
        return $this->hasMany(Result::class);
    }


    public function subjectAssignments()
    {
        return $this->hasMany(\App\Models\ClassSubject::class, 'subject_id');
    }

}
