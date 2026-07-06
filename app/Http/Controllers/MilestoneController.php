<?php
namespace App\Http\Controllers;
use App\Models\Milestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function index($childId) {
        return response()->json(Milestone::where('child_id', $childId)->get());
    }

    public function toggle(Request $request, $childId) {
        $month = $request->month;
        $m = Milestone::firstOrCreate(
            ['child_id' => $childId, 'month' => $month],
            ['is_achieved' => false]
        );
        $m->update([
            'is_achieved'   => !$m->is_achieved,
            'achieved_date' => !$m->is_achieved ? now()->toDateString() : null,
        ]);
        return response()->json($m);
    }
}