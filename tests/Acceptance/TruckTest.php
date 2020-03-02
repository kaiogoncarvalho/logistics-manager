<?php

namespace Tests\Acceptance;

use Tests\AcceptanceTestCase;
use App\Models\Truck;
use Laravel\Passport\Passport;
use App\Models\User;
use Illuminate\Http\Response;
use function GuzzleHttp\json_decode;
use Carbon\Carbon;
use App\Enums\Scope;

class TruckTest extends AcceptanceTestCase
{
    public function testGetById()
    {
        $truck = factory(Truck::class)->create();
        $user = factory(User::class)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'GET',
            '/v1/truck/' . $truck->id,
            )->assertStatus(Response::HTTP_OK);
        
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals($truck->toArray(), $content);
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
            '/v1/truck/1',
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
            '/v1/truck/float',
            )->assertStatus(Response::HTTP_NOT_FOUND);
    }
    
    public function testGetAll()
    {
        $user = factory(User::class)->create();
        factory(Truck::class)->times(30)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'GET',
            '/v1/trucks?page=3&per_page=5'
        )->assertStatus(Response::HTTP_OK);
        
        $content = json_decode($response->getContent(), true);
        $this->assertCount(5, $content['data']);
        $this->assertSame(3, $content['current_page']);
        $this->assertSame(11, $content['from']);
        $this->assertSame(15, $content['to']);
        $this->assertSame(30, $content['total']);
    }
    
    public function testCreate()
    {
        $user = factory(User::class)->create();
        $truck = factory(Truck::class)->make();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'POST',
            '/v1/truck',
            $truck->toArray()
        )->assertStatus(Response::HTTP_CREATED);
        
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('updated_at', $content);
        $this->assertArrayHasKey('created_at', $content);
        
        $this->assertSame($truck->name, $content['name']);
        
        $this->assertDatabaseHas(
            'trucks',
            $truck->toArray()
        );
    }
    
    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $truck = factory(Truck::class)->create();
        $truckUpdate = factory(Truck::class)->make();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'PATCH',
            '/v1/truck/' . $truck->id,
            $truckUpdate->toArray()
        )->assertStatus(Response::HTTP_OK);
        
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('updated_at', $content);
        $this->assertArrayHasKey('created_at', $content);
        
        $this->assertSame($truckUpdate->name, $content['name']);
        
        $this->assertDatabaseHas(
            'trucks',
            $truckUpdate->toArray()
        );
    }
    
    public function testDelete()
    {
        $user = factory(User::class)->create();
        $truck = factory(Truck::class)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $this->json(
            'DELETE',
            '/v1/truck/' . $truck->id
        )->assertStatus(Response::HTTP_NO_CONTENT);
        
        $this->assertDatabaseMissing(
            'trucks',
            $truck->toArray() + ['deleted_at' => null]
        );
    }
    
    public function testGetDeleteById()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::ADMIN]]);
        $truck = factory(Truck::class)->create(
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
            '/v1/truck/deleted/' . $truck->id
        )->assertStatus(Response::HTTP_OK);
        
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('deleted_at', $content);
        $this->assertArrayHasKey('created_at', $content);
        
        $this->assertSame($truck->name, $content['name']);
    }
    
    public function testGetDeleteByIdWithInvalidScope()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::USER]]);
        $truck = factory(Truck::class)->create(
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
            '/v1/truck/deleted/' . $truck->id
        )->assertStatus(Response::HTTP_UNAUTHORIZED);
        
       
    }
    
    public function testGetAllDeleted()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::ADMIN]]);
        factory(Truck::class)->times(15)->create(
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
            '/v1/trucks/deleted?page=2&per_page=5'
        )->assertStatus(Response::HTTP_OK);
        
        $content = json_decode($response->getContent(), true);
        $this->assertCount(5, $content['data']);
        $this->assertSame(2, $content['current_page']);
        $this->assertSame(6, $content['from']);
        $this->assertSame(10, $content['to']);
        $this->assertSame(15, $content['total']);
    }
    
    public function testGetAllDeletedWithInvalidScope()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::USER]]);
        factory(Truck::class)->times(10)->create(
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
            '/v1/trucks/deleted?page=2&perPage=5'
        )->assertStatus(Response::HTTP_UNAUTHORIZED);
        
        
    }
    
    public function testRecoverById()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::ADMIN]]);
        $truck = factory(Truck::class)->create(
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
            '/v1/truck/recover/' . $truck->id
        )->assertStatus(Response::HTTP_OK);
        
        $truckData = $truck->toArray();
        
        unset($truckData['updated_at']);
        
        $this->assertDatabaseHas(
            'trucks',
            $truckData + ['deleted_at' => null]
        );
    }
    
    public function testRecoverByIdWithInvalidScope()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::USER]]);
        $truck = factory(Truck::class)->create(
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
            '/v1/truck/recover/' . $truck->id
        )->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
    
}
