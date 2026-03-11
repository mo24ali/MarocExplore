<?php

namespace App\Http\Controllers;

use App\Models\Iterinary;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class IterinaryController extends Controller
{
    public function index(): JsonResponse
    {
        $itineraries = Iterinary::with(['destinations', 'user'])->get();

        return response()->json(['data' => $itineraries], 200);
    }

    public function show(Iterinary $iterinary): JsonResponse
    {
        $iterinary->load(['destinations', 'user']);

        return response()->json(['data' => $iterinary], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'duration' => 'required|string|max:100',
            'image' => 'nullable|url',
            'destination' => 'required|string|max:255',
            'destinations' => 'required|array|min:1',
            'destinations.*.name' => 'required|string|max:255',
            'destinations.*.lieu_logement' => 'required|string|max:255',
            'destinations.*.image' => 'required|url',
        ]);

        if (! $request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $destinations = array_map(function ($destination) {
            return [
                'name' => $destination['name'],
                'lieu_logement' => $destination['lieu_logement'],
                'image' => $destination['image'],
            ];
        }, $data['destinations']);

        $iterinary = DB::transaction(function () use ($data, $destinations, $request) {
            $created = Iterinary::create([
                'title' => $data['title'],
                'category' => $data['category'],
                'duration' => $data['duration'],
                'image' => $data['image'] ?? null,
                'destination' => $data['destination'],
                'user_id' => $request->user()->id,
            ]);

            foreach ($destinations as $destinationData) {
                $destination = Destination::create($destinationData);
                $created->destinations()->attach($destination->id);
            }

            return $created;
        });

        return response()->json(['data' => $iterinary->load('destinations')], 201);
    }

    public function update(Request $request, Iterinary $iterinary): JsonResponse
    {
        if (! $request->user() || $request->user()->id !== $iterinary->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:100',
            'duration' => 'sometimes|required|string|max:100',
            'image' => 'nullable|url',
        ]);

        $iterinary->update($data);

        return response()->json(['data' => $iterinary->fresh('destinations')], 200);
    }

    public function destroy(Request $request, Iterinary $iterinary): JsonResponse
    {
        if (! $request->user() || $request->user()->id !== $iterinary->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $iterinary->delete();

        return response()->json(['message' => 'Itinéraire supprimé'], 200);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->query('q');
        $category = $request->query('category');
        $duration = $request->query('duration');

        $builder = Iterinary::with('destinations');

        if ($query) {
            $builder->where('title', 'like', "%{$query}%");
        }

        if ($category) {
            $builder->where('category', $category);
        }

        if ($duration) {
            $builder->where('duration', $duration);
        }

        return response()->json(['data' => $builder->get()], 200);
    }

    public function popular(): JsonResponse
    {
        $itineraries = Iterinary::with('destinations')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return response()->json(['data' => $itineraries], 200);
    }

    public function addDestination(Request $request, Iterinary $iterinary): JsonResponse
    {
        if (! $request->user() || $request->user()->id !== $iterinary->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'lieu_logement' => 'required|string|max:255',
            'image' => 'nullable|url',
            'places' => 'nullable|array',
            'activities' => 'nullable|array',
        ]);

        $destination = Destination::create($data);
        $iterinary->destinations()->attach($destination->id);

        return response()->json(['data' => $destination], 201);
    }

    public function updateDestination(Request $request, Iterinary $iterinary, Destination $destination): JsonResponse
    {
        if (! $request->user() || $request->user()->id !== $iterinary->user_id || ! $iterinary->destinations()->where('destinations.id', $destination->id)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'lieu_logement' => 'sometimes|required|string|max:255',
            'image' => 'nullable|url',
            'places' => 'nullable|array',
            'activities' => 'nullable|array',
        ]);

        $destination->update($data);

        return response()->json(['data' => $destination], 200);
    }

    public function removeDestination(Request $request, Iterinary $iterinary, Destination $destination): JsonResponse
    {
        if (! $request->user() || $request->user()->id !== $iterinary->user_id || ! $iterinary->destinations()->where('destinations.id', $destination->id)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($iterinary->destinations()->count() <= 2) {
            return response()->json(['message' => 'Un itinéraire doit contenir au moins deux destinations.'], 400);
        }

        $iterinary->destinations()->detach($destination->id);
        $destination->delete();

        return response()->json(['message' => 'Destination supprimée'], 200);
    }

    public function stats(): JsonResponse
    {
        $byCategory = Iterinary::select('category')
            ->selectRaw('count(*) as total')
            ->groupBy('category')
            ->get();

        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            $usersMonthly = DB::table('users')
                ->selectRaw("to_char(created_at, 'YYYY-MM') as month")
                ->selectRaw('count(*) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } else {
            $usersMonthly = DB::table('users')
                ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as month")
                ->selectRaw('count(*) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        return response()->json([
            'itineraries_by_category' => $byCategory,
            'users_registered_per_month' => $usersMonthly,
        ], 200);
    }
}

