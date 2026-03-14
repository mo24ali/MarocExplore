<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Place;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /**
     * List all places for a destination.
     */
    public function index(Destination $destination): JsonResponse
    {
        return response()->json([
            'data' => $destination->places()->get(),
        ], 200);
    }

    /**
     * Add a new place to a destination.
     */
    public function store(Request $request, Destination $destination): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $place = $destination->places()->create($data);

        return response()->json(['data' => $place], 201);
    }

    /**
     * Show a single place.
     */
    public function show(Destination $destination, Place $place): JsonResponse
    {
        $this->ensureBelongsToDestination($destination, $place);

        return response()->json(['data' => $place], 200);
    }

    /**
     * Update a place.
     */
    public function update(Request $request, Destination $destination, Place $place): JsonResponse
    {
        $this->ensureBelongsToDestination($destination, $place);

        $data = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $place->update($data);

        return response()->json(['data' => $place], 200);
    }

    /**
     * Delete a place.
     */
    public function destroy(Destination $destination, Place $place): JsonResponse
    {
        $this->ensureBelongsToDestination($destination, $place);

        $place->delete();

        return response()->json(['message' => 'Place supprimée'], 200);
    }

    private function ensureBelongsToDestination(Destination $destination, Place $place): void
    {
        if ($place->destination_id !== $destination->id) {
            abort(404, 'Place introuvable pour cette destination.');
        }
    }
}
