<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        $admin = UserModel::all();
        return view('admin.index', compact('admin'));
    }

    public function profile()
    {
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Detail Profile']
        ];
    
        $page = (object) [
            'title' => 'Profile'
        ];
    
        $activeMenu = 'profile';

        return view('admin.profile', ['breadcrumb' => $breadcrumb, 'page'=> $page,'activeMenu' => $activeMenu]);
        // $admin = UserModel::all();
        // return view('admin.profile', compact('admin'));
    }

    function pengguna()
    {
        $admin = UserModel::all();
        return view('admin.pengguna', compact('admin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_pengguna()
    {
        return view('admin.create_pengguna');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_pengguna(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'kata_sandi' => 'required|string|min:8',
            'peran' => 'required|string|in:admin,admin,sarpras,teknisi',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        UserModel::create($request->all());

        return redirect()->route('admin.index')->with('success', 'UserModel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show_pengguna()
    {
        $admin = UserModel::all();
        return view('admin.show_pengguna', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_pengguna($id)
    {
        $admin = UserModel::findOrFail($id);
        return view('admin.edit_pengguna', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_pengguna(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'kata_sandi' => 'required|string|min:8',
            'peran' => 'required|string|in:admin,admin,sarpras,teknisi',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $admin = UserModel::findOrFail($id);
        $admin->update($request->all());
        // if successfully updated
        if ($admin) {
            return redirect()->route('admin.pengguna')->with('success', 'UserModel updated successfully.');
        } else {
            return redirect()->route('admin.pengguna')->with('error', 'Failed to update UserModel.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admin = UserModel::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'UserModel deleted successfully.');
    }
}