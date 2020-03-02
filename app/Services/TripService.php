<?php

namespace App\Services;

use App\Models\Trip;
use Carbon\Carbon;
use App\Services\Traits\Filters;
use App\Services\Traits\Order;

/**
 * Class TripService
 * @package App\Services
 */
class TripService implements CrudService
{
    use Filters, Order;
    
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
    
    public function get(array $filters = [], $order = null)
    {
        return $this->order(
            $this->filter($this->trip, $filters),
            $order
        );
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
        
        if (array_key_exists('origin', $fields)) {
            $trip->origin = $fields['origin'];
        }
        
        if (array_key_exists('destiny', $fields)) {
            $trip->destiny = $fields['destiny'];
        }
        
        $trip->trip_date = $fields['trip_date'] ?? $trip->trip_date;
        $trip->loaded = $fields['loaded'] ?? $trip->loaded;
        
        $trip->save();
        
        return $trip;
    }
    
    public function getDeleted(array $filters = [], $order = null)
    {
        return $this->order(
            $this->filter(
                $this->trip,
                $filters
            ),
            $order
        )->onlyTrashed();
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
    
    public function getByLoaded(string $frequency)
    {
        $trips = $this
            ->trip
            ->where('loaded', true);
        
        switch ($frequency) {
            case 'week':
                $today = (new \DateTime())->format('D');
                if ($today === 'Sun') {
                    $startWeek = 'now';
                }
                $startDate = (new \DateTime($startWeek ?? 'sunday last week'))->format('Y-m-d 00:00:00');
                $endDate = (new \DateTime('saturday this week'))->format('Y-m-d 23:59:59');
                break;
            case 'month':
                $startDate = (new \DateTime('first day of this month'))->format('Y-m-d 00:00:00');
                $endDate = (new \DateTime('last day of this month'))->format('Y-m-d 23:59:59');
                break;
        }
        
        return $trips
            ->where('trip_date', '>=', $startDate ?? Carbon::now()->format('Y-m-d 00:00:00'))
            ->where('trip_date', '<=', $endDate ?? Carbon::now()->format('Y-m-d 23:59:59'));
    }
    
}
