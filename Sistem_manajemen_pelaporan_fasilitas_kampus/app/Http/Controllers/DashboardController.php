<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $page = [
        'title' => 'Dashboard',
        'subtitle' => 'Halaman Utama Sistem'
    ];

    $breadcrumb = [
        'title' => 'Dashboard'
    ];

    return view('dashboard', compact('page', 'breadcrumb'));
}
}
