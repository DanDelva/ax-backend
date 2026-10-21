<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Resources\ScheduleResource;

class ScheduleController extends Controller
{
    public function index()
    {
        return ScheduleResource::collection(Schedule::with('subject')->get());
    }

    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'day_of_week' => 'required|string|max:20',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'classroom' => 'required|string|max:50'
        ]);

        $schedule = Schedule::create($validated);
        return new ScheduleResource($schedule->load('subject'));
    }

    public function show(Schedule $schedule)
    {
        return new ScheduleResource($schedule->load('subject'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');

        $validated = $request->validate([
            'subject_id' => 'sometimes|required|exists:subjects,id',
            'day_of_week' => 'sometimes|required|string|max:20',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
            'classroom' => 'sometimes|required|string|max:50'
        ]);

        $schedule->update($validated);
        return new ScheduleResource($schedule->load('subject'));
    }

    public function destroy(Request $request, Schedule $schedule)
    {
        if (!$request->user()->isAdmin()) abort(403, 'No autorizado');

        $schedule->delete();
        return response()->json(['message' => 'Eliminado exitosamente']);
    }
}
