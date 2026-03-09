<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $companiesCount = Company::count();
        $activeCompaniesCount = Company::where('status', 'active')->count();
        $totalUsers = User::where('is_super_admin', false)->count();

        return view('superadmin.dashboard.index', compact('companiesCount', 'activeCompaniesCount', 'totalUsers'));
    }
}
