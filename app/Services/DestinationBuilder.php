<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Destination;
use App\Models\DestinationDish;
use App\Models\DestinationPlace;
use App\Models\Image;
use App\Models\Iterinary;
use App\Models\StayLocation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationBuilder
{
    public function addDestinationWithDetails(Iterinary $iterinary, array $payload): Destination
    {
        return DB::transaction(function () use ($iterinary, $payload) {
            $destination = $this->createDestination($payload);
            $iterinary->destinations()->attach($destination->id);
            $this->syncPlaces($destination, Arr::get($payload, 'places', []));
            $this->syncActivities($destination, Arr::get($payload, 'activities', []));
            $this->syncDishes($destination, Arr::get($payload, 'dishes', []));

            return $destination->load(['stay', 'places', 'activities', 'dishes', 'images']);
        });
    }

    public function updateDestinationDetails(Destination $destination, array $payload): Destination
    {
        return DB::transaction(function () use ($destination, $payload) {
            if (array_key_exists('stay', $payload)) {
                $this->syncStay($destination, $payload['stay']);
            }

            $updates = Arr::only($payload, ['name', 'description', 'primary_image']);
            if (isset($updates['name'])) {
                $updates['slug'] = $this->ensureUniqueSlug($updates['name'], $destination->id);
            }
            $destination->update(array_filter($updates, fn ($value) => $value !== null || array_key_exists($value, $updates)));

            if (array_key_exists('places', $payload)) {
                $this->syncPlaces($destination, $payload['places']);
            }

            if (array_key_exists('activities', $payload)) {
                $this->syncActivities($destination, $payload['activities']);
            }

            if (array_key_exists('dishes', $payload)) {
                $this->syncDishes($destination, $payload['dishes']);
            }

            return $destination->fresh(['stay', 'places', 'activities', 'dishes', 'images']);
        });
    }

    public function storeDestinationImage(
        Iterinary $iterinary,
        Destination $destination,
        UploadedFile $file,
        string $contextType,
        ?int $contextId = null,
        ?string $altText = null
    ): Image {
        $slug = $destination->slug ?: Str::slug($destination->name);
        $contextSegment = $this->normalizeContextType($contextType);
        $directory = "itineraries/{$iterinary->id}/{$slug}/{$contextSegment}";
        $filename = Str::random(16) . '.' . ($file->extension() ?: 'jpg');
        $storageDisk = Storage::disk('public');
        $rawImage = file_get_contents($file->getRealPath());
        [$width, $height] = getimagesizefromstring($rawImage) ?: [null, null];
        $encoded = $rawImage;
        $mimeType = $file->getMimeType() ?? 'image/jpeg';

        if (class_exists('Intervention\\Image\\ImageManagerStatic')) {
            $manager = 'Intervention\\Image\\ImageManagerStatic';
            $processed = $manager::make($rawImage)->resize(1200, null, fn ($constraint) => $constraint->aspectRatio());
            $processed->encode('webp', 85);
            $encoded = (string) $processed;
            $width = $processed->width();
            $height = $processed->height();
            $mimeType = 'image/webp';
        }

        $path = "{$directory}/{$filename}";
        $storageDisk->put($path, $encoded);
        $storedUrl = $storageDisk->url($path);

        return $destination->images()->create([
            'image_link' => $storedUrl,
            'file_size' => strlen($encoded),
            'file_type' => $mimeType,
            'destination_id' => $destination->id,
            'context_type' => $contextType,
            'context_id' => $contextId,
            'alt_text' => $altText,
            'storage_disk' => 'public',
            'width' => $width,
            'height' => $height,
        ]);
    }

    protected function createDestination(array $payload): Destination
    {
        $stay = $this->createStay(Arr::get($payload, 'stay', []));
        $slug = $this->ensureUniqueSlug($payload['name'] ?? 'destination');

        return Destination::create([
            'name' => $payload['name'] ?? 'Untitled',
            'lieu_logement' => $payload['lieu_logement'] ?? null,
            'image' => $payload['image'] ?? null,
            'primary_image' => $payload['primary_image'] ?? ($payload['image'] ?? null),
            'description' => $payload['description'] ?? null,
            'slug' => $slug,
            'stay_location_id' => $stay?->id,
        ]);
    }

    protected function createStay(array $stayPayload): ?StayLocation
    {
        if (empty($stayPayload)) {
            return null;
        }

        return StayLocation::create([
            'address' => Arr::get($stayPayload, 'address'),
            'establishment_name' => Arr::get($stayPayload, 'establishment_name'),
            'notes' => Arr::get($stayPayload, 'notes'),
            'check_in' => Arr::get($stayPayload, 'check_in'),
            'check_out' => Arr::get($stayPayload, 'check_out'),
        ]);
    }

    protected function syncStay(Destination $destination, ?array $stayPayload): void
    {
        if ($stayPayload === null) {
            optional($destination->stay)->delete();
            $destination->update(['stay_location_id' => null]);
            return;
        }

        $stay = $destination->stay;

        if ($stay) {
            $stay->update([ // update existing stay
                'address' => Arr::get($stayPayload, 'address'),
                'establishment_name' => Arr::get($stayPayload, 'establishment_name'),
                'notes' => Arr::get($stayPayload, 'notes'),
                'check_in' => Arr::get($stayPayload, 'check_in'),
                'check_out' => Arr::get($stayPayload, 'check_out'),
            ]);
        } else {
            $stay = $this->createStay($stayPayload);
            $destination->update(['stay_location_id' => $stay?->id]);
        }
    }

    protected function syncPlaces(Destination $destination, array $places): void
    {
        if (empty($places)) {
            $destination->places()->delete();
            return;
        }

        $destination->places()->delete();
        foreach ($places as $index => $placePayload) {
            DestinationPlace::create([
                'destination_id' => $destination->id,
                'name' => Arr::get($placePayload, 'name'),
                'description' => Arr::get($placePayload, 'description'),
                'notes' => Arr::get($placePayload, 'notes'),
                'priority_order' => $placePayload['priority_order'] ?? $index,
            ]);
        }
    }

    protected function syncActivities(Destination $destination, array $activities): void
    {
        if (empty($activities)) {
            $destination->activities()->delete();
            return;
        }

        $destination->activities()->delete();
        foreach ($activities as $activityPayload) {
            if (Arr::get($activityPayload, 'name')) {
                $activityPayload['nom'] = Arr::get($activityPayload, 'name');
            }

            Activity::create(array_merge(['destination_id' => $destination->id], Arr::only($activityPayload, [
                'nom',
                'description',
                'start_time',
                'end_time',
                'cost',
                'currency',
                'details',
            ])));
        }
    }

    protected function syncDishes(Destination $destination, array $dishes): void
    {
        if (empty($dishes)) {
            $destination->dishes()->delete();
            return;
        }

        $destination->dishes()->delete();
        foreach ($dishes as $dishPayload) {
            DestinationDish::create([
                'destination_id' => $destination->id,
                'name' => Arr::get($dishPayload, 'name'),
                'venue' => Arr::get($dishPayload, 'venue'),
                'description' => Arr::get($dishPayload, 'description'),
                'must_try' => Arr::get($dishPayload, 'must_try', false),
            ]);
        }
    }

    protected function ensureUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $query = Destination::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = Str::slug("{$slug}-" . Str::random(3));
            $query = Destination::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }

    protected function normalizeContextType(string $contextType): string
    {
        return match (strtolower($contextType)) {
            'place', 'places' => 'places',
            'activity', 'activities' => 'activities',
            'dish', 'dishes' => 'dishes',
            'stay', 'lodging' => 'stay',
            default => 'shared',
        };
    }
}
