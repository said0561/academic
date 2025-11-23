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


Route::get('/create-admin', function () {
    // 1. Pata ID ya role ya admin
    $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');

    if (!$adminRoleId) {
        abort(500, 'Admin role not found. Hakikisha roles table ina admin row.');
    }

    // 2. Tengeneza au pata user kwa kutumia PHONE
    $phone = '255743434305'; // weka namba yoyote halali ya mfumo wako inayostart na 255

    $user = User::firstOrCreate(
        ['phone' => $phone],
        [
            'name'     => 'System Admin',
            'email'    => 'admin@example.com', // lazima iwepo kwa validation/unique
            'phone'    => $phone,
            'password' => Hash::make('Admin12345'), // password ya ku-login
        ]
    );

    // 3. Mpe role ya admin kwenye role_user
    DB::table('role_user')->updateOrInsert(
        [
            'user_id' => $user->id,
            'role_id' => $adminRoleId,
        ],
        [
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );

    return response()->json([
        'message'  => 'Admin user created/updated with admin role.',
        'user_id'  => $user->id,
        'phone'    => $user->phone,
        'email'    => $user->email,
        'role_id'  => $adminRoleId,
    ]);
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
