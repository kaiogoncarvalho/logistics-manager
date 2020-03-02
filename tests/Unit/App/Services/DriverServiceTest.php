<?php

namespace Tests\Unit\App\Services;

use App\Models\Driver;
use Tests\TestCase;
use App\Services\DriverService;
use Carbon\Carbon;
use App\Enums\Gender;
use App\Enums\CNH;
use Illuminate\Pagination\LengthAwarePaginator;

class DriverServiceTest extends TestCase
{
    
    public function testDelete()
    {
        $id = 1;
    
        $driverMock = $this->mock(Driver::class);
        $driverMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with(
                $id
            )->andReturnSelf();
    
        $driverMock
            ->shouldReceive('delete')
            ->once();
   
    
        $this->app->instance(Driver::class, $driverMock);
        $this->app->make(DriverService::class)->delete($id);
    }
    
    public function testGetDeleted()
    {
        $driverMock = $this->mock(Driver::class);
        $driverMock
            ->shouldReceive('getFilters')
            ->once()
            ->andReturn([]);
        $driverMock
            ->shouldReceive('onlyTrashed')
            ->once()
            ->andReturnSelf();
    
        $this->app->instance(Driver::class, $driverMock);
        $this->app->make(DriverService::class)->getDeleted();
    }
    
    public function testGetById()
    {
        $id = 1;
    
        $driverMock = $this->mock(Driver::class);
        $driverMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with(
                $id
            )->andReturnSelf();
    
        $this->app->instance(Driver::class, $driverMock);
        $this->app->make(DriverService::class)->getById($id);
    }
    
    public function testUpdate()
    {
        $id = 1;
        
        $fields = [
            'name'      => 'Name',
            'cpf'       => '11122233344',
            'gender'    => Gender::MALE,
            'own_truck' => true,
            'cnh'       => CNH::E,
            'birth_date' => Carbon::now()
        ];
        
        $driverMock = $this->mock(Driver::class);
        
        $driverMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with(
                $id
            )->andReturnSelf();
    
        $driverMock
            ->shouldReceive('save')
            ->once();
            
        $driverMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('name', $fields['name']);
    
        $driverMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('cpf', $fields['cpf']);
            
        $driverMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('birth_date', $fields['birth_date']);
    
        $driverMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('gender', $fields['gender']);
    
        $driverMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('own_truck', $fields['own_truck']);
    
        $driverMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('cnh', $fields['cnh']);
    
    
        $this->app->instance(Driver::class, $driverMock);
        $this->app->make(DriverService::class)->update($id, $fields);
    }
    
    public function testGetDeletedById()
    {
        $id = 1;
        
        $driverMock = $this->mock(Driver::class);
        $driverMock
            ->shouldReceive('onlyTrashed')
            ->once()
            ->andReturnSelf();
    
        $driverMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with($id)
            ->andReturnSelf();
    
        $this->app->instance(Driver::class, $driverMock);
        $this->app->make(DriverService::class)->getDeletedById($id);
    }
    
    public function testRecoverById()
    {
        $id = 1;
    
        $driverMock = $this->mock(Driver::class);
        $driverMock
            ->shouldReceive('onlyTrashed')
            ->once()
            ->andReturnSelf();
    
        $driverMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with($id)
            ->andReturnSelf();
    
        $driverMock
            ->shouldReceive('restore')
            ->once();
    
        $this->app->instance(Driver::class, $driverMock);
        $this->app->make(DriverService::class)->recoverById($id);
    }
    
    public function testCreate()
    {
        $fields = [
            'name'      => 'Name',
            'cpf'       => '11122233344',
            'gender'    => Gender::MALE,
            'own_truck' => true,
            'cnh'       => CNH::E,
            'birth_date' => Carbon::now()
        ];
    
        $driverMock = $this->mock(Driver::class);
    
        $driverMock
            ->shouldReceive('create')
            ->with($fields)
            ->andReturnSelf();
            
        $this->app->instance(Driver::class, $driverMock);
        $this->app->make(DriverService::class)->create($fields);
    }
    
    public function testGetAll()
    {
        $genders = [Gender::MALE, Gender::OTHERS];
        $driverMock = $this->mock(Driver::class);
        $driverMock
            ->shouldReceive('getFilters')
            ->once()
            ->andReturn(['genders' => 'in']);
        
        $driverMock
            ->shouldReceive('whereIn')
            ->once()
            ->with('genders', $genders)
            ->andReturnSelf();
    
        $this->app->instance(Driver::class, $driverMock);
        $this->app->make(DriverService::class)->get(['genders' => $genders]);
    }
}
