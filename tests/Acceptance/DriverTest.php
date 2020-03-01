<?php

namespace Tests\Acceptance;

use Tests\AcceptanceTestCase;
use App\Models\Driver;
use Laravel\Passport\Passport;
use App\Models\User;
use Illuminate\Http\Response;
use function GuzzleHttp\json_decode;
use Carbon\Carbon;
use App\Enums\Scope;

class DriverTest extends AcceptanceTestCase
{
    public function testGetById()
    {
        $driver = factory(Driver::class)->create();
        $user = factory(User::class)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'GET',
            '/v1/driver/' . $driver->id,
            )->assertStatus(Response::HTTP_OK);
        
        $content = json_decode($response->getContent(), true);
        
        $this->assertEquals($driver->toArray(), $content);
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
            '/v1/driver/1',
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
            '/v1/driver/integer',
            )->assertStatus(Response::HTTP_NOT_FOUND);
    }
    
    public function testGetAll()
    {
        $user = factory(User::class)->create();
        factory(Driver::class)->times(10)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'GET',
            '/v1/drivers?page=2&perPage=5'
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
        $driver = factory(Driver::class)->make();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'POST',
            '/v1/driver',
            $driver->toArray()
        )->assertStatus(Response::HTTP_CREATED);
        
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('updated_at', $content);
        $this->assertArrayHasKey('created_at', $content);
        
        $this->assertSame($driver->name, $content['name']);
        $this->assertSame($driver->cpf, $content['cpf']);
        $this->assertSame($driver->gender, $content['gender']);
        $this->assertSame($driver->birth_date, $content['birth_date']);
        $this->assertSame($driver->own_truck, $content['own_truck']);
        $this->assertSame($driver->cnh, $content['cnh']);
        
        $this->assertDatabaseHas(
            'drivers',
            $driver->toArray()
        );
    }
    
    public function testCreateWithDuplicateCpf()
    {
        $user = factory(User::class)->create();
        $driver = factory(Driver::class)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'POST',
            '/v1/driver',
            $driver->toArray()
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('cpf', $content['errors']);
    }
    
    public function testUpdateWithDuplicateCpf()
    {
        $user = factory(User::class)->create();
        $userDuplicate = factory(User::class)->create();
        $driver = factory(Driver::class)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'PATCH',
            '/v1/driver/' . $driver->id,
            [
                'cpf' => $userDuplicate->cpf
            ]
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('cpf', $content['errors']);
    }
    
    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $driver = factory(Driver::class)->create();
        $driverUpdate = factory(Driver::class)->make();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $response = $this->json(
            'PATCH',
            '/v1/driver/' . $driver->id,
            $driverUpdate->toArray()
        )->assertStatus(Response::HTTP_OK);
        
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('updated_at', $content);
        $this->assertArrayHasKey('created_at', $content);
        
        $this->assertNotSame($content['updated_at'], $content['created_at']);
        
        $this->assertSame($driverUpdate->name, $content['name']);
        $this->assertSame($driverUpdate->cpf, $content['cpf']);
        $this->assertSame($driverUpdate->gender, $content['gender']);
        $this->assertSame($driverUpdate->birth_date, $content['birth_date']);
        $this->assertSame($driverUpdate->own_truck, $content['own_truck']);
        $this->assertSame($driverUpdate->cnh, $content['cnh']);
        
        $this->assertDatabaseHas(
            'drivers',
            $driverUpdate->toArray()
        );
    }
    
    public function testDelete()
    {
        $user = factory(User::class)->create();
        $driver = factory(Driver::class)->create();
        
        Passport::actingAs(
            $user,
            $user->scopes
        );
        
        $this->json(
            'DELETE',
            '/v1/driver/' . $driver->id
        )->assertStatus(Response::HTTP_NO_CONTENT);
        
        $this->assertDatabaseMissing(
            'drivers',
            $driver->toArray() + ['deleted_at' => null]
        );
    }
    
    public function testGetDeleteById()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::ADMIN]]);
        $driver = factory(Driver::class)->create(
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
            '/v1/driver/deleted/' . $driver->id
        )->assertStatus(Response::HTTP_OK);
        
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('deleted_at', $content);
        $this->assertArrayHasKey('created_at', $content);
        
        $this->assertSame($driver->name, $content['name']);
        $this->assertSame($driver->cpf, $content['cpf']);
        $this->assertSame($driver->gender, $content['gender']);
        $this->assertSame($driver->birth_date, $content['birth_date']);
        $this->assertSame($driver->own_truck, $content['own_truck']);
        $this->assertSame($driver->cnh, $content['cnh']);
    }
    
    public function testGetDeleteByIdWithInvalidScope()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::USER]]);
        $driver = factory(Driver::class)->create(
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
            '/v1/driver/deleted/' . $driver->id
        )->assertStatus(Response::HTTP_UNAUTHORIZED);
        
       
    }
    
    public function testGetAllDeleted()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::ADMIN]]);
        factory(Driver::class)->times(10)->create(
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
            '/v1/drivers/deleted?page=2&perPage=5'
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
        factory(Driver::class)->times(10)->create(
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
            '/v1/drivers/deleted?page=2&perPage=5'
        )->assertStatus(Response::HTTP_UNAUTHORIZED);
        
        
    }
    
    public function testRecoverById()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::ADMIN]]);
        $driver = factory(Driver::class)->create(
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
            '/v1/driver/recover/' . $driver->id
        )->assertStatus(Response::HTTP_OK);
        
        $this->assertDatabaseHas(
            'drivers',
            $driver->toArray() + ['deleted_at' => null]
        );
    }
    
    public function testRecoverByIdWithInvalidScope()
    {
        $user = factory(User::class)
            ->create(['scopes' => [Scope::USER]]);
        $driver = factory(Driver::class)->create(
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
            '/v1/driver/recover/' . $driver->id
        )->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
    
}
