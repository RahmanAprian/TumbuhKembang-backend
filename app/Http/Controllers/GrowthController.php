<?php
// app/Http/Controllers/GrowthController.php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\GrowthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GrowthController extends Controller
{
    public function index($childId)
    {
        $records = GrowthRecord::where('child_id', $childId)->orderBy('age_months')->get();
        return response()->json($records);
    }

    public function store(Request $request, $childId)
    {
        $child = Child::findOrFail($childId);

        if ($request->user()->role === 'orangtua' && $child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $v = Validator::make($request->all(), [
            'weight'             => 'required|numeric|min:0.5|max:200',
            'height'             => 'required|numeric|min:20|max:250',
            'head_circumference' => 'nullable|numeric',
            'age_months'         => 'required|integer|min:0|max:216',
            'recorded_at'        => 'required|date',
            'notes'              => 'nullable|string',
        ]);
        if ($v->fails()) return response()->json(['message' => $v->errors()->first()], 422);

        // Auto hitung status gizi (BB/U sederhana)
        $status = $this->calcStatus($request->weight, $child->gender, $request->age_months);

        $record = GrowthRecord::create([
            'child_id'           => $childId,
            'weight'             => $request->weight,
            'height'             => $request->height,
            'head_circumference' => $request->head_circumference,
            'age_months'         => $request->age_months,
            'recorded_at'        => $request->recorded_at,
            'nutritional_status' => $status,
            'notes'              => $request->notes,
        ]);

        return response()->json($record, 201);
    }

    public function destroy(Request $request, $childId, $recordId)
    {
        $record = GrowthRecord::where('child_id', $childId)->findOrFail($recordId);
        $child  = Child::findOrFail($childId);

        if ($request->user()->role === 'orangtua' && $child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan'], 403);
        }

        $record->delete();
        return response()->json(['message' => 'Rekaman dihapus']);
    }

    // Ref WHO BB/U (simplified)
    private function calcStatus($weight, $gender, $ageMonths)
    {
        $whoRef = [
            'male'   => [0=>3.3,1=>4.5,2=>5.6,3=>6.4,4=>7.0,5=>7.5,6=>7.9,7=>8.3,8=>8.6,9=>8.9,10=>9.2,11=>9.4,12=>9.6,18=>10.9,24=>12.2,36=>14.3],
            'female' => [0=>3.2,1=>4.2,2=>5.1,3=>5.8,4=>6.4,5=>6.9,6=>7.3,7=>7.6,8=>7.9,9=>8.2,10=>8.5,11=>8.7,12=>8.9,18=>10.2,24=>11.5,36=>13.9],
        ];
        $tbl  = $whoRef[$gender] ?? $whoRef['male'];
        $keys = array_keys($tbl);
        sort($keys);
        $ref  = end($tbl);
        foreach ($keys as $i => $k) {
            if ($ageMonths <= $k) { $ref = $tbl[$k]; break; }
            if (isset($keys[$i+1]) && $ageMonths < $keys[$i+1]) {
                $r   = ($ageMonths - $k) / ($keys[$i+1] - $k);
                $ref = $tbl[$k] + $r * ($tbl[$keys[$i+1]] - $tbl[$k]);
                break;
            }
        }
        $pct = (($weight - $ref) / $ref) * 100;
        if ($pct < -30) return 'gizi_buruk';
        if ($pct < -15) return 'gizi_kurang';
        if ($pct > 30)  return 'obesitas';
        if ($pct > 15)  return 'gizi_lebih';
        return 'normal';
    }
}
