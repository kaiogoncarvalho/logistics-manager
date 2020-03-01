<?php

namespace App\Services;

use App\Models\Trip;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

/**
 * Class TripService
 * @package App\Services
 */
class TripService implements CrudService
{
    /**
     * @var Trip
     */
    private Trip $trip;
    
    /**
     * TripService constructor.
     * @param Trip $trip
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }
    
    /**
     * @param int $id
     * @return Trip
     */
    public function getById(int $id): Trip
    {
        return $this->trip->findOrFail($id);
    }
    
    public function getAll(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        return $this->trip->paginate($perPage, '*', 'page', $page);
    }
    
    public function create(array $fields): Trip
    {
        
        return $this->trip->create(
            [
                'driver_id' => $fields['driver_id'],
                'truck_id'  => $fields['truck_id'],
                'loaded'    => $fields['loaded'],
                'origin'    => $fields['origin'],
                'destiny'   => $fields['destiny'],
                'trip_date' => $fields['trip_date'] ?? Carbon::now()
            ]
        );
    }
    
    public function delete(int $id): void
    {
        $this->trip->findOrFail($id)->delete();
    }
    
    public function update(int $id, array $fields = []): Trip
    {
        $trip = $this
            ->trip
            ->findOrFail($id);
        
        $trip->driver_id = $fields['driver_id'] ?? $trip->driver_id;
        $trip->truck_id = $fields['truck_id'] ?? $trip->truck_id;
        
        if(array_key_exists('origin', $fields)){
            $trip->origin = $fields['origin'];
        }
    
        if(array_key_exists('destiny', $fields)){
            $trip->destiny = $fields['destiny'];
        }
        
        $trip->trip_date = $fields['trip_date'] ?? $trip->trip_date;
        $trip->loaded = $fields['loaded'] ?? $trip->loaded;
        
        $trip->save();
        
        return $trip;
    }
    
    public function getAllDeleted(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        return $this
            ->trip
            ->onlyTrashed()
            ->paginate(
                $perPage,
                '*',
                'page',
                $page
            );
    }
    
    public function getDeletedById(int $driver_id): Trip
    {
        return $this
            ->trip
            ->onlyTrashed()
            ->findOrFail($driver_id);
    }
    
    public function recoverById(int $driver_id): Trip
    {
        $trip = $this->getDeletedById($driver_id);
        $trip->restore();
        return $trip;
    }
    
}
