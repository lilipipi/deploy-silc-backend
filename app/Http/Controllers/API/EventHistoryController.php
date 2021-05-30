<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\AssetImageCollection;
use App\Models\AssetImage;
use App\Models\EventHistory;
use Exception;


class EventHistoryController extends BaseController
{
    public function showAll(Request $request)
    {
        return EventHistory::get();
    }

    public function store(Request $request)
    {
        return EventHistory::create($request->all());
    }

    public function update(Request $request, $eventId)
    {
        try {
            $event = EventHistory::findOrFail($eventId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Event not found.'
            ], 403);
        }

        $event->update($request->all());

        return response()->json(['message'=>'Event updated successfully.']);
    }

    public function delete(Request $request, $eventId)
    {
        try {
            $event = EventHistory::findOfFail($eventId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Event not found.'
            ], 403);
        }

        $event->delete();

        return response()->json(['message'=>'Event deleted successfully.']);
    }
}