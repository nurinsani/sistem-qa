<?php

namespace App\Http\Controllers\Qam;

use App\Http\Controllers\Controller;
use App\Models\DataSampling;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QamApprovalController extends Controller
{
    function index()
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

        $title = 'Approval';

        if (Auth::user()->code_qa == '2220') {
            $dataSamplings = DataSampling::where(function ($query) {
                $query->where('approval', '3330')
                    ->orWhere('approval', 'approved');
            })
            ->with(['branch', 'kelompok', 'ao'])
            ->get();
        } else {
            $dataSamplings = DataSampling::where(function ($query) {
                $query->where('approval', '3330')
                    ->orWhere('approval', 'approved');
            })
            ->with(['branch', 'kelompok', 'ao'])
            ->get();
        }

            // dd($dataSamplings);
        return view('qam.approval.index', compact('title', 'menus', 'dataSamplings'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'approval' => 'required'
        ]);

        // Cari data berdasarkan ID
        $sampling = DataSampling::findOrFail($id);
        
        $sampling->by = auth()->user()->code_qa;
        
        // Update status
        $sampling->approval = $request->approval;
        $sampling->save();

        return redirect()->route('qam.approval.index')->with('success', 'Status berhasil diperbarui!');
    }
    
}
