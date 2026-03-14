<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Dish;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DishController extends Controller
{
    /**
     * List all dishes for a destination.
     */
    public function index(Destination $destination): JsonResponse
    {
        return response()->json([
            'data' => $destination->dishes()->get(),
        ], 200);
    }

    /**
     * Add a new dish to a destination.
     */
    public function store(Request $request, Destination $destination): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'restaurant'  => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $dish = $destination->dishes()->create($data);

        return response()->json(['data' => $dish], 201);
    }

    /**
     * Show a single dish.
     */
    public function show(Destination $destination, Dish $dish): JsonResponse
    {
        $this->ensureBelongsToDestination($destination, $dish);

        return response()->json(['data' => $dish], 200);
    }

    /**
     * Update a dish.
     */
    public function update(Request $request, Destination $destination, Dish $dish): JsonResponse
    {
        $this->ensureBelongsToDestination($destination, $dish);

        $data = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'restaurant'  => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $dish->update($data);

        return response()->json(['data' => $dish], 200);
    }

    /**
     * Delete a dish.
     */
    public function destroy(Destination $destination, Dish $dish): JsonResponse
    {
        $this->ensureBelongsToDestination($destination, $dish);

        $dish->delete();

        return response()->json(['message' => 'Plat supprimé'], 200);
    }

    private function ensureBelongsToDestination(Destination $destination, Dish $dish): void
    {
        if ($dish->destination_id !== $destination->id) {
            abort(404, 'Plat introuvable pour cette destination.');
        }
    }
}
