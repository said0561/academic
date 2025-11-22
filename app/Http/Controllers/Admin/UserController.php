<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // 20 per page each group
        $admins = User::whereHas('roles', fn($q) => $q->where('slug', 'admin'))
            ->orderBy('name')
            ->paginate(20, ['*'], 'admins_page');

        $teachers = User::whereHas('roles', fn($q) => $q->where('slug', 'teacher'))
            ->orderBy('name')
            ->paginate(20, ['*'], 'teachers_page');

        $parents = User::whereHas('roles', fn($q) => $q->where('slug', 'parent'))
            ->orderBy('name')
            ->paginate(20, ['*'], 'parents_page');

        $academic = User::whereHas('roles', fn($q) => $q->where('slug', 'academic'))
            ->orderBy('name')
            ->paginate(20, ['*'], 'academic_page');

        return view('admin.users.index', compact(
            'admins',
            'teachers',
            'parents',
            'academic'
        ));
    }


    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // roles kama: admin, teacher, academic, parent
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'phone' => [
                'required',
                'regex:/^255[0-9]{9}$/',
                'unique:users,phone',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
            ],

            // moja kwa sasa: admin / teacher / academic / parent
            'role' => ['required', 'string', 'exists:roles,slug'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'email'    => $data['email'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        // mpe role moja kulingana na slug
        $user->assignRole($data['role']);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        // slug ya role ya sasa (tukichukulia ana role moja kuu)
        $currentRoleSlug = optional($user->roles->first())->slug;

        // Students wote, pamoja na class yao
        $students = \App\Models\Student::with('class')
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        // Classes zote (kwa filter)
        $classes = \App\Models\SchoolClass::orderBy('name')
            ->orderBy('stream')
            ->get();

        // ids za watoto wa user huyu (kama ni parent)
        $childrenIds = $user->children()->pluck('students.id')->toArray();

        return view('admin.users.edit', compact(
            'user',
            'roles',
            'currentRoleSlug',
            'students',
            'childrenIds',
            'classes',      // 👈 mpya
        ));
    }


    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'phone' => [
                'required',
                'regex:/^255[0-9]{9}$/',
                'unique:users,phone,' . $user->id,
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            // password inaweza kuachwa tupu (hakuna mabadiliko)
            'password' => [
                'nullable',
                'string',
                'min:6',
            ],

            'role' => ['required', 'string', 'exists:roles,slug'],
        ]);

        // Basic fields
        $user->name  = $data['name'];
        $user->phone = $data['phone'];
        $user->email = $data['email'] ?? null;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        // Sync role moja
        $role = Role::where('slug', $data['role'])->firstOrFail();
        $user->roles()->sync([$role->id]);

        // 🔥 Parent–Student linking logic
        // Tukigundua role ni 'parent', tunasync watoto kwenye pivot.
        if ($data['role'] === 'parent') {
            // children_ids kutoka kwenye form (checkboxes)
            $childrenIds = $request->input('children_ids', []); // array au []

            // sync pivot: parent_student
            $user->children()->sync($childrenIds);
        } else {
            // Kama role si parent tena, tuhakikishe hana watoto linked
            $user->children()->detach();
        }

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User updated successfully.');
    }


    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // unaweza kuweka guard: usijifute wewe mwenyewe au super admin
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User deleted.');
    }
}
