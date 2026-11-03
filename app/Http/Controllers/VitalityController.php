<?php

namespace App\Http\Controllers;

use App\Models\Chamber;
use App\Models\VitalityLog;
use Illuminate\Http\Request;

class VitalityController extends Controller
{
    /**
     * Display a listing of vitality logs.
     * GET /api/vitality/logs
     */
    public function index(Request $request)
    {
        $chamberId = $request->input('chamber_id', 1);
        $query = VitalityLog::where('chamber_id', $chamberId)->latest();

        if ($request->filled('result') && in_array($request->result, ['viable', 'non-viable'])) {
            $query->where('result', $request->result);
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        $logs = $query->paginate(15);

        // Calculate summary statistics
        $total = VitalityLog::where('chamber_id', $chamberId)->count();
        $viable = VitalityLog::where('chamber_id', $chamberId)->where('result', 'viable')->count();
        $nonViable = VitalityLog::where('chamber_id', $chamberId)->where('result', 'non-viable')->count();
        $viabilityRate = $total > 0 ? round(($viable / $total) * 100, 1) : 0;

        return response()->json([
            'success' => true,
            'logs' => $logs->items(),
            'pagination' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'total' => $logs->total(),
            ],
            'stats' => [
                'total' => $total,
                'viable' => $viable,
                'non_viable' => $nonViable,
                'viability_rate' => $viabilityRate,
            ],
        ]);
    }

    /**
     * Store a newly created vitality log in storage.
     * POST /api/vitality/logs
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'chamber_id' => 'required|exists:chambers,id',
            'batch_id' => 'required|string|max:50',
            'grid_position' => 'required|string|max:20', // e.g. "Row 4, Col 2" or "D2"
            'result' => 'required|in:viable,non-viable',
            'candling_day' => 'nullable|integer|between:1,35',
            'notes' => 'nullable|string|max:500',
        ]);

        $log = VitalityLog::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Embryo viability logged successfully.',
            'log' => $log,
        ], 201);
    }

    /**
     * Remove the specified vitality log from storage.
     * DELETE /api/vitality/logs/{id}
     */
    public function destroy($id)
    {
        $log = VitalityLog::findOrFail($id);
        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vitality log removed.',
        ]);
    }
}
