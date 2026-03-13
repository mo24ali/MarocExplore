<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
class ItineraryTest extends TestCase 
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);


    }


    public function test_user_can_create_itinerary(){
        $user = User::factory()->create();
         $data = [
        "title" => "Atlas Adventure",
        "category" => "mountain",
        "duration" => "3 days",
        "destination" => "Atlas",
        "destinations" => [
            [
                "name" => "Imlil",
                "lodging_place" => "Riad Atlas",
                "activities" => ["Hiking", "Tea"],
            ],
            [
                "name" => "Oukaimeden",
                "lodging_place" => "Mountain Lodge",
                "activities" => ["Skiing"]
            ]
        ]
    ];
    $response = $this->actingAs($user)
        ->postJson('api/v1/itineraries', $data);
    $response->assertStatus(201);
    $this->actingAs($user)
        ->getJson('api/v1/itineraries')
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'Atlas Adventure']);
    }
    public function test_unauthenticated_user_cannot_create_iterinary()
    {
        $response = $this->postJson('/api/v1/itineraries', [
            'title' => 'Titre Volé',
        ]);

        $response->assertStatus(401); // Doit échouer car pas de token
    }

    public function test_authenticated_user_can_create_iterinary_with_destinations()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $payload = [
            'title' => 'Trip au Sahara',
            'category' => 'Aventure',
            'duration' => '3 jours',
            'image' => 'https://example.com/desert.jpg',
            'destinations' => [
                [
                    'name' => 'Merzouga',
                    'lieu_logement' => 'Bivouac',
                    'places' => ['Dunes'],
                    'activities' => ['Camel ride']
                ],
                [
                    'name' => 'Rissani',
                    'lieu_logement' => 'Maison hotes',
                    'places' => ['Souk'],
                    'activities' => ['Shopping']
                ]
            ]
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/v1/itineraries', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('iterinaries', ['title' => 'Trip au Sahara']);
        $this->assertDatabaseCount('destinations', 2);
    }
}
