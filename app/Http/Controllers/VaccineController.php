<?php
// app/Http/Controllers/VaccineController.php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Vaccine;
use Illuminate\Http\Request;

class VaccineController extends Controller
{
    public function index($childId)
    {
        return response()->json(Vaccine::where('child_id', $childId)->orderBy('recommended_age_months')->get());
    }

    public function update(Request $request, $childId, $vaccineId)
    {
        $child   = Child::findOrFail($childId);
        $vaccine = Vaccine::where('child_id', $childId)->findOrFail($vaccineId);

        if ($request->user()->role === 'orangtua' && $child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $vaccine->update([
            'is_done'    => $request->is_done,
            'given_date' => $request->given_date,
            'location'   => $request->location,
            'notes'      => $request->notes,
        ]);

        return response()->json($vaccine);
    }
}
