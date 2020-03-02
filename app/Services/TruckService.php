<?php

namespace App\Services;

use App\Models\Truck;
use App\Services\Traits\Filters;
use App\Services\Traits\Order;

/**
 * Class TruckService
 * @package App\Services
 */
class TruckService implements CrudService
{
    use Filters, Order;
    
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
    
    public function get(array $filters, $order = null)
    {
        return $this->order(
            $this->filter($this->truck, $filters),
            $order
        );
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
    
    public function getDeleted(array $filters, $order = null)
    {
        return $this->order(
            $this->filter(
                $this
                    ->truck
                    ->onlyTrashed(),
                $filters
            ),
            $order
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
    
    public function getWithTrips()
    {
        return $this->truck->has('trips')->with('trips:id,truck_id,origin,destiny')->select(['id', 'name'])->get();
    }
    
}
