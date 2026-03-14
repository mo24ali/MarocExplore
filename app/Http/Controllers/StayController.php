<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Stay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StayController extends Controller
{
    /**
     * List all stays for a destination.
     */
    public function index(Destination $destination): JsonResponse
    {
        return response()->json([
            'data' => $destination->stays()->get(),
        ], 200);
    }

    /**
     * Add a new stay to a destination.
     */
    public function store(Request $request, Destination $destination): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'address'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $stay = $destination->stays()->create($data);

        return response()->json(['data' => $stay], 201);
    }

    /**
     * Show a single stay.
     */
    public function show(Destination $destination, Stay $stay): JsonResponse
    {
        $this->ensureBelongsToDestination($destination, $stay);

        return response()->json(['data' => $stay], 200);
    }

    /**
     * Update a stay.
     */
    public function update(Request $request, Destination $destination, Stay $stay): JsonResponse
    {
        $this->ensureBelongsToDestination($destination, $stay);

        $data = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'address'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $stay->update($data);

        return response()->json(['data' => $stay], 200);
    }

    /**
     * Delete a stay.
     */
    public function destroy(Destination $destination, Stay $stay): JsonResponse
    {
        $this->ensureBelongsToDestination($destination, $stay);

        $stay->delete();

        return response()->json(['message' => 'Logement supprimé'], 200);
    }

    private function ensureBelongsToDestination(Destination $destination, Stay $stay): void
    {
        if ($stay->destination_id !== $destination->id) {
            abort(404, 'Logement introuvable pour cette destination.');
        }
    }
}
