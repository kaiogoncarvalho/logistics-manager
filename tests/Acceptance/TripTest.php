<?php

namespace Tests\Acceptance;

use Tests\AcceptanceTestCase;
use App\Models\Trip;
use Laravel\Passport\Passport;
use App\Models\User;
use Illuminate\Http\Response;
use function GuzzleHttp\json_decode;
use Carbon\Carbon;
use App\Enums\Scope;

class TripTest extends AcceptanceTestCase
{
    public function testGetById()
    {
        $trip = factory(Trip::class)->create();
        $user = factory(User::class)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'GET',
            '/v1/trip/' . $trip->id,
            )->assertStatus(Response::HTTP_OK);
        
        $content = json_decode($response->getContent(), true);
        
        $tripData = $trip->toArray();
        $tripData['origin'] = [
            'type' => 'Point',
            'coordinates' => [
                $tripData['origin']->getLng(),
                $tripData['origin']->getLat(),
            ]
        ];
        $tripData['destiny'] = [
            'type' => 'Point',
            'coordinates' => [
                $tripData['destiny']->getLng(),
                $tripData['destiny']->getLat(),
            ]
        ];
        
        $this->assertEquals($tripData, $content);
    }
    
    public function testGetByIdWhenDontExist()
    {
        $user = factory(User::class)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $this->json(
            'GET',
            '/v1/trip/1',
            )->assertStatus(Response::HTTP_NOT_FOUND);
    }
    
    public function testGetByIdWhenPathIdIsInvalid()
    {
        $user = factory(User::class)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $this->json(
            'GET',
            '/v1/trip/integer',
            )->assertStatus(Response::HTTP_NOT_FOUND);
    }
    
    public function testGetAll()
    {
        $user = factory(User::class)->create();
        factory(Trip::class)->times(10)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'GET',
            '/v1/trips?page=2&perPage=5'
        )->assertStatus(Response::HTTP_OK);
        
        $content = json_decode($response->getContent(), true);
        $this->assertCount(5, $content['data']);
        $this->assertSame(2, $content['current_page']);
        $this->assertSame(6, $content['from']);
        $this->assertSame(10, $content['to']);
        $this->assertSame(10, $content['total']);
    }
    
    public function testCreate()
    {
        $user = factory(User::class)->create();
        $trip = factory(Trip::class)->raw();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'POST',
            '/v1/trip',
            $trip
        )->assertStatus(Response::HTTP_CREATED);
    
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('updated_at', $content);
        $this->assertArrayHasKey('created_at', $content);
    
        $this->assertSame($trip['driver_id'], $content['driver_id']);
        $this->assertSame($trip['truck_id'], $content['truck_id']);
        $this->assertSame($trip['loaded'], $content['loaded']);
        $this->assertSame($trip['trip_date'], $content['trip_date']);
        $this->assertEquals($trip['origin']['lon'], $content['origin']['coordinates'][0]);
        $this->assertEquals($trip['origin']['lat'], $content['origin']['coordinates'][1]);
        $this->assertEquals($trip['destiny']['lon'], $content['destiny']['coordinates'][0]);
        $this->assertEquals($trip['destiny']['lat'], $content['destiny']['coordinates'][1]);
    
        unset($trip['origin']);
        unset($trip['destiny']);
    
        $this->assertDatabaseHas(
            'trips',
            $trip
        );
    }
    
    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $trip = factory(Trip::class)->create();
        $tripUpdate = factory(Trip::class)->raw();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'PATCH',
            '/v1/trip/' . $trip->id,
            $tripUpdate
        )->assertStatus(Response::HTTP_OK);
        
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('updated_at', $content);
        $this->assertArrayHasKey('created_at', $content);
        
        $this->assertSame($tripUpdate['driver_id'], $content['driver_id']);
        $this->assertSame($tripUpdate['truck_id'], $content['truck_id']);
        $this->assertSame($tripUpdate['loaded'], $content['loaded']);
        $this->assertSame($tripUpdate['trip_date'], $content['trip_date']);
        $this->assertEquals($tripUpdate['origin']['lon'], $content['origin']['coordinates'][0]);
        $this->assertEquals($tripUpdate['origin']['lat'], $content['origin']['coordinates'][1]);
        $this->assertEquals($tripUpdate['destiny']['lon'], $content['destiny']['coordinates'][0]);
        $this->assertEquals($tripUpdate['destiny']['lat'], $content['destiny']['coordinates'][1]);
        
        unset($tripUpdate['origin']);
        unset($tripUpdate['destiny']);
        
        $this->assertDatabaseHas(
            'trips',
            $tripUpdate
        );
    }
    
    public function testDelete()
    {
        $user = factory(User::class)->create();
        $trip = factory(Trip::class)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $this->json(
            'DELETE',
            '/v1/trip/' . $trip->id
        )->assertStatus(Response::HTTP_NO_CONTENT);
        
        $this->assertDatabaseMissing(
            'trips',
            $trip->toArray() + ['deleted_at' => null]
        );
    }
    
    public function testGetDeleteById()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::ADMIN]]);
        $trip = factory(Trip::class)->create(
            [
                'deleted_at' => Carbon::now()
            ]
        );
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'GET',
            '/v1/trip/deleted/' . $trip->id
        )->assertStatus(Response::HTTP_OK);
        
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('deleted_at', $content);
        $this->assertArrayHasKey('created_at', $content);
        
        $this->assertSame($trip->name, $content['name']);
        $this->assertSame($trip->cpf, $content['cpf']);
        $this->assertSame($trip->gender, $content['gender']);
        $this->assertSame($trip->birth_date, $content['birth_date']);
        $this->assertSame($trip->own_truck, $content['own_truck']);
        $this->assertSame($trip->cnh, $content['cnh']);
    }
    
    public function testGetDeleteByIdWithInvalidScope()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::USER]]);
        $trip = factory(Trip::class)->create(
            [
                'deleted_at' => Carbon::now()
            ]
        );
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $this->json(
            'GET',
            '/v1/trip/deleted/' . $trip->id
        )->assertStatus(Response::HTTP_UNAUTHORIZED);
        
       
    }
    
    public function testGetAllDeleted()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::ADMIN]]);
        factory(Trip::class)->times(10)->create(
            [
                'deleted_at' => Carbon::now()
            ]
        );
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'GET',
            '/v1/trips/deleted?page=2&perPage=5'
        )->assertStatus(Response::HTTP_OK);
        
        $content = json_decode($response->getContent(), true);
        $this->assertCount(5, $content['data']);
        $this->assertSame(2, $content['current_page']);
        $this->assertSame(6, $content['from']);
        $this->assertSame(10, $content['to']);
        $this->assertSame(10, $content['total']);
    }
    
    public function testGetAllDeletedWithInvalidScope()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::USER]]);
        factory(Trip::class)->times(10)->create(
            [
                'deleted_at' => Carbon::now()
            ]
        );
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $this->json(
            'GET',
            '/v1/trips/deleted?page=2&perPage=5'
        )->assertStatus(Response::HTTP_UNAUTHORIZED);
        
        
    }
    
    public function testRecoverById()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::ADMIN]]);
        $trip = factory(Trip::class)->create(
            [
                'deleted_at' => Carbon::now()
            ]
        );
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $this->json(
            'PATCH',
            '/v1/trip/recover/' . $trip->id
        )->assertStatus(Response::HTTP_OK);
    
        $tripData = $trip->toArray();
        
        unset($tripData['origin']);
        unset($tripData['destiny']);
        
        $this->assertDatabaseHas(
            'trips',
            $tripData + ['deleted_at' => null]
        );
    }
    
    public function testRecoverByIdWithInvalidScope()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::USER]]);
        $trip = factory(Trip::class)->create(
            [
                'deleted_at' => Carbon::now()
            ]
        );
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $this->json(
            'PATCH',
            '/v1/trip/recover/' . $trip->id
        )->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
    
}
