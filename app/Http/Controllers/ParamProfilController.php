<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\ParamProfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParamProfilController extends Controller
{
    public function index()
    {
        $roleId = Auth::user()->role_id;

        $menus = Menu::whereNull('parent_id')
        ->where(function ($query) use ($roleId) {
            $query->where('role_id', $roleId)
                ->orWhereNull('role_id');
        })
        ->with(['children' => function ($query) use ($roleId) {
            $query->where('role_id', $roleId)
                ->orWhereNull('role_id');
        }])
        ->orderBy('order')
        ->get();

        $title = 'Parameter Profil';

        return view('param_profil.index', compact('menus', 'title'));
    }

    public function getData()
    {
        $param_profil = ParamProfil::orderBy('deskripsi', 'asc')->get();

        return response()->json(['data' => $param_profil]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required',
            'level' => 'required',
            'kategori_param' => 'required',
        ]);

        ParamProfil::create($request->all());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function edit($id)
    {
        $data = ParamProfil::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'deskripsi' => 'required',
            'level' => 'required',
            'kategori_param' => 'required',
        ]);

        $data = ParamProfil::findOrFail($id);
        $data->update($request->all());
        return response()->json(['message' => 'Data berhasil diperbarui']);
    }

    public function destroy($id)
    {
        ParamProfil::destroy($id);
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
