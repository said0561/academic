<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;



// Hawa controllers wa mfumo mpya wa shule (utawaunda taratibu)
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ClassSubjectController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Teacher\ResultEntryController;
use App\Http\Controllers\Parent\ParentResultController;
use App\Http\Controllers\Admin\ClassReportController;
use App\Http\Controllers\Admin\StudentImportController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Parent\ParentDashboardController;
use App\Http\Controllers\Academic\AcademicDashboardController;



use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// ... routes zako nyingine hapa juu au chini

Route::get('/create-admin', function () {
    try {
        /*
         |---------------------------------------------------------
         | 0. CREATE ALL CORE TABLES IF THEY DON'T EXIST
         |---------------------------------------------------------
        */

        // USERS
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // ROLES
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('slug');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // ROLE_USER (pivot)
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('role_id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // STUDENTS
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('first_name');
                $table->string('middle_name')->nullable();
                $table->string('last_name');
                // MySQL enum('M','F') -> string(1)
                $table->string('gender', 1)->nullable();
                $table->date('dob')->nullable();
                $table->unsignedBigInteger('class_id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // CLASSES
        if (!Schema::hasTable('classes')) {
            Schema::create('classes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('stream')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // DEPARTMENTS
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('code');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // Seed default departments: SECULAR & DINI kama table bado haina rekodi
        if (Schema::hasTable('departments') && DB::table('departments')->count() === 0) {
            DB::table('departments')->insert([
                [
                    'name'       => 'SECULAR',
                    'code'       => 'SECULAR',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name'       => 'DINI',
                    'code'       => 'DINI',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // SUBJECTS
        if (!Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('code');
                $table->unsignedBigInteger('department_id')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // CLASS_SUBJECT
        if (!Schema::hasTable('class_subject')) {
            Schema::create('class_subject', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('class_id');
                $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('teacher_user_id')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // EXAMS
        if (!Schema::hasTable('exams')) {
            Schema::create('exams', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('term')->nullable();
                $table->string('year', 4); // year(4) -> string(4)
                $table->unsignedInteger('total_marks')->default(100);
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // EXAM_SCHEDULES
        if (!Schema::hasTable('exam_schedules')) {
            Schema::create('exam_schedules', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('exam_id');
                $table->unsignedBigInteger('class_id');
                $table->unsignedBigInteger('subject_id');
                $table->date('exam_date')->nullable();
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // RESULTS
        if (!Schema::hasTable('results')) {
            Schema::create('results', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('exam_id');
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('subject_id');
                $table->decimal('score', 5, 2)->nullable();
                $table->string('grade', 2)->nullable();
                $table->string('remarks')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        // PARENT_STUDENT
        if (!Schema::hasTable('parent_student')) {
            Schema::create('parent_student', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('parent_user_id');
                $table->unsignedBigInteger('student_id');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
            });
        }

        /*
         |---------------------------------------------------------
         | 1. ENSURE ADMIN ROLE + USER
         |---------------------------------------------------------
        */

        $adminRole = DB::table('roles')->where('slug', 'admin')->first();

        if (!$adminRole) {
            $roleId = DB::table('roles')->insertGetId([
                'name'       => 'Administrator',
                'slug'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $roleId = $adminRole->id;
        }

        $phone = '255743434305';

        $user = User::firstOrCreate(
            ['phone' => $phone],
            [
                'name'     => 'System Admin',
                'email'    => 'admin@example.com',
                'phone'    => $phone,
                'password' => Hash::make('Admin12345'),
            ]
        );

        DB::table('role_user')->updateOrInsert(
            [
                'user_id' => $user->id,
                'role_id' => $roleId,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return response()->json([
            'ok'       => true,
            'message'  => 'All core tables ensured, departments seeded, admin ready.',
            'user_id'  => $user->id,
            'phone'    => $user->phone,
            'email'    => $user->email,
            'role_id'  => $roleId,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'ok'    => false,
            'error' => $e->getMessage(),
        ], 500);
    }
});



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Generic dashboard (optional – unaweza baadaye ku-redirect kulingana na role)
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('teacher')) {
        return redirect()->route('teacher.dashboard');
    }

    if ($user->hasRole('parent')) {
        return redirect()->route('parent.dashboard');
    }

    if ($user->hasRole('academic')) {
        return redirect()->route('academic.dashboard');
    }

    // fallback – kama hana role yoyote iliyo juu
    return view('dashboard'); // au u-mtupie 403, choice yako
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes – hizi ziko sawa
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes  (Administrator)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // 👉 Bulk upload students - WEKA JUU YA resource
        Route::get('students/import', [StudentImportController::class, 'create'])
            ->name('students.import');

        Route::post('students/import', [StudentImportController::class, 'store'])
            ->name('students.import.store');
        // 👉 NEW: download template
        Route::get('students/import/template', [StudentImportController::class, 'template'])
            ->name('students.import.template');

        // Manage school structure
        Route::resource('classes', ClassController::class);
        Route::resource('students', StudentController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('exams', ExamController::class);

        // Manage subjects & teacher assignments for a class
        Route::get('classes/{class}/subjects', [ClassSubjectController::class, 'edit'])
            ->name('classes.subjects.edit');

        Route::post('classes/{class}/subjects', [ClassSubjectController::class, 'update'])
            ->name('classes.subjects.update');

        Route::get('classes/{class}/exam-report', [ClassReportController::class, 'index'])
            ->name('classes.exam-report');

        Route::get('classes/{class}/exam-report/{student}', [ClassReportController::class, 'studentReport'])
            ->name('classes.exam-report.student');

        //User Management
        Route::resource('users', UserController::class);

        // Results management (full access)
        Route::get('results', [ResultController::class, 'index'])->name('results.index');
        Route::get('results/create', [ResultController::class, 'create'])->name('results.create');
        Route::post('results', [ResultController::class, 'store'])->name('results.store');
        Route::get('results/{result}/edit', [ResultController::class, 'edit'])->name('results.edit');
        Route::put('results/{result}', [ResultController::class, 'update'])->name('results.update');
        Route::delete('results/{result}', [ResultController::class, 'destroy'])->name('results.destroy');
    });


/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')->name('teacher.')
    ->group(function () {

        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

        // Teacher enters results for their classes
                Route::get('results/entry', [ResultEntryController::class, 'index'])
            ->name('results.entry');
        

        Route::get('results/enter/{class}/{subject}', [ResultEntryController::class, 'create'])
            ->name('results.create');

        Route::post('results/enter/{class}/{subject}', [ResultEntryController::class, 'store'])
            ->name('results.store');

            Route::get('results/view/{class}/{subject}', [ResultEntryController::class, 'show'])
             ->name('results.show');

                // Download CSV template for one exam + class + subject
        Route::get('results/template', [ResultEntryController::class, 'downloadTemplate'])
            ->name('results.template');

        // Upload filled CSV (tutafanya hatua inayofuata)
        Route::post('results/import', [ResultEntryController::class, 'importCsv'])
            ->name('results.import');

    });

/*
|--------------------------------------------------------------------------
| Parent Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:parent'])
    ->prefix('parent')->name('parent.')
    ->group(function () {

        Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');

        // View children results
        Route::get('/results/{student}', [ParentResultController::class, 'index'])
            ->name('results.show');

        // NEW: Parent report card
        Route::get('/results/{student}/report-card', [ParentResultController::class, 'report'])
            ->name('results.report');

            Route::get('/results/{student}/report-card', [ParentResultController::class, 'report'])
            ->name('results.report-card');

    });

/*
|--------------------------------------------------------------------------
| Academic Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:academic'])
    ->prefix('academic')->name('academic.')
    ->group(function () {

        Route::get('/dashboard', [AcademicDashboardController::class, 'index'])->name('dashboard');

        // hapa unaweza kurudia baadhi ya routes za exams/results kwa academic
        // kama vile ku-approve results nk.
    });

require __DIR__.'/auth.php';
