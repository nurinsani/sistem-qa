<?php

namespace App\Http\Controllers\Qam;

use App\Http\Controllers\Controller;
use App\Models\DataSampling;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

         $dataSamplings = DataSampling::where('approval', 2220)
            ->with(['branch', 'kelompok', 'ao'])
            ->get();

        return view('qam.approval.index', compact('title', 'menus', 'dataSamplings'));
    }

    public function updateStatus(Request $request, $id)
    {

        // Cari data berdasarkan ID
        $sampling = DataSampling::findOrFail($id);
        
        // Update status
        $sampling->approval = $request->approval;
        $sampling->save();

        return redirect()->route('qam.approval.index')->with('success', 'Status berhasil diperbarui!');
    }
    
}
