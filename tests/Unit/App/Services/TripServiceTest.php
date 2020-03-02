<?php

namespace Tests\Unit\App\Services;

use App\Models\Trip;
use Tests\TestCase;
use App\Services\TripService;

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
    
    public function testGetDeleted()
    {
        $tripMock = $this->mock(Trip::class);
        $tripMock
            ->shouldReceive('getFilters')
            ->once()
            ->andReturn([]);
        $tripMock
            ->shouldReceive('onlyTrashed')
            ->once()
            ->andReturnSelf();
    
        $this->app->instance(Trip::class, $tripMock);
        $this->app->make(TripService::class)->getDeleted();
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
        $tripMock = $this->mock(Trip::class);
        $tripMock
            ->shouldReceive('getFilters')
            ->once()
            ->andReturn(['trip_date' => '>=']);
    
        $tripMock
            ->shouldReceive('where')
            ->once()
            ->with('trip_date', '>=', "2020-02-02 00:00:00")
            ->andReturnSelf();
    
        $this->app->instance(Trip::class, $tripMock);
        $this->app->make(TripService::class)->get(['trip_date' => "2020-02-02 00:00:00"]);
    }
}
