<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Child;
use App\Models\GrowthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // Statistik dashboard admin
    public function stats()
    {
        return response()->json([
            'total_users'    => User::where('role','orangtua')->count(),
            'total_children' => Child::count(),
            'total_records'  => GrowthRecord::count(),
            'need_attention' => GrowthRecord::whereIn('nutritional_status',['gizi_buruk','gizi_kurang'])
                                    ->distinct('child_id')->count('child_id'),
        ]);
    }

    // Semua user orang tua
    public function users(Request $request)
    {
        $q = User::where('role','orangtua')->withCount('children');
        if ($request->search) $q->where('name','like',"%{$request->search}%");
        return response()->json($q->latest()->paginate(10));
    }

    public function showUser($id)
    {
        return response()->json(User::with(['children.growthRecords','children.vaccines'])->findOrFail($id));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') return response()->json(['message' => 'Tidak bisa hapus admin'], 403);
        $user->delete();
        return response()->json(['message' => 'Pengguna dihapus']);
    }

    // Semua anak
    public function children(Request $request)
    {
        $q = Child::with(['user','growthRecords'=>fn($q)=>$q->latest()->limit(1)]);
        if ($request->search) $q->where('name','like',"%{$request->search}%");
        return response()->json($q->latest()->paginate(10));
    }

    public function deleteChild($id)
    {
        Child::findOrFail($id)->delete();
        return response()->json(['message' => 'Data anak dihapus']);
    }
}
