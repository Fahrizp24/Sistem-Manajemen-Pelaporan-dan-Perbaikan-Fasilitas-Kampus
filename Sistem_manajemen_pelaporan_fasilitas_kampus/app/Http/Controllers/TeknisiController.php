<?php
namespace App\Http\Controllers;

use App\Models\LaporanModel;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'User Management',
            'list' => ['Tambah User', 'Baru']
        ];
    
        $page = (object) [
            'title' => 'Ajukan Penyelesaian'
        ];
    
        $activeMenu = 'user';

        return view('teknisi.penugasan', ['breadcrumb' => $breadcrumb, 'page'=> $page,'activeMenu' => $activeMenu]);
    }

    public function edit($id)
    {
        $pelapor = LaporanModel::findOrFail($id);
        return view('teknisi.edit', compact('pelapor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pelapor,email,' . $id,
            'phone' => 'required|string|max:15',
        ]);

        $pelapor = LaporanModel::findOrFail($id);
        $pelapor->update($request->all());

        return redirect()->route('teknisi.index')->with('success', 'UserModel updated successfully.');
    }
}
