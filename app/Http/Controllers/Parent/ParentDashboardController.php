<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    /**
     * Show parent dashboard with list of children.
     */
    public function index()
    {
        $parent = Auth::user();   // mzazi alielogin

        // watoto wake (through pivot)
        $children = $parent->children()
            ->with('class')       // tusije kufanya query nyingi
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        return view('parent.dashboard', compact('parent', 'children'));
    }
}
