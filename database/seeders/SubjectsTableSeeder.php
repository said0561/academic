<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Department;

class SubjectsTableSeeder extends Seeder
{
    public function run(): void
    {
        $dini = Department::where('code', 'DINI')->first();
        $sec  = Department::where('code', 'SEC')->first();

        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH', 'department_id' => $sec?->id],
            ['name' => 'English',     'code' => 'ENG',  'department_id' => $sec?->id],
            ['name' => 'Science',     'code' => 'SCI',  'department_id' => $sec?->id],
            ['name' => 'Kiswahili',   'code' => 'KISW', 'department_id' => $sec?->id],
            ['name' => 'Tarbia', 'code' => 'ISL', 'department_id' => $dini?->id],
        ];

        foreach ($subjects as $subj) {
            Subject::firstOrCreate(['code' => $subj['code']], $subj);
        }
    }
}
