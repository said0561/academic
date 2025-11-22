<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentImportController extends Controller
{
    /**
     * Show bulk upload form.
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('name')
            ->orderBy('stream')
            ->get();

        return view('admin.students.import', [
            'classes' => $classes,
        ]);
    }

    /**
     * Handle uploaded CSV and create students.
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'class_id' => ['required', 'exists:classes,id'],
        'file'     => ['required', 'file', 'mimes:csv,txt', 'max:2048'], // max ~2MB
    ]);

    $classId = $data['class_id'];
    $file    = $data['file'];

    $path   = $file->getRealPath();
    $handle = fopen($path, 'r');

    if (! $handle) {
        return back()->withErrors(['file' => 'Unable to read the uploaded file.']);
    }

    $headerPassed = false;
    $created = 0;

    DB::beginTransaction();

    try {
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {

            // skip blank lines
            if (count(array_filter($row)) === 0) {
                continue;
            }

            // skip header row (first row)
            if (! $headerPassed) {
                $headerPassed = true;
                continue;
            }

            // CSV columns: first_name,middle_name,last_name,gender,dob
            [$first, $middle, $last, $gender, $dob] = array_pad($row, 5, null);

            // basic validation
            if (!$first || !$last) {
                continue; // skip rows without main names
            }

            $gender = strtoupper(trim((string) $gender));
            if (!in_array($gender, ['M', 'F'])) {
                $gender = null;
            }

            $dob = $dob ? trim($dob) : null;

            // Normalize date to Y-m-d if possible
            if ($dob) {
                $dobNormalized = null;
                $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y'];

                foreach ($formats as $fmt) {
                    try {
                        $dobNormalized = Carbon::createFromFormat($fmt, $dob)->format('Y-m-d');
                        break;
                    } catch (\Exception $e) {
                        // continue
                    }
                }

                $dob = $dobNormalized;
            }

            Student::create([
                'first_name'  => trim($first),
                'middle_name' => $middle ? trim($middle) : null,
                'last_name'   => trim($last),
                'gender'      => $gender,
                'dob'         => $dob,
                'class_id'    => $classId,
            ]);

            $created++;
        }

        fclose($handle);
        DB::commit();

    } catch (\Throwable $e) {
        if (is_resource($handle)) {
            fclose($handle);
        }
        DB::rollBack();

        return back()->withErrors([
            'file' => 'Import failed: ' . $e->getMessage(),
        ]);
    }

    return redirect()
        ->route('admin.students.index')
        ->with('status', "Successfully imported {$created} students into this class.");
}



    public function template()
{
    $fileName = 'students_template.csv';

    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
    ];

    // Columns tunazotarajia
    $columns = ['first_name', 'middle_name', 'last_name', 'gender', 'dob'];

    $callback = function () use ($columns) {
        $handle = fopen('php://output', 'w');

        // header row
        fputcsv($handle, $columns);

        // mfano wa rows mbili za kuonyesha format
        fputcsv($handle, ['Ali', 'Hassan', 'Omar', 'M', '2015-03-12']);
        fputcsv($handle, ['Asha', '', 'Said', 'F', '2014-11-05']);

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}

}
