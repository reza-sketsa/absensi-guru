<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Anda bukan admin.');
        }
        return view('admin.dashboard');
    }
}
