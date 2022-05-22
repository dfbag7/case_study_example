<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class MatchTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    public function setUp() : void
    {
        parent::setUp();

        $this->seed();
    }

    /**
     * @return void
     */
    public function test_api_match_works()
    {
        $propertyId = 1;

        $response = $this
            ->withHeader('Accept', 'application/json')
            ->call('GET', route('api.match', ['propertyId' => $propertyId]));

        $response->assertSuccessful();
        $this->assertInstanceOf(JsonResponse::class, $response->baseResponse);

        $data = $response->baseResponse->getData(true);

        // do simple checks for the structure
        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('searchProfileId', $data[0]);
        $this->assertArrayHasKey('score', $data[0]);
        $this->assertArrayHasKey('strictMatchesCount', $data[0]);
        $this->assertArrayHasKey('looseMatchesCount', $data[0]);
    }
}
