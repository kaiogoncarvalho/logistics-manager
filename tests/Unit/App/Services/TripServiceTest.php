<?php

namespace Tests\Unit\App\Services;

use App\Models\Trip;
use Tests\TestCase;
use App\Services\TripService;
use Illuminate\Pagination\LengthAwarePaginator;

class TripServiceTest extends TestCase
{
    
    public function testDelete()
    {
        $id = 1;
    
        $tripMock = $this->mock(Trip::class);
        $tripMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with(
                $id
            )->andReturnSelf();
    
        $tripMock
            ->shouldReceive('delete')
            ->once();
   
    
        $this->app->instance(Trip::class, $tripMock);
        $this->app->make(TripService::class)->delete($id);
    }
    
    public function testGetAllDeleted()
    {
        $tripMock = $this->mock(Trip::class);
        $tripMock
            ->shouldReceive('onlyTrashed')
            ->once()
            ->andReturnSelf();
    
        $page = 1;
        $perPage = 10;
        
        $tripMock
            ->shouldReceive('paginate')
            ->once()
            ->with($perPage, '*', 'page', $page)
            ->andReturn($this->mock(LengthAwarePaginator::class));
    
        $this->app->instance(Trip::class, $tripMock);
        $this->app->make(TripService::class)->getAllDeleted($perPage, $page);
    }
    
    public function testGetById()
    {
        $id = 1;
    
        $tripMock = $this->mock(Trip::class);
        $tripMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with(
                $id
            )->andReturnSelf();
    
        $this->app->instance(Trip::class, $tripMock);
        $this->app->make(TripService::class)->getById($id);
    }
    
    public function testUpdate()
    {
        $id = 1;
        
        $fields = [
            'driver_id' => 1,
            'truck_id'  => 1,
            'loaded'    => $this->faker->boolean,
            'origin'    => [
                'lat' => $this->faker->latitude,
                'lon' => $this->faker->longitude
            ],
            'trip_date' => $this->faker->date()
        ];
        
        $tripMock = $this->mock(Trip::class);
        
        $tripMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with(
                $id
            )->andReturnSelf();
    
        $tripMock
            ->shouldReceive('save')
            ->once();
            
        $tripMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('driver_id', $fields['driver_id']);
    
        $tripMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('truck_id', $fields['truck_id']);
            
        $tripMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('loaded', $fields['loaded']);
    
        $tripMock
            ->shouldReceive('setAttribute')
            ->once()
            ->withSomeOfArgs('origin');
        
    
        $tripMock
            ->shouldReceive('setAttribute')
            ->once()
            ->with('trip_date', $fields['trip_date']);
    
    
        $this->app->instance(Trip::class, $tripMock);
        $this->app->make(TripService::class)->update($id, $fields);
    }
    
    public function testGetDeletedById()
    {
        $id = 1;
        
        $tripMock = $this->mock(Trip::class);
        $tripMock
            ->shouldReceive('onlyTrashed')
            ->once()
            ->andReturnSelf();
    
        $tripMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with($id)
            ->andReturnSelf();
    
        $this->app->instance(Trip::class, $tripMock);
        $this->app->make(TripService::class)->getDeletedById($id);
    }
    
    public function testRecoverById()
    {
        $id = 1;
    
        $tripMock = $this->mock(Trip::class);
        $tripMock
            ->shouldReceive('onlyTrashed')
            ->once()
            ->andReturnSelf();
    
        $tripMock
            ->shouldReceive('findOrFail')
            ->once()
            ->with($id)
            ->andReturnSelf();
    
        $tripMock
            ->shouldReceive('restore')
            ->once();
    
        $this->app->instance(Trip::class, $tripMock);
        $this->app->make(TripService::class)->recoverById($id);
    }
    
    public function testCreate()
    {
        $fields = [
            'driver_id' => 1,
            'truck_id'  => 1,
            'loaded'    => $this->faker->boolean,
            'origin'    => [
                'lat' => $this->faker->latitude,
                'lon' => $this->faker->longitude
            ],
            'destiny'   => [
                'lat' => $this->faker->latitude,
                'lon' => $this->faker->longitude
            ],
            'trip_date' => $this->faker->date()
        ];
        
        
        $tripMock = $this->mock(Trip::class);
        
        $tripMock
            ->shouldReceive('create')
            ->with($fields)
            ->andReturnSelf();
            
        $this->app->instance(Trip::class, $tripMock);
        $this->app->make(TripService::class)->create($fields);
    }
    
    public function testGetAll()
    {
        $page = 1;
        $perPage = 10;
        
        $tripMock = $this->mock(Trip::class);
        $tripMock
            ->shouldReceive('paginate')
            ->once()
            ->with($perPage, '*', 'page', $page)
            ->andReturn($this->mock(LengthAwarePaginator::class));
    
        $this->app->instance(Trip::class, $tripMock);
        $this->app->make(TripService::class)->getAll($perPage, $page);
    }
}
