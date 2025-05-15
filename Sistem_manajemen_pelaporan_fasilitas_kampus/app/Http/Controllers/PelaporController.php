use Illuminate\Http\Request;
use App\Models\UserModel;

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
class PelaporController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelapor = UserModel::all();
        return view('pelapor.index', compact('pelapor'));
    }

    public function profile()
    {
        $pelapor = UserModel::all();
        return view('pelapor.profile', compact('pelapor'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pelapor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pelapor,email',
            'phone' => 'required|string|max:15',
        ]);

        UserModel::create($request->all());

        return redirect()->route('pelapor.index')->with('success', 'UserModel created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pelapor = UserModel::findOrFail($id);
        return view('pelapor.show', compact('pelapor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pelapor = UserModel::findOrFail($id);
        return view('pelapor.edit', compact('pelapor'));
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

        $pelapor = UserModel::findOrFail($id);
        $pelapor->update($request->all());

        return redirect()->route('pelapor.index')->with('success', 'UserModel updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pelapor = UserModel::findOrFail($id);
        $pelapor->delete();

        return redirect()->route('pelapor.index')->with('success', 'UserModel deleted successfully.');
    }
}