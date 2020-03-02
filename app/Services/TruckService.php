<?php

namespace App\Services;

use App\Models\Truck;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class TruckService
 * @package App\Services
 */
class TruckService implements CrudService
{
    /**
     * @var Truck
     */
    private Truck $truck;
    
    /**
     * TruckService constructor.
     * @param Truck $truck
     */
    public function __construct(Truck $truck)
    {
        $this->truck = $truck;
    }
    
    /**
     * @param int $id
     * @return Truck
     */
    public function getById(int $id): Truck
    {
        return $this->truck->findOrFail($id);
    }
    
    public function getAll(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        return $this->truck->paginate($perPage, '*', 'page', $page);
    }
    
    public function create(array $fields): Truck
    {
        return $this->truck->create(
            [
                'name' => $fields['name']
            ]
        );
    }
    
    public function delete(int $id): void
    {
        $this->truck->findOrFail($id)->delete();
    }
    
    public function update(int $id, array $fields = []): Truck
    {
        $truck = $this
            ->truck
            ->findOrFail($id);
        
        $truck->name = $fields['name'] ?? $truck->name;
        
        $truck->save();
        
        return $truck;
    }
    
    public function getAllDeleted(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        return $this
            ->truck
            ->onlyTrashed()
            ->paginate(
                $perPage,
                '*',
                'page',
                $page
            );
    }
    
    public function getDeletedById(int $driver_id): Truck
    {
        return $this
            ->truck
            ->onlyTrashed()
            ->findOrFail($driver_id);
    }
    
    public function recoverById(int $driver_id): Truck
    {
        $truck = $this->getDeletedById($driver_id);
        $truck->restore();
        return $truck;
    }
    
}
