<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['name', 'stream'];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(
                Subject::class,
                'class_subject',   // pivot table
                'class_id',        // foreign key ya class kwenye pivot
                'subject_id'       // foreign key ya subject kwenye pivot
            )
            ->withPivot('teacher_user_id')
            ->withTimestamps();
    }

    public function subjectAssignments()
    {
        return $this->hasMany(\App\Models\ClassSubject::class, 'class_id');
    }


}
