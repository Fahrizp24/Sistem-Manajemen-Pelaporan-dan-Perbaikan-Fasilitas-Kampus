<?php
namespace App\Http\Controllers;

class ContohController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'User Management',
            'list' => ['Tambah User', 'Baru']
        ];
    
        $page = (object) [
            'title' => 'Tambah User Baru'
        ];
    
        $activeMenu = 'user';

        return view('contoh', ['breadcrumb' => $breadcrumb, 'page'=> $page,'activeMenu' => $activeMenu]);
    }
}
