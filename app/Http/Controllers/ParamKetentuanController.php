<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\ParamKetentuan;
use App\Models\ParamProfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParamKetentuanController extends Controller
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

        $title = 'Parameter Ketentuan';

        $param_profil = ParamProfil::get();

        return view('param_ketentuan.index', compact('menus', 'title', 'param_profil'));
    }

    public function getData()
    {
        $param_ketentuan = ParamKetentuan::orderBy('nomor_ketentuan', 'asc')->get();

        return response()->json(['data' => $param_ketentuan]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_ketentuan' => 'required',
            'heading' => 'required',
        ]);

        ParamKetentuan::create($request->all());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function edit($id)
    {
        $data = ParamKetentuan::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_ketentuan' => 'required',
            'heading' => 'required',
        ]);

        $data = ParamKetentuan::findOrFail($id);
        $data->update($request->all());
        return response()->json(['message' => 'Data berhasil diperbarui']);
    }

    public function destroy($id)
    {
        ParamKetentuan::destroy($id);
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
