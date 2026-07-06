<?php
// app/Http/Controllers/ChildController.php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChildController extends Controller
{
    // Daftar anak milik orang tua login / semua (admin)
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Child::with(['growthRecords' => fn($q) => $q->orderBy('age_months'), 'vaccines']);

        if ($user->role === 'orangtua') {
            $query->where('user_id', $user->id);
        } else {
            $query->with('user');
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'       => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender'     => 'required|in:male,female',
            'blood_type' => 'nullable|string|max:5',
        ]);
        if ($v->fails()) return response()->json(['message' => $v->errors()->first()], 422);

        $child = Child::create([
            'user_id'    => $request->user()->id,
            'name'       => $request->name,
            'birth_date' => $request->birth_date,
            'gender'     => $request->gender,
            'blood_type' => $request->blood_type,
        ]);

        // Auto-create default vaccines
        $defaults = [
            ['HB-0', 0], ['BCG', 1], ['Polio 1', 1],
            ['DPT-HB-Hib 1', 2], ['Polio 2', 2],
            ['DPT-HB-Hib 2', 3], ['Polio 3', 3],
            ['DPT-HB-Hib 3', 4], ['Polio 4', 4], ['IPV', 4],
            ['MR', 9], ['DPT-HB-Hib 4 (Booster)', 18], ['MR Booster', 18],
        ];
        foreach ($defaults as [$name, $age]) {
            Vaccine::create([
                'child_id'               => $child->id,
                'vaccine_name'           => $name,
                'recommended_age_months' => $age,
                'is_done'                => false,
            ]);
        }

        return response()->json($child->load(['growthRecords', 'vaccines']), 201);
    }

    public function show(Request $request, $id)
    {
        $user  = $request->user();
        $child = Child::with(['growthRecords' => fn($q) => $q->orderBy('age_months'), 'vaccines', 'user'])->findOrFail($id);

        if ($user->role === 'orangtua' && $child->user_id !== $user->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        return response()->json($child);
    }

    public function update(Request $request, $id)
    {
        $child = Child::findOrFail($id);
        if ($request->user()->role === 'orangtua' && $child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }
        $child->update($request->only(['name', 'birth_date', 'gender', 'blood_type']));
        return response()->json($child);
    }

    public function destroy(Request $request, $id)
    {
        $child = Child::findOrFail($id);
        if ($request->user()->role === 'orangtua' && $child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }
        $child->delete();
        return response()->json(['message' => 'Data anak dihapus']);
    }
}
