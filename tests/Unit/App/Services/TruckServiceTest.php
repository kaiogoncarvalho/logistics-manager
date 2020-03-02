<?php

namespace Tests\Unit\App\Services;

use App\Models\Truck;
use Tests\TestCase;
use App\Services\TruckService;
use Illuminate\Pagination\LengthAwarePaginator;

class TruckServiceTest extends TestCase
{
    
    public function testDelete()
    {
        $id = 1;
    
        $truckMock = $this->mock(Truck::class);
        $truckMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with(
                $id
            )->andReturnSelf();
    
        $truckMock
            ->shouldReceive('delete')
            ->once();
   
    
        $this->app->instance(Truck::class, $truckMock);
        $this->app->make(TruckService::class)->delete($id);
    }
    
    public function testGetAllDeleted()
    {
        $truckMock = $this->mock(Truck::class);
        $truckMock
            ->shouldReceive('onlyTrashed')
            ->once()
            ->andReturnSelf();
    
        $page = 1;
        $perPage = 10;
        
        $truckMock
            ->shouldReceive('paginate')
            ->once()
            ->with($perPage, '*', 'page', $page)
            ->andReturn($this->mock(LengthAwarePaginator::class));
    
        $this->app->instance(Truck::class, $truckMock);
        $this->app->make(TruckService::class)->getAllDeleted($perPage, $page);
    }
    
    public function testGetById()
    {
        $id = 1;
    
        $truckMock = $this->mock(Truck::class);
        $truckMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with(
                $id
            )->andReturnSelf();
    
        $this->app->instance(Truck::class, $truckMock);
        $this->app->make(TruckService::class)->getById($id);
    }
    
    public function testUpdate()
    {
        $id = 1;
        
        $fields = [
            'name'      => 'Name',
        ];
        
        $truckMock = $this->mock(Truck::class);
        
        $truckMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with(
                $id
            )->andReturnSelf();
    
        $truckMock
            ->shouldReceive('save')
            ->once();
        
        $truckMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('name', $fields['name']);
            
        $this->app->instance(Truck::class, $truckMock);
        $this->app->make(TruckService::class)->update($id, $fields);
    }
    
    public function testGetDeletedById()
    {
        $id = 1;
        
        $truckMock = $this->mock(Truck::class);
        $truckMock
            ->shouldReceive('onlyTrashed')
            ->once()
            ->andReturnSelf();
    
        $truckMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with($id)
            ->andReturnSelf();
    
        $this->app->instance(Truck::class, $truckMock);
        $this->app->make(TruckService::class)->getDeletedById($id);
    }
    
    public function testRecoverById()
    {
        $id = 1;
    
        $truckMock = $this->mock(Truck::class);
        $truckMock
            ->shouldReceive('onlyTrashed')
            ->once()
            ->andReturnSelf();
    
        $truckMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with($id)
            ->andReturnSelf();
    
        $truckMock
            ->shouldReceive('restore')
            ->once();
    
        $this->app->instance(Truck::class, $truckMock);
        $this->app->make(TruckService::class)->recoverById($id);
    }
    
    public function testCreate()
    {
        $fields = [
            'name'      => 'Name'
        ];
    
        $truckMock = $this->mock(Truck::class);
    
        $truckMock
            ->shouldReceive('create')
            ->with($fields)
            ->andReturnSelf();
            
        $this->app->instance(Truck::class, $truckMock);
        $this->app->make(TruckService::class)->create($fields);
    }
    
    public function testGetAll()
    {
        $page = 1;
        $perPage = 10;
        
        $truckMock = $this->mock(Truck::class);
        $truckMock
            ->shouldReceive('paginate')
            ->once()
            ->with($perPage, '*', 'page', $page)
            ->andReturn($this->mock(LengthAwarePaginator::class));
    
        $this->app->instance(Truck::class, $truckMock);
        $this->app->make(TruckService::class)->getAll($perPage, $page);
    }
}
